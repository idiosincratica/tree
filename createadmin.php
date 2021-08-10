<?php
//создать администратора

const SITE = '';
require_once 'resource/settings.php';
require_once 'resource/db-connection.php';

require_once 'components/user/UserModel.php';

fwrite(STDOUT, "Creating a new admin user.\nType a username:\n");
$username = trim(fgets(STDIN));
while(empty($username)){
    fwrite(STDOUT, "It's empty. Retype the username please:\n");
    $username = trim(fgets(STDIN));
}
fwrite(STDOUT, "Type a password:\n");
$password = trim(fgets(STDIN));
while(empty($password)){
    fwrite(STDOUT, "It's empty. Retype the password please:\n");
    $password = trim(fgets(STDIN));
}
UserModel::createUser($username, $password);
UserModel::adminify($username);
fwrite(STDOUT, "You have supposedly created an admin user. Congratulations!\n");