<?php

function upavadi_autoloader($class) {
    $parts = explode("_", $class);
    if ("Upavadi" !== $parts[0] && "TngApiCustom" !== $parts[0]) {
        return;
    }
    
    $dir = dirname(__FILE__);
    $customDir = $dir . "/../tng-api-custom";
    if ("TngApiCustom" == $parts[0]) {
        if (!is_dir($customDir)) {
            throw new RuntimeException('Custom dir must be called tng-api-custom');
        }
        $dir = $customDir;
        array_shift($parts);
    }
    $file = implode('/', $parts) . '.php';
    include $dir . '/' . $file;
}


if (function_exists('__autoload')) {
    spl_autoload_register('__autoload');
}
spl_autoload_register("upavadi_autoloader");
