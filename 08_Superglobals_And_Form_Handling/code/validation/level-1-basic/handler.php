<?php

require_once 'functions.php';

function validateForm(array $data): array
{
   $errors = [];

   // Валидация поля name
   if (!validateRequired('name', $data['name'] ?? '')) {
      $errors['name'][] = 'Введите ваше имя.';
   } elseif (!validateLength('name', $data['name'], 3, 50)) {
      $errors['name'][] = 'Имя должно содержать от 3 до 50 символов.';
   }

   // Валидация поля email
   if (!validateRequired('email', $data['email'] ?? '')) {
      $errors['email'][] = 'Введите ваш email.';
   } elseif (!validateEmail($data['email'])) {
      $errors['email'][] = 'Пожалуйста, введите корректный email.';
   }

   // Валидация поля message
   if (!validateRequired('message', $data['message'] ?? '')) {
      $errors['message'][] = 'Введите ваше сообщение.';
   } elseif (!validateLength('message', $data['message'], 10, 1000)) {
      $errors['message'][] = 'Сообщение должно содержать от 10 до 1000 символов.';
   }

   // Валидация поля category
   if (!validateRequired('category', $data['category'] ?? '')) {
      $errors['category'][] = 'Выберите категорию.';
   } elseif (!validateEnum('category', $data['category'], ['1', '2', '3'])) {
      $errors['category'][] = 'Выберите допустимую категорию.';
   }

   return $errors;
}

// Массив для хранения ошибок валидации
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $formData = [
      'name' => trim($_POST['name'] ?? ''),
      'email' => trim($_POST['email'] ?? ''),
      'message' => trim($_POST['message'] ?? ''),
      'category' => $_POST['category'] ?? '',
   ];

   $errors = validateForm($formData);

   if (empty($errors)) {
      // Данные прошли валидацию, можно продолжать обработку (например, сохранять в БД)
      echo 'Данные успешно обработаны!';
      file_put_contents('submissions.txt', json_encode($formData) . PHP_EOL, FILE_APPEND);
      header('Location: index.php');
      exit;
   }
}
