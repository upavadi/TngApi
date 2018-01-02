<?php
/*
 * Plugin Name: TngApi-V3 with Bootstrap
 * Description: This is a stand-alone plugin which allows access to the TNG database. For access to TNG pages, within Wordpress, tng-wordpress-plugin must be installed and activated 
 *
 * Plugin URI: https://github.com/upavadi/TngApi
 * Version: 3.2.3
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
//wp_enqueue_style('upavadi-styles', plugins_url( 'css/bootstrap.css',__FILE__) );
//wp_enqueue_style('upavadi-styles', plugins_url( 'css/upavadi.css',__FILE__) );
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

function create_tng_tables()
{	
    global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$add_people = $wpdb->prefix . "tng_people";
	$add_families = $wpdb->prefix . "tng_families";
	$add_children = $wpdb->prefix . "tng_children";
	$add_notes = $wpdb->prefix . "tng_notes";
	$add_events  = $wpdb->prefix . "tng_events";
	 
	// this 'if statement' makes sure that the families_table doe not exist already
	$sql_families = "CREATE TABLE " . $add_families . " ( 
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		headpersonid varchar(22) NOT NULL,
		tnguser varchar(22) NOT NULL,
		gedcom varchar(22) NOT NULL,
		familyID varchar(22) NOT NULL,
		husband varchar(22),
		wife varchar(22),
		marrdate varchar(50),
		marrdatetr date DEFAULT '0000-00-00' NOT NULL,
		marrplace varchar(255),
		husborder tinyint(4) NOT NULL,
		wifeorder tinyint(4) NOT NULL,
		living tinyint(4),
		datemodified datetime,
		PRIMARY KEY  id (id));";
	//require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	dbDelta($sql_families);
	
	$sql_children = "CREATE TABLE " . $add_children . " ( 
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		headpersonid varchar(22) NOT NULL,
		tnguser varchar(22) NOT NULL,
		gedcom varchar(22) NOT NULL,
		familyID varchar(22) NOT NULL,
		personID varchar(22) NOT NULL,
		haskids varchar(22),
		ordernum smallint(6),
		parentorder tinyint(4),
		datemodified datetime,
		PRIMARY KEY  id (id));";		
	dbDelta($sql_children);
	
	//this 'if statement' makes sure that the people_table doe not exist already
	$sql_people = "CREATE TABLE " . $add_people . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		headpersonid VARCHAR(22) NOT NULL,
		tnguser varchar(22) NOT NULL,
		gedcom varchar(22) NOT NULL,
		personID varchar(22) NOT NULL,
		lastname varchar(122),
		firstname varchar(122),
		personevent varchar(122),
		birthdate varchar(52),
		birthdatetr date DEFAULT '0000-00-00' NOT NULL,
		birthplace varchar(122),
		deathdate varchar(52),
		deathdatetr date DEFAULT '0000-00-00' NOT NULL,
		deathplace varchar(122),
		sex varchar(22),
		famc varchar(22),
		living varchar(22),
		datemodified datetime,
		PRIMARY KEY  id (id));";		
	dbDelta($sql_people);
	
	$sql_notes = "CREATE TABLE " . $add_notes . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		headpersonid VARCHAR(22),
		tnguser varchar(22) NOT NULL,
		gedcom varchar(22) NOT NULL,
		xnoteID varchar(22) NOT NULL,
		note text,
		eventID varchar(22),
		ordernum varchar(22),
		secret varchar(22),
		noteID varchar(22),
		notelinkID varchar(22),
		persfamID varchar(22),
		datemodified datetime,
		PRIMARY KEY  id (id));";		
	dbDelta($sql_notes);
	
	$sql_events = "CREATE TABLE " . $add_events . " ( 
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		headpersonid varchar(22),
		tnguser varchar(22) NOT NULL,
		persfamID varchar(22) NOT NULL,
		gedcom varchar(22) NOT NULL,
		eventID varchar(22) NOT NULL,
		eventtypeID varchar(22),
		eventdate varchar(22),
		eventdatetr date DEFAULT '0000-00-00' NOT NULL,
		cause varchar(90),
		parenttag varchar(22),
		info varchar(22),
		datemodified datetime,
		PRIMARY KEY  id (id));";
	dbDelta($sql_events);
	
	echo $wpdb->show_errors();
	
}
function droptables() {
		global $wpdb;
		$tngDeactivate = esc_attr(get_option('tng-api-drop-table'));
		if ($tngDeactivate == '1') { 
		$droppeople = $wpdb->prefix . "tng_people";
		$dropfamilies = $wpdb->prefix . "tng_families";
		$dropchildren = $wpdb->prefix . "tng_children";
		$add_notes = $wpdb->prefix . "tng_notes";
		$dropevents = $wpdb->prefix . "tng_events";
		$wpdb->query("DROP TABLE IF EXISTS $droppeople");
		$wpdb->query("DROP TABLE IF EXISTS $dropfamilies");
		$wpdb->query("DROP TABLE IF EXISTS $dropchildren");
		$wpdb->query("DROP TABLE IF EXISTS $add_notes");
		$wpdb->query("DROP TABLE IF EXISTS $dropevents");
		}

}
add_action( 'wp_enqueue_scripts', 'add_upavadi_stylesheets' );
function add_upavadi_stylesheets() {
		wp_register_style( 'register-tngapi_bootstrap', plugins_url('css/bootstrap.css', __FILE__) );
		wp_enqueue_style( 'register-tngapi_bootstrap' );
		wp_register_style( 'register-tngapi_upavadi', plugins_url('css/upavadi.css', __FILE__) );
		wp_enqueue_style( 'register-tngapi_upavadi' );
} 

// this hook will cause our 'create_tng_tables()' function to run when the plugin is activated
register_activation_hook( __FILE__, 'create_tng_tables' );
register_deactivation_hook( __FILE__, 'droptables' );
/***********************************************/
$dir = dirname(__FILE__);
$customDir = $dir . "/../tng-api-custom";
if (is_dir($customDir)) {
    $customContent = new TngApiCustom_TngCustom($content);
}
?>
