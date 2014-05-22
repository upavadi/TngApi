<?php
/*
 * Plugin Name: tng api
 * Description: This plugin allows access to the TNG database. It also adds functionality of creating pages to display TNG data....
 *
 * Plugin URI: https://github.com/upavadi/TngApi
 * Version: 2.0
 *         
 * Authors: Neel Upadhyaya & Mahesh Upadhyaya
 * Author URI: http://www.upavadi.net/
 * License: 
 * 
 *
 */
require_once __DIR__ . '/vendor/autoload.php';
$content = Upavadi_TngContent::instance();
new TabsShortcodes();

$content->addShortcode(new Upavadi_Shortcode_FamilySearch);
$content->addShortcode(new Upavadi_Shortcode_PersonNotes);
$content->addShortcode(new Upavadi_Shortcode_FamilyUser);
$content->addShortcode(new Upavadi_Shortcode_FamilyForm);
$content->addShortcode(new Upavadi_Shortcode_AddFamilyForm);
$content->addShortcode(new Upavadi_Shortcode_Birthdays());
$content->addShortcode(new Upavadi_Shortcode_BirthdaysPlusOne());
$content->addShortcode(new Upavadi_Shortcode_Danniversaries());
$content->addShortcode(new Upavadi_Shortcode_Danniversariesplusone());
$content->addShortcode(new Upavadi_Shortcode_Manniversaries());
$content->addShortcode(new Upavadi_Shortcode_Manniversariesplusone());
$content->addShortcode(new Upavadi_Shortcode_TngProxy());

$content->addShortcode(new Upavadi_Shortcode_TabsShortcode());
	

$familySearch = new Upavadi_Widget_FamilySearch;

add_action('init', array($content, 'initPlugin'), 1);
add_action('widgets_init', array($familySearch, 'init'));
add_action( 'admin_menu', array($content, 'adminMenu') );
add_action( 'admin_init', array($content, 'initAdmin') );
add_filter('the_posts', array($content, 'proxyFilter'));