<?php defined('SITE') or die; ?>
<?php

class Error404 extends Exception{}
class Error400 extends Exception{}
class Error500 extends Exception{}

function prep($data){
//очищение данных для повторного вывода в html-форме
	return htmlspecialchars(strip_tags(trim($data)),ENT_QUOTES,'UTF-8');
}

function restricted_access(){
	if(empty($_SESSION['is_admin'])){
		throw new Error404();
	}
}

function render_template($content=false, $path=TEMPLATE_PATH){
	ob_start();
	require $path;
	return ob_get_clean();
}

function logout(){
	session_start();
	$_SESSION = [];
	session_unset();
	session_destroy();

	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}

	$location = BASE.'/';
	header("Location: $location");
}

function prepare_in(&$arr,$str){
	/*returns a string for use in PDO::prepare() which is formatted like this :<pattern>_<counter> and
	a prepared data array. Takes data array and arbitrary string to be used as pattern in
	"variable" names
	*/

	$count = count($arr);
	$placeholders = array();
	$values = array();
	$i = 0;
	foreach($arr as $a){
		$placeholder = ':'.$str.'_'.$i;
		$placeholders[] = $placeholder;
		$values[$placeholder] = $a;
		$i++;
	}
	return array('placeholders'=>implode(',',$placeholders),'values'=>$values);
}
