<?php
/*
 * Plugin Name: 1-tng api 1.3
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
//$content->addShortcode(new Upavadi_Shortcode_TngProxy());
$content->addShortcode(new Upavadi_Shortcode_SubmitImage());
$content->addShortcode(new Upavadi_Shortcode_AdminFamilySheet());
$content->addShortcode(new Upavadi_Shortcode_UserFamilySheet());
$familySearch = new Upavadi_Widget_FamilySearch;
$update = new Upavadi_Update_Admin($wpdb, $content);

add_action('init', array($content, 'initPlugin'), 1);
add_action('widgets_init', array($familySearch, 'init'));
add_action( 'admin_menu', array($content, 'adminMenu') );
add_action( 'admin_init', array($content, 'initAdmin') );
add_action( 'admin_menu', array($update, 'initAdmin') );
//add_filter('the_posts', array($content, 'proxyFilter'));
/**********************************************/
function create_tng_tables()
{	
    global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$add_people = $wpdb->prefix . "tng_people";
	$add_families = $wpdb->prefix . "tng_families";
	$add_children = $wpdb->prefix . "tng_children";
	$add_notes = $wpdb->prefix . "tng_notes";
	
	// this 'if statement' makes sure that the families_table doe not exist already
	$sql_families = 'CREATE TABLE '. $add_families.'(
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		headpersonid varchar(22) NOT NULL,
		tnguser varchar(22) NOT NULL,
		familyid varchar(22) NOT NULL,
		husband varchar(22) NOT NULL,
		wife varchar(22) NOT NULL,
		marrdate varchar(50) NOT NULL,
		marrdatetr varchar(50),
		marrplace varchar(255) NOT NULL,
		husborder tinyint(4),
		wifeorder tinyint(4),
		living tinyint(4),
		datemodified datetime,
		PRIMARY KEY  id (id))';
	dbDelta($sql_families);
	
	$sql_children = 'CREATE TABLE '. $add_children.'(
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		headpersonid varchar(22),
		tnguser varchar(22),
		familyID varchar(22),
		personID varchar(22),
		haskids varchar(22),
		ordernum smallint(6),
		parentorder tinyint(4),
		datemodified datetime,
		PRIMARY KEY  (id))';
		
	dbDelta($sql_children);
	
	//this 'if statement' makes sure that the people_table doe not exist already
	$sql_people = 'CREATE TABLE ' . $add_people.'(
		id INT(11) UNSIGNED AUTO_INCREMENT,
		headpersonid VARCHAR(22),
		tnguser varchar(22) NOT NULL,
		personid varchar(22) NOT NULL,
		lastname varchar(122) NOT NULL,
		firstname varchar(122) NOT NULL,
		personevent varchar(122) NOT NULL,
		birthdate varchar(52) NOT NULL,
		birthdatetr date,
		birthplace varchar(122) NOT NULL,
		deathdate varchar(52) NOT NULL,
		deathdatetr date,
		deathplace varchar(122) NOT NULL,
		sex varchar(22) NOT NULL,
		famc varchar(22) NOT NULL,
		living varchar(22) NOT NULL,
		cause varchar(90) NOT NULL,
		datemodified datetime,
		PRIMARY KEY  (id))';
		
	dbDelta($sql_people);
	
	$sql_notes = 'CREATE TABLE '. $add_notes.'(
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		headpersonid varchar(22),
		tnguser varchar(22),
		persfamID varchar(22),
		noteID varchar(22),
		note text,
		notenameID varchar(22),
		notename text,
		notebirtID varchar(22),
		notebirt text,
		notedeatID varchar(22),
		notedeat text,
		noteburiID varchar(22),
		noteburi text,
		datemodified datetime,
		PRIMARY KEY  (id))';
		
	dbDelta($sql_notes);
	
}

// this hook will cause our 'create_tng_tables()' function to run when the plugin is activated
register_activation_hook( __FILE__, 'create_tng_tables' );
/***********************************************/
$dir = dirname(__FILE__);
$customDir = $dir . "/../tng-api-custom";
if (is_dir($customDir)) {
    $customContent = new TngApiCustom_TngCustom($content);
}

	
