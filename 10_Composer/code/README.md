# Код к главе "Composer: менеджер зависимостей PHP"

Каждая папка - отдельный небольшой проект с файлом `composer.json`. Папки `vendor/` в репозитории нет, как и положено проекту на Composer: перед первым запуском её создаёт команда `composer install`. Примеры проверены на PHP 8.4 и Composer 2.11. Для всех проектов, кроме `04_dev_dependency`, достаточно PHP 8.1.

## Как запустить

Зависимости устанавливают, а встроенный сервер запускают из папки проекта:

```bash
cd 01_first_project
composer install
php -S localhost:8000 -t public
```

Затем в браузере открывают `http://localhost:8000/`. Сервер останавливают клавишами `Ctrl+C` в терминале.

Итоговый проект `feedback` запускается скриптом Composer:

```bash
cd feedback
composer install
composer start
```

Форма открывается по адресу `http://localhost:8000/contact.php`. Консольную проверку без браузера запускает команда `php check.php`.

## Примеры по разделам главы

| Папка | Раздел главы | Что показывает |
| --- | --- | --- |
| [01_first_project](01_first_project/) | Первый проект с Composer | `composer.json` с Monolog, подключение `vendor/autoload.php`, запись в `var/log/app.log` |
| [02_version_conflict_bug](02_version_conflict_bug/) | Конфликт зависимостей | ОШИБКА НАМЕРЕННО: `psr/log ^1.1` и `monolog/monolog ^3.12` несовместимы, `composer install` выводит `Problem 1`. Lock-файла нет: Composer не может его создать |
| [03_version_conflict_fixed](03_version_conflict_fixed/) | Конфликт зависимостей | Исправление: `psr/log ^3.0` |
| [04_dev_dependency](04_dev_dependency/) | Зависимости для разработки | `symfony/var-dumper` в `require-dev`, функция `dump()` в `public/debug.php`. Для проверки нужно выполнить `composer install --no-dev` и снова запустить `php public/debug.php` |
| [05_own_autoload](05_own_autoload/) | Автозагрузка своих классов | `autoload.psr-4` для `App\` и `autoload.files` для `functions.php`, программа `php public/check.php` |
| [06_update_remove](06_update_remove/) | Обновление зависимостей, Удаление зависимостей | Monolog `3.11.0` в lock-файле при ограничении `^3.0`. Последовательность: `composer install`, `composer outdated`, `composer update monolog/monolog`, `composer remove monolog/monolog` |
| [feedback](feedback/) | Собираем решения вместе | Форма обратной связи из прошлой главы на Composer: `egulias/email-validator`, Monolog, скрипт `start` |

## Замечания

- Lock-файл проекта `04_dev_dependency` создан на PHP 8.4: ветка 8 пакета `symfony/var-dumper` требует PHP 8.4. На более старом PHP `composer install` сообщит о несовместимости. В этом случае необходимо выполнить `composer remove --dev symfony/var-dumper`, а затем `composer require --dev symfony/var-dumper`: Composer подберёт ветку пакета под установленную версию PHP и запишет новое ограничение.
- Журналы записываются в папку `var/log/` проекта. Она создаётся при первой записи и указана в `.gitignore`.
- Все данные учебные: письма не отправляются, сообщения сохраняются только в журнал на локальном компьютере.
