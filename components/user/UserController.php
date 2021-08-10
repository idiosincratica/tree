<?php defined('SITE') or die; ?>
<?php

class UserController extends Controller{
    static function login(){
        Doc::set_title('Вход пользователей');
        Doc::set_description('Вход пользователей на сайт');
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $username = isset($_POST['username']) ? prep($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if($username and $password){
                require_once 'UserModel.php';
                if(UserModel::loginUser($username, $password)){
                    $location = $_SESSION['is_admin'] ? BASE.'/admin' : BASE.'/';
                    header('Location: '.$location);
                    exit;
                }
                else{
                    Doc::add_error('Авторизация не удалась, попробуйте еще');
                }
            }
            else{
                Doc::add_error('Вы забыли ввести поле');
            }
            return self::render('login-view.php',
            ['username' => $username, 'password' => '']);
        }
        elseif($_SERVER['REQUEST_METHOD'] == 'GET'){
            return self::render('login-view.php',
            ['username' => '', 'password' => '']);
        }
    }
}