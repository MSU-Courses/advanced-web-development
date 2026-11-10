# Код к главе "Организация кода: файлы, пространства имён и автозагрузка"

Каждая папка с номером - отдельный небольшой проект. Внутри лежат папка `public/` с файлом, который нужно запустить, и папки `src/`, `config/`, `templates/` или `lib/`, из которых он подключает код. Примеры рассчитаны на PHP 8.1 и новее, проверены на PHP 8.4. Composer и база данных не нужны.

## Как запустить

Консольный пример запускается из папки проекта:

```bash
cd 05_require_order_fixed
php public/delivery.php
```

Примеры с HTML-страницами (`01_plain_split`, `11_minishop`) удобнее открыть в браузере. Флаг `-t public` делает папку `public/` корневой папкой сайта:

```bash
cd 11_minishop
php -S localhost:8000 -t public
```

Затем откройте `http://localhost:8000/checkout.php`. Чтобы остановить сервер, нажмите `Ctrl+C` в терминале.

Пример `02_path_bug` нужно запустить двумя способами: из папки `public/` (`cd public` и `php price.php`) и из папки проекта (`php public/price.php`). Во втором случае появится ошибка.

## Список примеров

| Папка | Раздел главы | Что показывает |
| --- | --- | --- |
| [01_plain_split](01_plain_split/) | Разделение обычного кода | Функции, настройки, данные и части страницы в отдельных файлах; две страницы используют общие `header.php` и `footer.php` |
| [02_path_bug](02_path_bug/) | Пути к подключаемым файлам | ОШИБКА НАМЕРЕННО: относительный путь `'../src/functions.php'` зависит от папки, из которой запущен скрипт |
| [03_path_fixed](03_path_fixed/) | Пути к подключаемым файлам | Исправление: путь строится от `__DIR__` |
| [04_require_order_bug](04_require_order_bug/) | Порядок подключения классов | ОШИБКА НАМЕРЕННО: дочерний класс подключён раньше родительского, `Class "RegularDelivery" not found` |
| [05_require_order_fixed](05_require_order_fixed/) | Порядок подключения классов | Исправление: интерфейс, родитель, затем наследник |
| [06_autoload_flat](06_autoload_flat/) | Автозагрузка классов | `trace.php` показывает, в каком порядке PHP вызывает автозагрузчик; `checkout.php` считает заказ без единого `require` классов |
| [07_name_conflict_bug](07_name_conflict_bug/) | Пространства имён | ОШИБКА НАМЕРЕННО: два класса `Validator`, `Cannot redeclare class Validator` |
| [08_name_conflict_fixed](08_name_conflict_fixed/) | Пространства имён | Исправление: `MiniShop\Form\Validator` и `SimpleMail\Validator`, `use` и псевдоним `as` |
| [09_global_class_bug](09_global_class_bug/) | Глобальное пространство имён | ОШИБКА НАМЕРЕННО: `InvalidArgumentException` внутри `namespace MiniShop\Cart` ищется как `MiniShop\Cart\InvalidArgumentException` |
| [10_global_class_fixed](10_global_class_fixed/) | Глобальное пространство имён | Исправление: `\InvalidArgumentException` |
| [11_minishop](11_minishop/) | MiniShop: страница заказа из нескольких файлов | Итоговый проект: классы в пространствах имён `MiniShop\...`, автозагрузчик PSR-4 в `autoload.php`, страница `public/checkout.php` |

Все данные учебные: заказ нигде не сохраняется, настоящая оплата не выполняется.
