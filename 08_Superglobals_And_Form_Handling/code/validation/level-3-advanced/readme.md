# Продвинутая обработка формы на PHP (ООП)

Этот пример показывает, как организовать форму через классы, чтобы код был:

- читаемым;
- расширяемым;
- безопасным;
- удобным для повторного использования.

Ниже разобрано, что делает **каждый класс**, как они связаны между собой, и как собрать такой подход в своём проекте.

## 1) Что делает каждый файл

- `index.php` - страница с HTML и вызовом `FormBuilder`; показывает форму и успех отправки.
- `handler.php` - принимает `POST`, запускает валидацию, сохраняет результат и передаёт ошибки/старые значения обратно в форму.
- `src/Validator.php` - универсальный движок правил валидации (required, email, min, max и т.д.).
- `src/ContactFormValidator.php` - специализированная валидация именно для контактной формы: правила, подписи полей, кастомные сообщения, санитаризация данных.
- `src/FormBuilder.php` - конструктор HTML-формы по конфигурации полей (input, textarea, select, radio, checkbox) с подсветкой ошибок.

## 2) Как работает поток данных (отправка формы)

1. Пользователь открывает `index.php`.
2. В начале `index.php` подключается `handler.php`.
3. Если запрос не `POST`, форма просто отображается пустой.
4. Если запрос `POST`, в `handler.php` создаётся `ContactFormValidator` и передаются `$_POST`.
5. `ContactFormValidator`:
   - очищает данные (trim, htmlspecialchars, нормализация email);
   - создаёт `Validator` с набором правил.
6. `Validator` проходит по каждому полю и правилу, собирает ошибки.
7. Если ошибок нет:
   - берутся чистые данные через `validated()`;
   - данные сохраняются в `submissions.txt`;
   - выполняется редирект `index.php?success=1` (паттерн PRG).
8. Если ошибки есть:
   - в `index.php` возвращаются массивы `$errors` и `$oldValues`;
   - `FormBuilder` подставляет старые значения и выводит сообщения об ошибках под нужными полями.

## 3) Класс `Validator` (универсальное ядро)

### Назначение

`Validator` ничего не знает о конкретной форме. Он знает только:

- какие пришли данные;
- какие правила применить;
- какие сообщения показать при ошибках.

### Что важно понять студенту

- Это **переиспользуемый сервис**: один класс можно применять для разных форм.
- Правила задаются строкой (`required|email|max:255`) или массивом.
- При ошибке формируется массив `errors[field][]`.

### Ключевые методы

- `validate(): bool` - запускает проверку всех полей.
- `errors(): array` - возвращает ошибки по полям.
- `firstError(string $field): string` - первая ошибка конкретного поля.
- `allErrors(): array` - плоский список всех ошибок.
- `fails(): bool` - есть ли ошибки.
- `make(...)` - фабричный метод для удобного создания объекта.

### Поддерживаемые правила (основные)

- Обязательность: `required`, `nullable`
- Тип/формат: `email`, `numeric`, `integer`, `alpha`, `alpha_num`, `url`, `regex`
- Размер/диапазон: `min`, `max`, `between`
- Принадлежность списку: `enum`, `not_in`
- Сравнение: `confirmed`
- Даты: `date`, `date_format`, `before`, `after`
- Строковые проверки: `starts_with`, `ends_with`, `contains`

### Запуск валидации по полям и правилам

```php
public function validate(): bool
{
   $this->errors = [];

   foreach ($this->rules as $field => $ruleSet) {
      $rules    = is_string($ruleSet) ? explode('|', $ruleSet) : $ruleSet;
      $value    = $this->data[$field] ?? null;
      $nullable = in_array('nullable', $rules, true);

      if ($nullable && ($value === null || $value === '')) {
         continue;
      }

      foreach ($rules as $rule) {
         if ($rule === 'nullable') continue;

         [$ruleName, $ruleParam] = $this->parseRule($rule);
         $passed = $this->applyRule($ruleName, $field, $value, $ruleParam);

         if (!$passed) {
            $this->addError($field, $ruleName, $ruleParam);
            if ($ruleName === 'required') break;
         }
      }
   }

   return empty($this->errors);
}
```

Что важно в этом фрагменте:

- Правила можно передавать строкой (`required|email`) или массивом.
- `nullable` отключает проверки для пустого значения.
- При падении `required` остальные проверки поля сразу прекращаются.

### Маршрутизация правил через `match`

```php
private function applyRule(string $rule, string $field, mixed $value, ?string $param): bool
{
   return match ($rule) {
      'required'    => $this->ruleRequired($value),
      'email'       => $this->ruleEmail($value),
      'numeric'     => is_numeric($value),
      'integer'     => filter_var($value, FILTER_VALIDATE_INT) !== false,
      'min'         => $this->ruleMin($value, (int) $param),
      'max'         => $this->ruleMax($value, (int) $param),
      'between'     => $this->ruleBetween($value, $param),
      'enum'        => $this->ruleEnum($value, $param),
      'not_in'      => !$this->ruleEnum($value, $param),
      default       => true,
   };
}
```

Идея: добавление нового правила обычно сводится к двум шагам:

1. Добавить ветку в `applyRule`.
2. Добавить метод `ruleXxx(...)`.

## 4) Класс `ContactFormValidator` (логика домена)

### Назначение

Это «обёртка» вокруг `Validator`, заточенная именно под контактную форму.

Он хранит:

