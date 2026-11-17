# Примеры к главе "Шаблоны и шаблонизация в PHP"

Каждая папка - отдельный небольшой проект с одними и теми же двумя страницами: "Обратная связь" (`contact.php`) и "О сайте" (`about.php`). Логика формы во всех проектах одинаковая (`src/contact.php` или классы `App\...` в `feedback`), меняется только способ шаблонизации. Проект запускается встроенным сервером PHP из папки проекта:

```bash
php -S localhost:8000 -t public
```

Затем открывают `http://localhost:8000/contact.php` и `http://localhost:8000/about.php`. В папках `05_twig`, `06_smarty` и `feedback` сначала выполняют `composer install`; в `feedback` сервер запускает также `composer start`. Файлы без папки `public` (например, `03_render/scope/01_outside.php`) запускаются в терминале: `php 03_render/scope/01_outside.php`.

Примеры проверены на PHP 8.4.5, Twig 3.30.0 и Smarty 5.9.0. Twig 3 требует PHP 8.1 и новее.

| Файл или папка | Раздел главы | Что показывает |
| --- | --- | --- |
| [01_require_parts](01_require_parts/) | 1. Подключение частей страницы | `header.php`, `footer.php` и `contact_form.php` через `require` |
| [02_placeholders](02_placeholders/) | 2. Шаблоны с метками | HTML-файлы с метками `{{name}}` и функция `fillTemplate()` |
| [02_placeholders/escaped_html_bug.php](02_placeholders/escaped_html_bug.php) | 2. Шаблоны с метками, ловушка | ОШИБКА НАМЕРЕННО: готовый HTML-список экранируется и выводится текстом |
| [03_render](03_render/) | 3. Функция рендеринга | `render()` на `extract()` и буферизации вывода |
| [03_render/scope](03_render/scope/) | 3. Шаблон внутри функции | `01_outside.php`: шаблон внутри функции не видит внешнюю переменную (предупреждение намеренно); `02_parameter.php`: видит параметр |
| [03_render/buffering](03_render/buffering/) | 3. Буферизация вывода | `ob_start()` и `ob_get_clean()`, вложенные буферы, вывод шаблона в строку |
| [03_render/forgotten_buffer_bug.php](03_render/forgotten_buffer_bug.php) | 3. Функция рендеринга, ловушка | ОШИБКА НАМЕРЕННО: незакрытый буфер выводит HTML перед `<!doctype html>` |
| [04_layout](04_layout/) | 4. Layout-шаблон | `layout.php`, страницы в `pages/`, частичный шаблон `partials/field.php`, функция `renderPage()` |
| [04_layout/double_escape_bug.php](04_layout/double_escape_bug.php) | 4. Layout-шаблон, ловушка | ОШИБКА НАМЕРЕННО: готовое содержимое страницы экранируется второй раз |
| [05_twig](05_twig/) | 5. Twig | наследование шаблонов, `include`, автоэкранирование |
| [05_twig/raw_bug.php](05_twig/raw_bug.php) | 5. Twig, ловушка | ОШИБКА НАМЕРЕННО: фильтр `raw` выводит данные пользователя без экранирования |
| [06_smarty](06_smarty/) | 6. Smarty | те же страницы на Smarty 5 с `setEscapeHtml(true)` |
| [06_smarty/escape_off_bug.php](06_smarty/escape_off_bug.php) | 6. Smarty, ловушка | ОШИБКА НАМЕРЕННО: без `setEscapeHtml(true)` Smarty не экранирует вывод |
| [feedback](feedback/) | Собираем решения вместе | форма обратной связи из прошлой главы на Twig |
