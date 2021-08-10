<?php defined('SITE') or die; ?>
<?php
abstract class Controller{
    static function render($path, $args=false){  //calls view using local path (inside a component)
        $refl = new ReflectionClass(get_called_class());
        $file = $refl->getFileName();
        $path = dirname(realpath($file)).'/'.$path;

        return self::renderGlobal($path, $args);
    }

    static function renderGlobal($path, $args=false){  //calls view using global path
        ob_start();
        call_user_func(function() use ($path, $args){
            if(is_array($args)){
                extract($args);
            }
            require $path;
        });
        return ob_get_clean();
    }
}