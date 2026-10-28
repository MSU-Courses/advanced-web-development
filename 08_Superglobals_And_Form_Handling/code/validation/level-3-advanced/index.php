<?php


require_once 'handler.php';

use App\Core\ContactFormValidator;
use App\Core\FormBuilder;

// Строим форму через FormBuilder
$form = FormBuilder::make([
   [
      'name'        => 'name',
      'label'       => 'Имя',
      'type'        => 'text',
      'required'    => true,
      'placeholder' => 'Иван Иванов',
   ],
   [
      'name'        => 'email',
      'label'       => 'Email',
      'type'        => 'email',
      'required'    => true,
      'placeholder' => 'example@mail.ru',
   ],
   [
      'name'        => 'message',
      'label'       => 'Сообщение',
      'type'        => 'textarea',
      'required'    => true,
      'placeholder' => 'Ваше сообщение...',
      'rows'        => 5,
   ],
   [
      'name'        => 'category',
      'label'       => 'Категория',
      'type'        => 'select',
      'required'    => true,
      'placeholder' => 'Выберите категорию',
      'options'     => ContactFormValidator::CATEGORIES,
   ],
])
   ->action($_SERVER['PHP_SELF'])
   ->method('POST')
   ->withErrors($errors)
   ->withOldValues($oldValues);

?>
<!DOCTYPE html>
<html lang="ru">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Контактная форма</title>
   <style>
      @import url('https://fonts.googleapis.com/css2?family=Onest:wght@300;400;500;600&display=swap');

      *,
      *::before,
      *::after {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      :root {
         --bg: #f0ede8;
         --surface: #ffffff;
         --border: #d8d3cc;
         --accent: #2d6a4f;
         --accent-h: #1b4332;
         --error: #c0392b;
         --text: #1a1a1a;
         --muted: #7a7670;
         --radius: 6px;
      }

      body {
         font-family: 'Onest', sans-serif;
         background: var(--bg);
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         padding: 40px 20px;
      }

      .card {
         background: var(--surface);
         width: 100%;
         max-width: 480px;
         border-radius: 12px;
         box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
         overflow: hidden;
      }

      .card-header {
         background: var(--accent);
         padding: 28px 32px 24px;
         color: #fff;
      }

      .card-header h1 {
         font-size: 22px;
         font-weight: 600;
         letter-spacing: -0.3px;
      }

      .card-header p {
         margin-top: 6px;
         font-size: 14px;
         opacity: 0.75;
         font-weight: 300;
      }

      form {
         padding: 28px 32px 32px;
      }

      .form-group {
         margin-bottom: 20px;
      }

      label {
         display: block;
         font-size: 13px;
         font-weight: 500;
         color: var(--text);
         margin-bottom: 7px;
         letter-spacing: 0.1px;
      }

      .required {
         color: var(--error);
         margin-left: 2px;
      }

      input[type="text"],
      input[type="email"],
      input[type="password"],
      textarea,
      select {
         width: 100%;
         padding: 10px 13px;
         font-family: inherit;
         font-size: 14px;
         color: var(--text);
         background: #faf9f7;
         border: 1.5px solid var(--border);
         border-radius: var(--radius);
         transition: border-color .18s, box-shadow .18s;
         outline: none;
         -webkit-appearance: none;
      }

      textarea {
         resize: vertical;
         min-height: 110px;
      }

      input:focus,
      textarea:focus,
      select:focus {
         border-color: var(--accent);
         box-shadow: 0 0 0 3px rgba(45, 106, 79, .13);
         background: #fff;
      }

      .has-error input,
      .has-error textarea,
      .has-error select {
         border-color: var(--error);
         background: #fff8f7;
      }

      .has-error input:focus,
      .has-error textarea:focus,
      .has-error select:focus {
         box-shadow: 0 0 0 3px rgba(192, 57, 43, .12);
      }

      .error {
         margin-top: 5px;
         font-size: 12px;
         color: var(--error);
         display: flex;
         align-items: center;
         gap: 4px;
      }

      .error::before {
         content: '⚠';
         font-size: 11px;
      }

      button[type="submit"] {
         width: 100%;
         padding: 12px;
         background: var(--accent);
         color: #fff;
         border: none;
         border-radius: var(--radius);
         font-family: inherit;
         font-size: 15px;
         font-weight: 600;
         cursor: pointer;
         margin-top: 8px;
         letter-spacing: 0.2px;
         transition: background .18s, transform .1s;
      }

      button[type="submit"]:hover {
         background: var(--accent-h);
      }

      button[type="submit"]:active {
         transform: scale(.99);
      }

      .success-banner {
         margin: 0 32px 20px;
         padding: 12px 16px;
         background: #d8f3dc;
         border: 1.5px solid #74c69d;
         border-radius: var(--radius);
         font-size: 14px;
         color: #1b4332;
      }
   </style>
</head>

<body>
   <div class="card">
      <div class="card-header">
         <h1>Свяжитесь с нами</h1>
         <p>Заполните форму, и мы ответим вам в течение 24 часов.</p>
      </div>

      <?php if (isset($_GET['success'])): ?>
         <div class="success-banner">✓ Ваше сообщение успешно отправлено!</div>
      <?php endif; ?>

      <?php echo $form->render('Отправить сообщение'); ?>
   </div>
</body>

</html>