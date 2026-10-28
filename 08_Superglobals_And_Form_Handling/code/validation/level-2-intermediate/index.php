<?php

require_once 'handler.php';

// Хелпер - возвращает старое значение поля (чтобы не терять введённые данные)
function old(string $field, array $oldValues): string
{
   return htmlspecialchars($oldValues[$field] ?? '', ENT_QUOTES, 'UTF-8');
}

// Хелпер - возвращает первую ошибку поля
function error(string $field, array $errors): string
{
   return $errors[$field][0] ?? '';
}

?>
<!DOCTYPE html>
<html lang="ru">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Контактная форма</title>
   <style>
      *,
      *::before,
      *::after {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body {
         font-family: Arial, sans-serif;
         background-color: #f5f5f5;
         padding: 40px 20px;
      }

      form {
         background: #fff;
         max-width: 500px;
         margin: 0 auto;
         padding: 30px;
         border-radius: 8px;
         box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
      }

      h2 {
         margin-bottom: 24px;
         font-size: 20px;
         color: #333;
      }

      .form-group {
         margin-bottom: 18px;
      }

      label {
         display: block;
         margin-bottom: 6px;
         font-weight: bold;
         color: #333;
         font-size: 14px;
      }

      input[type="text"],
      input[type="email"],
      textarea,
      select {
         width: 100%;
         padding: 10px 12px;
         border: 1px solid #ddd;
         border-radius: 4px;
         font-family: Arial, sans-serif;
         font-size: 14px;
      }

      textarea {
         resize: vertical;
      }

      input:focus,
      textarea:focus,
      select:focus {
         outline: none;
         border-color: #4CAF50;
         box-shadow: 0 0 0 3px rgba(76, 175, 80, .15);
      }

      /* Красная рамка у полей с ошибкой */
      .has-error input,
      .has-error textarea,
      .has-error select {
         border-color: #d32f2f;
      }

      .error {
         color: #d32f2f;
         font-size: 12px;
         margin-top: 4px;
      }

      button[type="submit"] {
         width: 100%;
         padding: 12px;
         background-color: #4CAF50;
         color: white;
         border: none;
         border-radius: 4px;
         font-size: 15px;
         font-weight: bold;
         cursor: pointer;
         margin-top: 8px;
      }

      button[type="submit"]:hover {
         background-color: #45a049;
      }

      .success {
         max-width: 500px;
         margin: 0 auto 16px;
         padding: 12px 16px;
         background: #e8f5e9;
         border: 1px solid #81c784;
         border-radius: 4px;
         color: #2e7d32;
         font-size: 14px;
      }
   </style>
</head>

<body>

   <?php if (isset($_GET['success'])): ?>
      <div class="success">✓ Сообщение успешно отправлено!</div>
   <?php endif; ?>

   <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

      <div class="form-group <?php echo error('name', $errors) ? 'has-error' : ''; ?>">
         <label for="name">Имя</label>
         <input type="text" name="name" id="name" required
            placeholder="Иван Иванов"
            value="<?php echo old('name', $oldValues); ?>">
         <div class="error"><?php echo error('name', $errors); ?></div>
      </div>

      <div class="form-group <?php echo error('email', $errors) ? 'has-error' : ''; ?>">
         <label for="email">Email</label>
         <input type="email" name="email" id="email" required
            placeholder="example@mail.ru"
            value="<?php echo old('email', $oldValues); ?>">
         <div class="error"><?php echo error('email', $errors); ?></div>
      </div>

      <div class="form-group <?php echo error('message', $errors) ? 'has-error' : ''; ?>">
         <label for="message">Сообщение</label>
         <textarea name="message" id="message" required rows="5"
            placeholder="Ваше сообщение..."><?php echo old('message', $oldValues); ?></textarea>
         <div class="error"><?php echo error('message', $errors); ?></div>
      </div>

      <div class="form-group <?php echo error('category', $errors) ? 'has-error' : ''; ?>">
         <label for="category">Категория</label>
         <select name="category" id="category" required>
            <option value="">Выберите категорию</option>
            <option value="1" <?php echo old('category', $oldValues) === '1' ? 'selected' : ''; ?>>Категория 1</option>
            <option value="2" <?php echo old('category', $oldValues) === '2' ? 'selected' : ''; ?>>Категория 2</option>
            <option value="3" <?php echo old('category', $oldValues) === '3' ? 'selected' : ''; ?>>Категория 3</option>
         </select>
         <div class="error"><?php echo error('category', $errors); ?></div>
      </div>

      <button type="submit">Отправить сообщение</button>

   </form>

</body>

</html>