<?php

namespace App\Core;

class FormBuilder
{
   private string $action;
   private string $method;
   private array  $fields;
   private array  $errors;
   private array  $oldValues;
   private array  $formAttrs;

   public function __construct(
      array  $fields,
      string $action    = '',
      string $method    = 'POST',
      array  $errors    = [],
      array  $oldValues = [],
      array  $formAttrs = []
   ) {
      $this->fields    = $fields;
      $this->action    = $action;
      $this->method    = strtoupper($method);
      $this->errors    = $errors;
      $this->oldValues = $oldValues;
      $this->formAttrs = $formAttrs;
   }

   public static function make(array $fields): static
   {
      return new static($fields);
   }

   public function action(string $url): static
   {
      $this->action = $url;
      return $this;
   }

   public function method(string $method): static
   {
      $this->method = strtoupper($method);
      return $this;
   }

   public function withErrors(array $errors): static
   {
      $this->errors = $errors;
      return $this;
   }

   public function withOldValues(array $values): static
   {
      $this->oldValues = $values;
      return $this;
   }

   public function attr(string $key, string $value): static
   {
      $this->formAttrs[$key] = $value;
      return $this;
   }

   /** Render methods */

   public function render(string $submitLabel = 'Отправить'): string
   {
      $html  = $this->openTag();

      foreach ($this->fields as $field) {
         $html .= $this->renderField($field);
      }

      $html .= $this->renderSubmit($submitLabel);
      $html .= '</form>' . PHP_EOL;

      return $html;
   }

   private function openTag(): string
   {
      $method  = in_array($this->method, ['GET', 'POST']) ? $this->method : 'POST';
      $action  = $this->e($this->action);
      $attrs   = $this->buildAttrs($this->formAttrs);
      return "<form action=\"{$action}\" method=\"{$method}\"{$attrs}>" . PHP_EOL;
   }

   public function renderField(array $field): string
   {
      $name  = $field['name'];
      $type  = $field['type'] ?? 'text';
      $label = $field['label'] ?? $name;
      $error = $this->errors[$name][0] ?? null;

      $html  = "  <div class=\"form-group" . ($error ? ' has-error' : '') . "\">" . PHP_EOL;
      $html .= $this->renderLabel($name, $label, $field['required'] ?? false);

      $html .= match ($type) {
         'textarea' => $this->renderTextarea($field),
         'select'   => $this->renderSelect($field),
         'checkbox' => $this->renderCheckbox($field),
         'radio'    => $this->renderRadio($field),
         default    => $this->renderInput($field),
      };

      if ($error) {
         $html .= "    <div class=\"error\">" . $this->e($error) . "</div>" . PHP_EOL;
      }

      $html .= "  </div>" . PHP_EOL;
      return $html;
   }

   private function renderLabel(string $for, string $text, bool $required): string
   {
      $req = $required ? ' <span class="required" aria-hidden="true">*</span>' : '';
      return "    <label for=\"{$this->e($for)}\">{$this->e($text)}{$req}</label>" . PHP_EOL;
   }


   private function renderInput(array $field): string
   {
      $name        = $field['name'];
      $type        = $field['type'] ?? 'text';
      $value       = $this->e($this->oldValues[$name] ?? $field['value'] ?? '');
      $placeholder = $field['placeholder'] ?? '';
      $required    = ($field['required'] ?? false) ? ' required' : '';
      $extra       = $this->buildAttrs($field['attrs'] ?? []);

      return "    <input type=\"{$type}\" name=\"{$this->e($name)}\" id=\"{$this->e($name)}\""
         . " value=\"{$value}\""
         . ($placeholder ? " placeholder=\"{$this->e($placeholder)}\"" : '')
         . $required . $extra . ">" . PHP_EOL;
   }


   private function renderTextarea(array $field): string
   {
      $name        = $field['name'];
      $value       = $this->e($this->oldValues[$name] ?? $field['value'] ?? '');
      $placeholder = $field['placeholder'] ?? '';
      $required    = ($field['required'] ?? false) ? ' required' : '';
      $rows        = $field['rows'] ?? 5;
      $extra       = $this->buildAttrs($field['attrs'] ?? []);

      return "    <textarea name=\"{$this->e($name)}\" id=\"{$this->e($name)}\""
         . " rows=\"{$rows}\""
         . ($placeholder ? " placeholder=\"{$this->e($placeholder)}\"" : '')
         . $required . $extra . ">{$value}</textarea>" . PHP_EOL;
   }


   private function renderSelect(array $field): string
   {
      $name        = $field['name'];
      $options     = $field['options'] ?? [];
      $currentVal  = $this->oldValues[$name] ?? $field['value'] ?? '';
      $placeholder = $field['placeholder'] ?? 'Выберите...';
      $required    = ($field['required'] ?? false) ? ' required' : '';
      $extra       = $this->buildAttrs($field['attrs'] ?? []);

      $html  = "    <select name=\"{$this->e($name)}\" id=\"{$this->e($name)}\"{$required}{$extra}>" . PHP_EOL;
      $html .= "      <option value=\"\">{$this->e($placeholder)}</option>" . PHP_EOL;

      foreach ($options as $val => $label) {
         $selected = ((string) $currentVal === (string) $val) ? ' selected' : '';
         $html    .= "      <option value=\"{$this->e($val)}\"{$selected}>{$this->e($label)}</option>" . PHP_EOL;
      }

      $html .= "    </select>" . PHP_EOL;
      return $html;
   }

   private function renderCheckbox(array $field): string
   {
      $name    = $field['name'];
      $val     = $field['value'] ?? '1';
      $checked = ($this->oldValues[$name] ?? '') == $val ? ' checked' : '';
      $extra   = $this->buildAttrs($field['attrs'] ?? []);

      return "    <input type=\"checkbox\" name=\"{$this->e($name)}\" id=\"{$this->e($name)}\""
         . " value=\"{$this->e($val)}\"{$checked}{$extra}>" . PHP_EOL;
   }


   private function renderRadio(array $field): string
   {
      $name       = $field['name'];
      $options    = $field['options'] ?? [];
      $currentVal = $this->oldValues[$name] ?? $field['value'] ?? '';
      $html       = '';

      foreach ($options as $val => $label) {
         $id      = $this->e($name) . '_' . $this->e($val);
         $checked = ((string) $currentVal === (string) $val) ? ' checked' : '';
         $html   .= "    <label class=\"radio-label\">"
            .  "<input type=\"radio\" name=\"{$this->e($name)}\" id=\"{$id}\""
            .  " value=\"{$this->e($val)}\"{$checked}> {$this->e($label)}</label>" . PHP_EOL;
      }

      return $html;
   }

   private function renderSubmit(string $label): string
   {
      return "  <button type=\"submit\">{$this->e($label)}</button>" . PHP_EOL;
   }

   private function e(string $value): string
   {
      return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
   }

   private function buildAttrs(array $attrs): string
   {
      $result = '';
      foreach ($attrs as $key => $value) {
         $result .= ' ' . $this->e($key) . '="' . $this->e($value) . '"';
      }
      return $result;
   }
}
