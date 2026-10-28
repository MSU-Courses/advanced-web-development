<?php

/**
 * Класс Validator - проверяет данные по правилам.
 *
 * Как использовать:
 *   $validator = new Validator($data, $rules);
 *   if ($validator->validate()) { ... } else { $errors = $validator->getErrors(); }
 *
 * Правила передаются массивом:
 *   'field' => ['required', 'email', 'min:5', 'max:100', 'enum:1,2,3']
 */
class Validator
{
   // Данные, которые нужно проверить (например $_POST)
   private array $data;

   // Правила для каждого поля
   private array $rules;

   // Сюда складываем ошибки: ['field' => ['ошибка 1', 'ошибка 2']]
   private array $errors = [];

   public function __construct(array $data, array $rules)
   {
      $this->data  = $data;
      $this->rules = $rules;
   }

   public function validate(): bool
   {
      $this->errors = [];

      foreach ($this->rules as $field => $rules) {
         $value = $this->data[$field] ?? '';

         foreach ($rules as $rule) {
            $this->checkRule($field, $value, $rule);
         }
      }

      return empty($this->errors);
   }

   private function checkRule(string $field, string $value, string $rule): void
   {
      // Правила с параметром выглядят так: "min:5", "max:100", "enum:1,2,3"
      // Разбиваем на имя правила и параметр
      $param = null;
      if (str_contains($rule, ':')) {
         $parts = explode(':', $rule, 2);
         $rule  = $parts[0];
         $param = $parts[1];
      }

      // Если поле пустое и правило не required - пропускаем остальные проверки
      if ($value === '' && $rule !== 'required') {
         return;
      }

      switch ($rule) {

         case 'required':
            if (trim($value) === '') {
               $this->addError($field, 'Поле обязательно для заполнения.');
            }
            break;

         case 'email':
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
               $this->addError($field, 'Введите корректный email-адрес.');
            }
            break;

         case 'min':
            if (mb_strlen($value) < (int) $param) {
               $this->addError($field, "Минимальная длина - {$param} символов.");
            }
            break;

         case 'max':
            if (mb_strlen($value) > (int) $param) {
               $this->addError($field, "Максимальная длина - {$param} символов.");
            }
            break;

         case 'enum':
            // Параметр: "1,2,3" → превращаем в массив ['1','2','3']
            $allowed = explode(',', $param);
            if (!in_array($value, $allowed, true)) {
               $this->addError($field, "Выберите одно из допустимых значений: {$param}.");
            }
            break;

         case 'numeric':
            if (!is_numeric($value)) {
               $this->addError($field, 'Поле должно содержать число.');
            }
            break;

         case 'alpha':
            // Только буквы (включая кириллицу)
            if (!preg_match('/^[\p{L}]+$/u', $value)) {
               $this->addError($field, 'Поле должно содержать только буквы.');
            }
            break;

         case 'url':
            if (!filter_var($value, FILTER_VALIDATE_URL)) {
               $this->addError($field, 'Введите корректный URL.');
            }
            break;
      }
   }

   private function addError(string $field, string $message): void
   {
      $this->errors[$field][] = $message;
   }


   // Все ошибки: ['name' => ['ошибка'], 'email' => ['ошибка']]
   public function getErrors(): array
   {
      return $this->errors;
   }

   // Первая ошибка конкретного поля (удобно для вывода в форме)
   public function getError(string $field): string
   {
      return $this->errors[$field][0] ?? '';
   }

   // Есть ли ошибки вообще?
   public function hasErrors(): bool
   {
      return !empty($this->errors);
   }
}
