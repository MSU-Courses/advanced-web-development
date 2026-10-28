<?php

// handler.php

require_once './src/Validator.php';
require_once './src/ContactFormValidator.php';
require_once './src/FormBuilder.php';


use App\Core\ContactFormValidator;


$errors    = [];
$oldValues = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   $formValidator = new ContactFormValidator($_POST);

   if ($formValidator->validate()) {
      // Данные прошли валидацию — получаем чистый массив
      $data = $formValidator->validated();
      file_put_contents('submissions.txt', json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
      header('Location: index.php?success=1');
      exit;
   }

   // Есть ошибки — возвращаем их и старые значения в форму
   $errors    = $formValidator->errors();
   $oldValues = $formValidator->getData();
}
