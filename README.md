# Tree
Simple MySQL + PHP + JS adjacency list tree
# Инструкция:
* импортировать схему БД из файла db.sql
* в файле resource/db-config.php заполнить необходимые для доступа к БД данные
* в файле resource/settings.php записать правильный путь к файлу конфига БД (DB_CONFIG_PATH)
* для создания учетки админа использовать консольную утилиту createadmin.php (php -f createadmin.php)
* заходить по адресу без index.php, так как настроена .htaccess
* работал на php 8.0.2