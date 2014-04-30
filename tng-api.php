<?php
/*
 * Plugin Name: tng api
 * Description: This plugin allows access to the TNG database. It also adds functionality ....
 *
 * Plugin URI: https://github.com/bahulneel/upavadi-tng-api/
 * Version: 2.0
 *         
 * Author: Neel Upadhyaya (& Mahesh Upadhyaya??)
 * Author URI: http://www.upavadi.net/
 * License: 
 * 
 *
 */
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
$content->addShortcode(new Upavadi_Shortcode_FamilyUser);
$content->addShortcode(new Upavadi_Shortcode_FamilyForm);
$content->addShortcode(new Upavadi_Shortcode_AddFamilyForm);
$content->addShortcode(new Upavadi_Shortcode_Birthdays());
$content->addShortcode(new Upavadi_Shortcode_BirthdaysPlusOne());
$content->addShortcode(new Upavadi_Shortcode_Danniversaries());
$content->addShortcode(new Upavadi_Shortcode_Manniversaries());

$familySearch = new Upavadi_Widget_FamilySearch;

add_action('init', array($content, 'initPlugin'), 1);
add_action('widgets_init', array($familySearch, 'init'));
add_action( 'admin_menu', array($content, 'adminMenu') );
add_action( 'admin_init', array($content, 'initAdmin') );
