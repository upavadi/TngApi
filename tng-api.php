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

$content = Upavadi_TngContent::instance();
$content->addShortcode(new Upavadi_Shortcode_FamilySearch);
$content->addShortcode(new Upavadi_Shortcode_PersonNotes);
$content->addShortcode(new Upavadi_Shortcode_AddFamilyForm);

$familySearch = new Upavadi_Widget_FamilySearch;

add_action('init', array($content, 'initPlugin'), 1);
add_action('widgets_init', array($familySearch, 'init'));