- список категорий (`CATEGORIES`);
- правила для полей (`$rules`);
- человекочитаемые подписи (`$labels`);
- кастомные тексты ошибок (`$customMessages`).

### Почему это полезно

Если завтра поменяются требования формы, мы изменим только этот класс, не трогая универсальный `Validator`.

### Ключевые методы

- `__construct(array $rawData)` - принимает сырые данные и сразу их очищает.
- `sanitize(array $raw): array` - базовая защита/нормализация:
  - `trim()` убирает лишние пробелы;
  - `htmlspecialchars()` защищает от XSS при повторном выводе;
  - email приводится к нижнему регистру.
- `validate()`, `errors()`, `fails()`, `firstError()` - проксируют методы ядра.
- `validated(): array` - отдаёт только допустимые поля **только после успешной валидации**.
- `getData()` / `getValue()` - доступ к очищенным данным (для заполнения формы после ошибки).

### Конструктор и сборка `Validator`

```php
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
```

Это важный момент архитектуры: доменный класс хранит **правила конкретной формы** и передаёт их универсальному ядру.

### Санитаризация входных данных

```php
private function sanitize(array $raw): array
{
   return [
      'name'     => htmlspecialchars(trim($raw['name']     ?? ''), ENT_QUOTES, 'UTF-8'),
      'email'    => strtolower(trim($raw['email']          ?? '')),
      'message'  => htmlspecialchars(trim($raw['message']  ?? ''), ENT_QUOTES, 'UTF-8'),
      'category' => trim($raw['category']                  ?? ''),
   ];
}
```

Практический смысл:

- `trim` убирает случайные пробелы.
- `htmlspecialchars` защищает текст при повторном выводе в HTML.
- email нормализуется в нижний регистр.

### Безопасное получение валидных данных

```php
public function validated(): array
{
   if ($this->validator->fails()) {
      throw new \RuntimeException('Нельзя получить validated() — форма не прошла валидацию.');
   }

   return array_intersect_key($this->data, $this->rules);
}
```

Метод не даст случайно сохранить невалидные данные: сначала проверка, потом доступ к результату.

## 5) Класс `FormBuilder` (генерация HTML формы)

### Назначение

`FormBuilder` получает конфигурацию полей и строит HTML формы автоматически.

### Что умеет

- Устанавливать `action` и `method`.
- Рисовать поля:
  - `input` (text/email/и др.);
  - `textarea`;
  - `select`;
  - `checkbox`;
  - `radio`.
- Подставлять старые значения (`withOldValues`) после неуспешной валидации.
- Показывать ошибки (`withErrors`) рядом с нужным полем.
- Экранировать значения через `htmlspecialchars` (метод `e()`), чтобы безопасно вставлять в HTML.

### Ключевые методы

- `make(array $fields)` - старт создания формы.
- `action(string $url)`, `method(string $method)` - базовые параметры `<form>`.
- `withErrors(array $errors)` - подключение ошибок.
- `withOldValues(array $values)` - старые значения для повторного показа.
- `attr(string $key, string $value)` - дополнительные атрибуты формы.
- `render(string $submitLabel)` - финальная генерация HTML.

### Fluent-интерфейс сборки формы

```php
$form = FormBuilder::make($fields)
   ->action($_SERVER['PHP_SELF'])
   ->method('POST')
   ->withErrors($errors)
   ->withOldValues($oldValues);

echo $form->render('Отправить сообщение');
```

Такой стиль удобен тем, что форма настраивается цепочкой, а не длинным конструктором с множеством аргументов.

### Рендер поля с ошибкой

```php
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
```

Здесь видно сразу 3 полезных приёма:

- выбор шаблона поля через `match`;
- автоматическая подсветка невалидного поля (`has-error`);
- экранирование текста ошибки через `$this->e(...)`.

## 6) Роль `handler.php`

`handler.php` связывает всё вместе:

1. Подключает классы.
2. Создаёт пустые `$errors` и `$oldValues`.
3. Проверяет, что запрос - `POST`.
4. Создаёт `ContactFormValidator` и запускает `validate()`.
5. При успехе:
   - получает `validated()`;
   - сохраняет в файл;
   - делает `header('Location: index.php?success=1')`.
6. При ошибке:
   - отдаёт `errors()` и `getData()` для повторного рендера формы.

Такой редирект после успешного `POST` защищает от повторной отправки при обновлении страницы.

## 7) Как студенту сделать такое же в своём проекте

1. Создайте универсальный класс валидации (ядро правил).
2. Создайте отдельный класс под конкретную форму:
   - правила;
   - подписи;
   - кастомные сообщения;
   - очистка данных.
3. Сделайте обработчик запроса (`handler.php`):
   - валидация;
   - ветка успеха;
   - ветка ошибок.
4. Отрисуйте форму через `FormBuilder` (или аналог):
   - конфиг полей;
   - подстановка старых значений;
   - вывод ошибок.
5. Применяйте паттерн PRG (`POST -> Redirect -> GET`) при успешной отправке.

## 8) Что можно улучшить дальше (практика)

- Перенести сохранение из файла в БД.
- _Выделить интерфейс для валидаторов_, чтобы легче расширять систему.

Этот пример хорошо демонстрирует разделение ответственности:

- `Validator` - **как проверять**;
- `ContactFormValidator` - **что проверять в конкретной форме**;
- `FormBuilder` - **как выводить форму**;
- `handler.php` - **как управлять сценарием отправки**.

Именно такое разделение делает код понятным для команды и удобным для развития.
