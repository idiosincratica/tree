<?php
const SITE = '';
require_once 'resource/settings.php';
require_once 'resource/db-connection.php';
require_once 'resource/Doc.php';
require_once 'resource/functions.php';
require_once 'resource/controller.php';

//significant accessed url path without a prepending real directory path to the index.php on server if it exists
define('VIRTUAL_PATH', substr((string)parse_url(rawurldecode($_SERVER['REQUEST_URI']),PHP_URL_PATH),strlen(BASE_PATH)));

Doc::set_title('Структура данных');
Doc::set_description('Структура данных и именами и описаниями');
Doc::set_keywords('структура данных');

session_start();

call_user_func(function(){
    try{
        require_once 'routes.php';
        routes();
    }
    catch(Error404 $e){
        header('HTTP/1.1 404 Not Found');
        if(!string_starts_with(VIRTUAL_PATH, '/api/')){
            echo render_template('<div class="page-error">Ошибка 404<br>Страница не найдена</div>');
        }
    }
    catch(Error400 $e){
        header('HTTP/1.1 400 Bad Request');
        if(!string_starts_with(VIRTUAL_PATH, '/api/')){
            echo render_template('<div class="page-error">Ошибка 400<br>Плохой запрос</div>');
        }
    }
    catch(Error500 $e){
        header('HTTP/1.1 500 Internal Server Error');
        if(!string_starts_with(VIRTUAL_PATH, '/api/')){
            echo render_template('<div class="page-error">Ошибка 500<br>Что-то пошло не так</div>');
        }
    }
    catch(Exception $e){
        if(ini_get('display_errors') == 1){
            throw $e;
        }
        header('HTTP/1.1 500 Internal Server Error');
        if(!string_starts_with(VIRTUAL_PATH, '/api/')){
            echo render_template('<div class="page-error">Ошибка 500<br>Что-то пошло не так</div>');
        }
    }
});

db::disconnect();
