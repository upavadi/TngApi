<?php

function upavadi_autoloader($class) {
    $parts = explode("_", $class);
    if ("Upavadi" !== $parts[0]) {
        return;
    }
    $dir = dirname(__FILE__);
    $file = implode('/', $parts) . '.php';
    include $dir . '/' . $file;
}

/* Plugin Name: tng-api 
 */
if (function_exists('__autoload')) {
    spl_autoload_register('__autoload');
}
spl_autoload_register("upavadi_autoloader");
