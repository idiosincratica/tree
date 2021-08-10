<?php defined('SITE') or die; ?>
<?php
class Doc {
	private static $messages = array();
	private static $errors = array();
	private static $title, $keywords, $description;
	
	public static function add_message($text){
		self::$messages[] = $text;
	}
	
	public static function add_error($text){
		self::$errors[] = $text;
	}
	
	public static function set_title($text){
		self::$title = $text;
	}
	
	public static function set_keywords($text){
		self::$keywords = $text;
	}
	
	public static function set_description($text){
		self::$description = $text;
	}
	
	public static function print_messages(){
		if(!empty(self::$messages)){
			$messages = self::process_messages(self::$messages);
			echo "\n<div class='alerts messages'>\n$messages\n</div>\n";
		}
		
		if(!empty(self::$errors)){
			$errors = self::process_messages(self::$errors);
			echo "\n<div class='alerts errors'>\n$errors\n</div>\n";
		}
	}
	
	private static function process_messages(&$messages){
		if(count($messages)===1){
			return $messages[0];
		}
		return "<ul class='alerts__list'>\n".
		implode(array_map(function($message){
			return "<li>$message</li>\n";
		}, $messages)).
		"</ul>\n";
	}
	
	public static function print_title(){
		echo self::$title;
	}
	
	public static function print_keywords(){
		echo self::$keywords;
	}
	
	public static function print_description(){
		echo self::$description;
	}
}
?>