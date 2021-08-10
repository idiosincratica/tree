<?php defined('SITE') or die; ?>
<?php

class UserModel{
    static function createUser($username, $password){
        $dbh = db::get();

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = 'insert into users set username=:username, password=:password';
        $sth = $dbh->prepare($query);
        $sth->execute([
            ':username' => $username,
            ':password' => $hashed_password
        ]);
    }

    static function adminify($username){
        //grant user administrative rights
        $dbh = db::get();

        $query = 'update users set is_admin=true where username=:username';
        $sth = $dbh->prepare($query);
        $sth->execute([':username' => $username]);
    }

    static function loginUser($username, $password){
        $dbh = db::get();

        $query = 'select id, password, is_admin from users where username=:username';
        $sth = $dbh->prepare($query);
        $sth->execute([':username' => $username]);
        $data = $sth->fetch();
        if($data and password_verify($password, $data['password'])){
            session_start();
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = $data['is_admin'];
            return true;
        }
        else{
            return false;
        }
    }
}

