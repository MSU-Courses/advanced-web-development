<?php

namespace App\Core;

use App\Core\Validator;

class ContactFormValidator
{
   private Validator $validator;
   private array $data;

   public const CATEGORIES = [
      '1' => 'Категория 1',
      '2' => 'Категория 2',
      '3' => 'Категория 3',
   ];

   private array $rules = [
      'name'     => 'required|between:3,50|alpha',
      'email'    => 'required|email|max:255',
      'message'  => 'required|between:10,1000',
      'category' => 'required|enum:1,2,3',
   ];

   private array $labels = [
      'name'     => 'Имя',
      'email'    => 'Email',
      'message'  => 'Сообщение',
      'category' => 'Категория',
   ];

   private array $customMessages = [
      'name.alpha'       => 'Имя должно содержать только буквы.',
      'name.between'     => 'Имя должно содержать от 3 до 50 символов.',
      'email.email'      => 'Пожалуйста, введите корректный email-адрес.',
      'message.between'  => 'Сообщение должно содержать от 10 до 1000 символов.',
      'category.enum'    => 'Выберите допустимую категорию.',
   ];

   public function __construct(array $rawData)
   {
      $this->data      = $this->sanitize($rawData);
      $this->validator = Validator::make(
         $this->data,
         $this->rules,
         $this->customMessages,
         $this->labels
      );
   }

   public function validate(): bool
   {
      return $this->validator->validate();
   }

   public function fails(): bool
   {
      return $this->validator->fails();
   }

   public function errors(): array
   {
      return $this->validator->errors();
   }

   public function firstError(string $field): string
   {
      return $this->validator->firstError($field);
   }

   public function validated(): array
   {
      if ($this->validator->fails()) {
         throw new \RuntimeException('Нельзя получить validated() — форма не прошла валидацию.');
      }

      return array_intersect_key($this->data, $this->rules);
   }

   private function sanitize(array $raw): array
   {
      return [
         'name'     => htmlspecialchars(trim($raw['name']     ?? ''), ENT_QUOTES, 'UTF-8'),
         'email'    => strtolower(trim($raw['email']          ?? '')),
         'message'  => htmlspecialchars(trim($raw['message']  ?? ''), ENT_QUOTES, 'UTF-8'),
         'category' => trim($raw['category']                  ?? ''),
      ];
   }

   public function getData(): array
   {
      return $this->data;
   }

   public function getValue(string $field): string
   {
      return $this->data[$field] ?? '';
   }
}
