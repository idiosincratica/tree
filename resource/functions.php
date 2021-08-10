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
		exit;
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

function isCLI(){
    return (php_sapi_name() === 'cli');
}

function prepare_in(&$arr,$str){
	/*returns a string for use PDO::prepare() formatted like this :[somethig]_[counter] and
	a prepared data array. Takes data array and arbitrary string to be used as pattern in
	"variables" names
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

function ex($a,$string='',$b='auto'){  //посмотреть что лежит в переменной
	echo '<pre style="border:1px solid #fd5; background:rgba(255,200,50,.2); padding:7px; overflow-x:auto;">';
	if($string)
		echo $string.' = ';
	if($b=='print_r'){
		print_r($a);
		}
	elseif($b=='echo'){
		echo $a;
	}
	elseif($b=='var_dump'){
		var_dump($a);
	}
	elseif($b=='auto'){
		if(gettype($a)=='array'){
			print_r($a);
		}
		elseif((gettype($a)=='null') or (gettype($a)=='boolean')){
			var_dump($a);
		}
		elseif((gettype($a)=='string') or (gettype($a)=='integer')){
			echo $a;
		}
		else{
			var_dump($a);
		}
	}
	elseif($b=='my_flag'){
		echo '--MY FLAG--';
	}
	echo "</pre>";
}