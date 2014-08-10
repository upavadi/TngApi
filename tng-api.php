<?php
/*
 * Plugin Name: tng api 1.3
 * Description: This plugin allows access to the TNG database. For access to TNG pages, tng-wordpress-plugin must be installed and activated 
 *
 * Plugin URI: https://github.com/upavadi/TngApi
 * Version: 1.3
 *         
 * Author: Neel Upadhyaya & Mahesh Upadhyaya
 * Author URI: http://www.upavadi.net/
 * License: MIT Licence http://opensource.org/licenses/MIT
 *
 * URL to the plugin Directory 	
 * <?php echo plugins_url('subdirectory/file', dirname(__FILE__)); ?>
 *
 */
require_once __DIR__ . '/autoload.php';
include_once __DIR__. '/tabs.php';

$content = Upavadi_TngContent::instance();

$content->addShortcode(new Upavadi_Shortcode_FamilySearch);
$content->addShortcode(new Upavadi_Shortcode_PersonNotes);
$content->addShortcode(new Upavadi_Shortcode_FamilyUser);
$content->addShortcode(new Upavadi_Shortcode_FamilyForm);
$content->addShortcode(new Upavadi_Shortcode_AddFamilyForm);
$content->addShortcode(new Upavadi_Shortcode_Birthdays());
$content->addShortcode(new Upavadi_Shortcode_Danniversaries());
$content->addShortcode(new Upavadi_Shortcode_Manniversaries());
$content->addShortcode(new Upavadi_Shortcode_Manniversariesplusone());
//$content->addShortcode(new Upavadi_Shortcode_TngProxy());
$content->addShortcode(new Upavadi_Shortcode_SubmitImage());
$familySearch = new Upavadi_Widget_FamilySearch;

add_action('init', array($content, 'initPlugin'), 1);
add_action('widgets_init', array($familySearch, 'init'));
add_action( 'admin_menu', array($content, 'adminMenu') );
add_action( 'admin_init', array($content, 'initAdmin') );
//add_filter('the_posts', array($content, 'proxyFilter'));

$dir = dirname(__FILE__);
$customDir = $dir . "/../tng-api-custom";
if (is_dir($customDir)) {
    $customContent = new TngApiCustom_TngCustom($content);
}
