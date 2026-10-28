```markdown
# Spike: Contract Selection Rules

Investigation of how to add new conditions (APR, Sales Partner, WebBank eligibility, Major
Accounts) to the contract selection rules screen, alongside the template list on the right.

## How contract selection rules work today

_Purpose_. Decide which merchant agreement `.dotx` template may be used for a contract.

- _Lookup_. `DocumentManager::merchantAgreementTemplate()` (app/Lib/DocumentManager.php:3326-3421) copies five facts onto a contract stub at:
  - `FundingAmount`
  - `flagContractType`
  - `contractType_id`
  - `turnUnderwriter`
  - `Salesrep.IsoPolicyFlag`

- _Ordering_. `ContractManager::getActiveContractTemplateRules()` (app/Lib/ContractManager.php) returns blacklist rules first, then turn rules, then the rest, each sorted `rule_count    DESC, id DESC`. The caller takes the first match and throws an exception if nothing matches.

- _Comparison is in-memory PHP_ (`DocumentManager.php`), a logical AND over every filled condition. No operator is stored anywhere: each comparison is hard-coded, and ranges are pairs of nullable columns where NULL means no bound.

- `rule_count` is priority, not a condition. One point per filled condition

Ten conditions exist today:

| Column or join                | Control                  | Comparison            | Matcher               |
| ----------------------------- | ------------------------ | --------------------- | --------------------- |
| `primary_address_state_id`    | select + "Any State"     | `=`                   | `DocumentManager.php` |
| `funding_amount_min` / `_max` | two money fields         | `>=` / `<=` inclusive | `DocumentManager.php` |
| `turn_min` / `turn_max`       | two number fields        | `>=` / `<=` inclusive | `DocumentManager.php` |
| `subsidiary_id`               | select of brands + "Any" | `=`                   | `DocumentManager.php` |
| business types (HABTM)        | checkbox group           | `IN`                  | `DocumentManager.php` |
| `contractType_id`             | select + "Any"           | `=`                   | `DocumentManager.php` |
| funding sources (HABTM)       | select + "Any"           | `IN` over bitmask     | `DocumentManager.php` |
| `is_ca_broker_licensed`       | select Yes/No + "Any"    | one-sided `=`         | `DocumentManager.php` |

Constraints that shape any estimate:

- The form is a plain POST (`app/Controller/AdminController.php`), opened in a modal, with
  no AJAX save.
- _There is no edit action._ Changing a rule means adding a new one and disabling the old.
- _No client-side validation_ (`app/webroot/js/admin.js` only attaches a datepicker), and server-side is per field only: `numeric` or `boolean` with `allowEmpty` (`app/Model/ContractTemplateRule.php`).

## 1. APR

_What exists_

_Source for the rule:_ `offers.apr` on the offer the document is generated for. This is the right granularity, because the template is selected per offer and per request, not per contract.

_Reading it costs nothing extra_. `getOfferRequest()` (app/Lib/OfferManager.php) already loads the whole offer row when the contract stub is built.

_Answers_

1. _First implementation?_ Yes. The value sits on the offer row that is already loaded, and the matcher stays a pure in-memory comparison.
2. _Data source and values_. Deal side: `offers.apr`, percentage from 0 to 100 with two decimals. Empty for frequencies the APR service does not support (only daily, weekly, biweekly and monthly are supported, app/Lib/OfferManager.php) and for offers selected before APR storage shipped.
3. _Control_. An operator select with `=`, `>=`, `<=` and `in range`, driving one or two number inputs.
4. _Validation and dependencies_.
   1. Minimum not above maximum;
   2. both bounds within 0 to 100;
   3. = compares both sides rounded to two decimals, otherwise it will rarely fire on a calculated value.
5. _Next to the templates_. Left column, directly after Maximum Turn, so the three ranges sit together. In the grid, APR reuses the existing range prose with a percent sign: "At least 40%", "Up to 60%", "Between 40% and 60%", "Exactly 45%".

## 2. Sales Partner

_What exists_

1. No partner table exists. A sales partner is a contacts row whose contactType*id is a sales type (app/Lib/ContactManager.php): \_4 Sales Partner*, _5 ISO_, _6 Super ISO_, _13 Sales VP_, _20 Direct Sales_, _24 Referral_.

_Answers_

1. _First implementation?_ Yes. The list is reused from the contract filter, the only new logic is excluding marked reps.
2. _Data source and values_. Deal side: `contracts.salesRep_id`, read from the contract rather than the merchant, because it can be edited per contract. Values are ids of contacts with `contactType_id` in `4`, `5`, `6`, `13`, `20`, `24`, or only `4` (open question 4), whose displayed name contains none of the status markers `DECLINED`, `INACTIVE`, `TERMINATED`, `BLACKLISTED`, and whose `isBlacklisted` is not set. Rule side: `sales_rep_contact_id` int NULL.
3. _Control_. A select, the same as the contract filter: reuse `ContactManager::salesReps()`, then drop entries whose formatted name contains a marker. Filtering the formatted name rather than one column matches exactly what users see, wherever the marker was typed. Comparison `=`, `NULL` means Any.
4. _Validation and dependencies_. On save, the selected `id` must exist, be one of the allowed sales types.
5. _Next to the templates_. Left column, next to Subsidiary/Colonial, since it will eventually replace it. The grid must show the contact name, not the id, so the controller needs a Contact fetch; the grid resolves nothing by lookup today.

### 3. WebBank eligibility

_What exists_

1. `contracts.is_webbank` and `webbank_funded` are bit `NULL`.
2. That flag is an output of template selection, rewritten on every qualifying generation including back to 0. Using it to choose a template is circular: `NULL` on the first run, stale on a rerun.

_Answers_

1. _First implementation?_ No. Blocked until the policy-driven `webbank_not_eligible` column from the separate ticket exists. It cannot be built on is_webbank.
2. _Data source and values_. Deal side: `contracts.webbank_not_eligible`, set by policy and overridable by the Underwriting group. Values `1`, `0`, `NULL`. Rule side: `webbank_not_eligible tinyint(1) NULL`.
3. _Control_. Dropdown `Yes` / `No` / `Any`. Comparison `=`.
4. _Validation and dependencies_. Only the three allowed values.
5. _Next to the templates_. Left column, next to CA Broker License, so the Yes/No flags sit together. Grid column shows Yes, No or Any.

## Condition model

Keep the shape the table already uses: dedicated nullable columns on contract_template_rules, NULL meaning "Any", comparison hard-coded in the matcher.

All four requested APR operators map onto the same two columns:

| Business          | intent | apr_min | apr_max             | Grid shows |
| ----------------- | ------ | ------- | ------------------- | ---------- |
| >= 40             | 40     | NULL    | At least 40%        |
| <= 60             | NULL   | 60      | Up to 60%           |
| between 40 and 60 | 40     | 60      | Between 40% and 60% |
| = 45              | 45     | 45      | Exactly 45%         |

Use `decimal(5,2)` for the bounds, not the `FLOAT` used on `contracts.aprEstimate`: float boundaries make `>= 45.00` unreliable at exactly that value.

## Summary

| APR                  | Sales Partner                    | WebBank                                              |
| -------------------- | -------------------------------- | ---------------------------------------------------- | ------------------------------------ |
| First implementation | Yes                              | Yes                                                  | No, blocked on policy column         |
| Deal-side source     | offers.aprEstimate               | contracts.salesRep_id                                | contracts.webbank_not_eligible (new) |
| Rule column          | apr_min, apr_max                 | sales_rep_contact_id                                 | webbank_not_eligible                 |
| Control              | operator select + number fields  | select of active sales reps                          | Yes / No / Any                       |
| Operators            | =, >=, <=, between               | =                                                    | =                                    |
| Key validation       | min ≤ max, 0 to 100, missing APR | id exists, is a sales type, no status marker in name | NULL semantics                       |
| Position             | after Maximum Turn               | next to Subsidiary/Colonial                          | next to CA Broker License            |

## Open questions

1. APR, missing values: what should a rule with APR bounds do when the selected offer has no stored APR? Not match, or ignore the APR condition?
2. Sales Partner: all six sales contact types, or only `CONTACT_TYPE_SALES_PARTNER (4)`?
3. Subsidiary/Colonial: replaced by Sales Partner, or kept alongside? They are not equivalent.
4. WebBank: what should a rule asking "No" do when the contract value is NULL?
5. Sales Partner, status markers: the requirement names terminated and inactive; should DECLINED and BLACKLISTED reps be excluded as well?
```
