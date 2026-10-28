<?php

namespace App\Core;

/**
 * Class Validator
 * 
 * Ядро системы валидации. Принимает данные и правила,
 * возвращает массив ошибок по каждому полю.
 * 
 * Поддерживаемые правила:
 *   required            — поле обязательно
 *   email               — валидный email
 *   min:N               — минимальная длина строки / минимальное число
 *   max:N               — максимальная длина строки / максимальное число
 *   between:N,M         — длина строки от N до M
 *   numeric             — только цифры
 *   integer             — целое число
 *   alpha               — только буквы
 *   alpha_num           — буквы и цифры
 *   url                 — валидный URL
 *   regex:PATTERN       — соответствие регулярному выражению
 *   enum:a,b,c          — значение из списка
 *   confirmed:field     — совпадение с другим полем (например, пароль)
 *   unique:array_key    — уникальность в переданном наборе данных
 *   date                — валидная дата (любой формат)
 *   date_format:FORMAT  — дата в конкретном формате (d.m.Y и т.п.)
 *   before:date         — дата раньше указанной
 *   after:date          — дата позже указанной
 *   in_keys:a,b,c       — ключ существует в массиве
 *   not_in:a,b,c        — значение НЕ из списка
 *   starts_with:str     — строка начинается с ...
 *   ends_with:str       — строка заканчивается на ...
 *   contains:str        — строка содержит ...
 *   nullable            — разрешить null/пустое (отключает required-like проверки)
 */
class Validator
{
   private array $data;
   private array $rules;
   private array $customMessages;
   private array $errors = [];
   private array $labels = [];

   // Дефолтные сообщения об ошибках (поддерживают :field, :min, :max, :values и т.д.)
   private array $defaultMessages = [
      'required'    => 'Поле «:field» обязательно для заполнения.',
      'email'       => 'Поле «:field» должно содержать корректный email-адрес.',
      'min'         => 'Поле «:field» должно содержать не менее :min символов.',
      'max'         => 'Поле «:field» не должно превышать :max символов.',
      'between'     => 'Поле «:field» должно содержать от :min до :max символов.',
      'numeric'     => 'Поле «:field» должно быть числом.',
      'integer'     => 'Поле «:field» должно быть целым числом.',
      'alpha'       => 'Поле «:field» должно содержать только буквы.',
      'alpha_num'   => 'Поле «:field» должно содержать только буквы и цифры.',
      'url'         => 'Поле «:field» должно содержать корректный URL.',
      'regex'       => 'Поле «:field» имеет неверный формат.',
      'enum'        => 'Значение поля «:field» должно быть одним из: :values.',
      'not_in'      => 'Значение поля «:field» недопустимо.',
      'confirmed'   => 'Поле «:field» не совпадает с подтверждением.',
      'date'        => 'Поле «:field» должно содержать корректную дату.',
      'date_format' => 'Поле «:field» должно быть в формате :format.',
      'before'      => 'Дата в поле «:field» должна быть раньше :date.',
      'after'       => 'Дата в поле «:field» должна быть позже :date.',
      'starts_with' => 'Поле «:field» должно начинаться с «:value».',
      'ends_with'   => 'Поле «:field» должно заканчиваться на «:value».',
      'contains'    => 'Поле «:field» должно содержать «:value».',
   ];

   public function __construct(array $data, array $rules, array $customMessages = [], array $labels = [])
   {
      $this->data           = $data;
      $this->rules          = $rules;
      $this->customMessages = $customMessages;
      $this->labels         = $labels;
   }

   public static function make(array $data, array $rules, array $messages = [], array $labels = []): static
   {
      return new static($data, $rules, $messages, $labels);
   }

   public function validate(): bool
   {
      $this->errors = [];

      foreach ($this->rules as $field => $ruleSet) {
         $rules    = is_string($ruleSet) ? explode('|', $ruleSet) : $ruleSet;
         $value    = $this->data[$field] ?? null;
         $nullable = in_array('nullable', $rules, true);

         // Пропускаем пустые nullable-поля
         if ($nullable && ($value === null || $value === '')) {
            continue;
         }

         foreach ($rules as $rule) {
            if ($rule === 'nullable') continue;

            [$ruleName, $ruleParam] = $this->parseRule($rule);

            $passed = $this->applyRule($ruleName, $field, $value, $ruleParam);

            if (!$passed) {
               $this->addError($field, $ruleName, $ruleParam);
               // После failed required нет смысла проверять остальное
               if ($ruleName === 'required') break;
            }
         }
      }

      return empty($this->errors);
   }

