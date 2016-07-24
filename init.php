<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 22.07.16
 * Time: 9:14
 */

function loadLibClass($class, $file_ext = '.php'){
    if (file_exists('lib/' . $class. $file_ext)){
        require_once('lib/' . $class . $file_ext);
    } else if (file_exists( $class . $file_ext )) {
        require_once($class . $file_ext);
    }
}
spl_autoload_register('loadLibClass');

