<?php

function upadvadi_autoloader($class) {
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
spl_autoload_register("upadvadi_autoloader");


$instance = Upavadi_TngContent::instance();

add_action('init', array($instance, 'initPlugin'), 1);