   private function applyRule(string $rule, string $field, mixed $value, ?string $param): bool
   {
      return match ($rule) {
         'required'    => $this->ruleRequired($value),
         'email'       => $this->ruleEmail($value),
         'numeric'     => is_numeric($value),
         'integer'     => filter_var($value, FILTER_VALIDATE_INT) !== false,
         'alpha'       => ctype_alpha((string) $value),
         'alpha_num'   => ctype_alnum((string) $value),
         'url'         => filter_var($value, FILTER_VALIDATE_URL) !== false,
         'date'        => strtotime((string) $value) !== false,
         'min'         => $this->ruleMin($value, (int) $param),
         'max'         => $this->ruleMax($value, (int) $param),
         'between'     => $this->ruleBetween($value, $param),
         'regex'       => $this->ruleRegex($value, $param),
         'enum'        => $this->ruleEnum($value, $param),
         'not_in'      => !$this->ruleEnum($value, $param),
         'confirmed'   => $this->ruleConfirmed($value, $param),
         'date_format' => $this->ruleDateFormat($value, $param),
         'before'      => $this->ruleBefore($value, $param),
         'after'       => $this->ruleAfter($value, $param),
         'starts_with' => str_starts_with((string) $value, (string) $param),
         'ends_with'   => str_ends_with((string) $value, (string) $param),
         'contains'    => str_contains((string) $value, (string) $param),
         default       => true,
      };
   }

   private function ruleRequired(mixed $value): bool
   {
      if (is_array($value)) return count($value) > 0;
      return $value !== null && $value !== '' && $value !== false;
   }

   private function ruleEmail(mixed $value): bool
   {
      return filter_var((string) $value, FILTER_VALIDATE_EMAIL) !== false;
   }

   private function ruleMin(mixed $value, int $min): bool
   {
      return is_numeric($value)
         ? (float) $value >= $min
         : mb_strlen((string) $value) >= $min;
   }

   private function ruleMax(mixed $value, int $max): bool
   {
      return is_numeric($value)
         ? (float) $value <= $max
         : mb_strlen((string) $value) <= $max;
   }

   private function ruleBetween(mixed $value, ?string $param): bool
   {
      [$min, $max] = array_map('intval', explode(',', (string) $param));
      $len = mb_strlen((string) $value);
      return $len >= $min && $len <= $max;
   }

   private function ruleRegex(mixed $value, ?string $pattern): bool
   {
      return (bool) preg_match($pattern, (string) $value);
   }

   private function ruleEnum(mixed $value, ?string $param): bool
   {
      $allowed = array_map('trim', explode(',', (string) $param));
      return in_array((string) $value, $allowed, true);
   }

   private function ruleConfirmed(mixed $value, ?string $otherField): bool
   {
      $other = $this->data[$otherField] ?? null;
      return $value === $other;
   }

   private function ruleDateFormat(mixed $value, ?string $format): bool
   {
      $d = \DateTime::createFromFormat((string) $format, (string) $value);
      return $d && $d->format((string) $format) === $value;
   }

   private function ruleBefore(mixed $value, ?string $date): bool
   {
      return strtotime((string) $value) < strtotime((string) $date);
   }

   private function ruleAfter(mixed $value, ?string $date): bool
   {
      return strtotime((string) $value) > strtotime((string) $date);
   }


   private function parseRule(string $rule): array
   {
      if (str_contains($rule, ':')) {
         [$name, $param] = explode(':', $rule, 2);
         return [$name, $param];
      }
      return [$rule, null];
   }

   private function addError(string $field, string $rule, ?string $param): void
   {
      $messageKey = "{$field}.{$rule}";

      // Приоритет: кастомное сообщение → дефолтное
      $message = $this->customMessages[$messageKey]
         ?? $this->customMessages[$rule]
         ?? $this->defaultMessages[$rule]
         ?? "Поле «:field» не прошло проверку «{$rule}».";

      $label   = $this->labels[$field] ?? $field;
      $message = str_replace(':field', $label, $message);

      // Подставляем параметры
      if ($param !== null) {
         if (str_contains($rule, 'between')) {
            [$min, $max] = explode(',', $param);
            $message = str_replace([':min', ':max'], [$min, $max], $message);
         } else {
            $message = str_replace(
               [':min', ':max', ':values', ':format', ':date', ':value'],
               [$param,  $param,  $param,   $param,   $param,  $param],
               $message
            );
         }
      }

      $this->errors[$field][] = $message;
   }

   public function fails(): bool
   {
      return !empty($this->errors);
   }

   public function errors(): array
   {
      return $this->errors;
   }

   public function firstError(string $field): string
   {
      return $this->errors[$field][0] ?? '';
   }

   public function allErrors(): array
   {
      $flat = [];
      foreach ($this->errors as $fieldErrors) {
         foreach ($fieldErrors as $err) {
            $flat[] = $err;
         }
      }
      return $flat;
   }
}
