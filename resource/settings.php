<?php defined('SITE') or die; ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

mb_internal_encoding('UTF-8');
const DS = DIRECTORY_SEPARATOR;
const DOMAIN = 'localhost';
define('DB_CONFIG_PATH','resource/my-db-config.php');
date_default_timezone_set('Europe/Moscow');
define('BASE_PATH',preg_replace('@/[^/]+$@','',$_SERVER['SCRIPT_NAME']));
define('BASE','http://'.DOMAIN.BASE_PATH);
define('TEMPLATE_PATH','template/template.php');