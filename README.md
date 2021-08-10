# Data Tree
Simple MySQL + PHP + JS adjacency list tree
### Инструкция:
* Импортировать схему БД из файла db.sql
* В файле resource/db-config.php заполнить необходимые для доступа к БД данные
* В файле resource/settings.php записать правильный путь к файлу конфига БД (DB_CONFIG_PATH)
* Для создания учетки админа использовать консольную утилиту createadmin.php (php -f createadmin.php)
* Заходить по адресу без index.php, так как настроена .htaccess
* Работал на php 8.0.2