<?php

// index.php

require_once 'handler.php';

?>

<!DOCTYPE html>
<html lang="ru">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Форма</title>
   <style>
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body {
         font-family: Arial, sans-serif;
         background-color: #f5f5f5;
         padding: 20px;
      }

      form {
         background-color: white;
         max-width: 500px;
         margin: 0 auto;
         padding: 30px;
         border-radius: 8px;
         box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      }

      label {
         display: block;
         margin-bottom: 8px;
         font-weight: bold;
         color: #333;
      }

      input[type="text"],
      input[type="email"],
      textarea,
      select {
         width: 100%;
         padding: 10px;
         margin-bottom: 5px;
         border: 1px solid #ddd;
         border-radius: 4px;
         font-family: Arial, sans-serif;
      }

      textarea {
         resize: vertical;
         min-height: 100px;
      }

      input:focus,
      textarea:focus,
      select:focus {
         outline: none;
         border-color: #4CAF50;
         box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
      }

      button {
         width: 100%;
         padding: 12px;
         background-color: #4CAF50;
         color: white;
         border: none;
         border-radius: 4px;
         font-size: 16px;
         font-weight: bold;
         cursor: pointer;
         margin-top: 10px;
      }

      button:hover {
         background-color: #45a049;
      }

      .error {
         color: #d32f2f;
         font-size: 12px;
         margin-bottom: 15px;
      }
   </style>
</head>

<body>
   <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <label for="name">Имя:</label>
      <input type="text" name="name" id="name" required value="<?php echo getOldValue('name'); ?>">
      <div class="error"><?php echo getErrors($errors, 'name'); ?></div>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required value="<?php echo getOldValue('email'); ?>">
      <div class="error"><?php echo getErrors($errors, 'email'); ?></div>

      <label for="message">Сообщение:</label>
      <textarea name="message" id="message" required><?php echo getOldValue('message'); ?></textarea>
      <div class="error"><?php echo getErrors($errors, 'message'); ?></div>

      <label for="category">Категория:</label>
      <select name="category" id="category" required>
         <option value="">Выберите категорию</option>
         <option value="1" <?php echo (getOldValue('category') === '1') ? 'selected' : ''; ?>>Категория 1</option>
         <option value="2" <?php echo (getOldValue('category') === '2') ? 'selected' : ''; ?>>Категория 2</option>
         <option value="3" <?php echo (getOldValue('category') === '3') ? 'selected' : ''; ?>>Категория 3</option>
      </select>
      <div class="error"><?php echo getErrors($errors, 'category'); ?></div>

      <button type="submit">Отправить</button>
   </form>
</body>

</html>