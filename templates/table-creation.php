<?php
echo "In table creation";
function create_tng_tables()
{	
    global $wpdb;
	$add_people = $wpdb->prefix . "tng_people";
	$add_families = $wpdb->prefix . "tng_families";
	$add_children = $wpdb->prefix . "tng_children";
	//$wpdb->show_errors();
	echo "Before people table";
	// this 'if statement' makes sure that the families_table doe not exist already
	if($wpdb->get_var('SHOW TABLES LIKE' .$add_families) != $add_families) 
	{
		echo "in Families table";
		$sql_families = 'CREATE TABLE '. $add_families.'(
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		headpersonid varchar(22) NOT NULL,
		tnguser varchar(22) NOT NULL,
		familyid varchar(22) NOT NULL,
		husband varchar(22) NOT NULL,
		wife varchar(22) NOT NULL,
		marrdate varchar(50) NOT NULL,
		marrdatetr date,
		marrplace text NOT NULL,
		husborder tinyint(4),
		wifeorder tinyint(4),
		living tinyint(4),
		datemodified datetime,
		PRIMARY KEY id (id))';
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql_families);
	}

	// this 'if statement' makes sure that the children_table doe not exist already
	if($wpdb->get_var('SHOW TABLES LIKE' .$add_children) != $add_children) 
	{
		echo "in children table";
		$sql_children = 'CREATE TABLE '. $add_children.'(
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		headpersonid varchar(22),
		tnguser varchar(22),
		familyID varchar(22),
		personID varchar(22),
		haskids varchar(22),
		ordernum smallint(6),
		parentorder tinyint(4),
		childextra smallint(6),
		PRIMARY KEY id (id))';
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql_children);
	}
	//this 'if statement' makes sure that the people_table doe not exist already
	//if($wpdb->get_var('SHOW TABLES LIKE' .$add_people) != $add_people) 
	//{
		$wpdb->print_errors();
		echo "in people table";
		$sql_people = 'CREATE TABLE '. $add_people.'(
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		headpersonid varchar(22) NOT NULL,
		tnguser varchar(22),
		personID varchar(22),
		lastname varchar(122),
		firstname varchar(22),
		personevent varchar(22),
		birthdate varchar(22),
		birthdatetr date,
		birthplace varchar(122),
		deathdate varchar(22),
		deathdatetr date,
		#deathplace varchar(122)
		#sex varchar(22),
		#famc varchar(22),
		#living varchar(22),
		datemodified datetime,
		PRIMARY KEY id (id))';
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql_people);
	//}
    
}

// this hook will cause our 'create_tng_tables()' function to run when the plugin is activated
//register_activation_hook( __FILE__, 'create_tng_tables' );
?>