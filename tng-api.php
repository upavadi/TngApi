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

$content = Upavadi_TngContent::instance();
$content->addShortcode(new Upavadi_Shortcode_FamilySearch);

$familySearch = new Upavadi_Widget_FamilySearch;

add_action('init', array($content, 'initPlugin'), 1);
add_action('widgets_init', array($this, 'init'));
