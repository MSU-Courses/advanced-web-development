<?php

require_once 'Validator.php';

$errors    = [];
$oldValues = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   // Забираем данные из формы и очищаем от лишних пробелов
   $data = [
      'name'     => trim($_POST['name']     ?? ''),
      'email'    => trim($_POST['email']    ?? ''),
      'message'  => trim($_POST['message']  ?? ''),
      'category' => trim($_POST['category'] ?? ''),
   ];

   // Описываем правила для каждого поля
   $rules = [
      'name'     => ['required', 'min:3', 'max:50'],
      'email'    => ['required', 'email'],
      'message'  => ['required', 'min:10', 'max:1000'],
      'category' => ['required', 'enum:1,2,3'],
   ];

   $validator = new Validator($data, $rules);

   if ($validator->validate()) {
      // Всё ок - сохраняем и редиректим
      file_put_contents('submissions.txt', json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
      header('Location: index.php?success=1');
      exit;
   }

   // Есть ошибки - передаём их и старые значения обратно в форму
   $errors    = $validator->getErrors();
   $oldValues = $data;
}
