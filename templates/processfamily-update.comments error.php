<?php
require_once '../../../../wp-load.php';
//header('Location: /thank-you');
//Person identifiers
$tnguser = $_POST['User'];
$headpersonID = $_POST['personId'];
$personID = $_POST['personId'];
$firstname = $_POST['firstname'];
$lastname = $_POST['surname'];
$birthdate = $_POST['B_day'];
$birthdatetr = $birthdate;
$birthplace = $_POST['B_Place'];
$deathdate = $_POST['D_day'];
$deathdatetr = $deathdate; // do we need this?
$deathplace = $_POST['D_Place'];
$sex = $_POST['personsex'];
$famc = $_POST['personfamc'];
$living = $_POST['personliving'];
$personevent = $_POST['personevent'];
$datemodified = date('Y-m-d H:i:s');

//Define table variables for Insert
global $wpdb;
$people_table = $wpdb->prefix . "tng_people";
$families_table = $wpdb->prefix . "tng_families";

//$result = $wpdb->get_var("SELECT personID FROM wp_tng_people where tnguser = '$tnguser' AND headpersonID = '$headpersonID'");

 
// Insert Head Person in PEOPLE
	$wpdb->insert(
		$people_table,
		array(
			headpersonID =>$headpersonID,
			personID =>$personID,
			tnguser =>$tnguser,
			firstname =>$firstname,
			lastname =>$lastname,
			personevent =>$personevent,
			birthdate =>$birthdate,
			birthdatetr =>$birthdatetr,
			birthplace =>$birthplace,
			deathdate =>$deathdate,
			deathdatetr =>$deathdatetr,
			deathplace =>$deathplace,
			sex =>$sex,
			famc =>$famc,
			living =>$living,
			datemodified => $datemodified,
			)

		);

//Father identifiers
$personID = $_POST['fatherpersonId'];
$firstname = $_POST['fathername'];
$lastname = $_POST['fathersurname'];
$birthdate = $_POST['fatherB_day'];
$birthdatetr = $birthdate;
$birthplace = $_POST['fatherB_Place'];
$deathdate = $_POST['fatherD_day'];
$deathdatetr = $deathdate;
$deathplace = $_POST['fatherD_Place'];
$sex = $_POST['fathersex'];
$famc = $_POST['fatherfamc'];
$living = $_POST['fatherliving'];
$personevent = $_POST['fatherevent'];

// Insert Father in PEOPLE
	$wpdb->insert(
		$people_table,
		array(
			headpersonID =>$headpersonID,
			personID =>$personID,
			tnguser =>$tnguser,
			firstname =>$firstname,
			lastname =>$lastname,
			personevent =>$personevent,
			birthdate =>$birthdate,
			birthdatetr =>$birthdatetr,
			birthplace =>$birthplace,
			deathdate =>$deathdate,
			deathdatetr =>$deathdatetr,
			deathplace =>$deathplace,
			sex =>$sex,
			famc =>$famc,
			living =>$living,
			datemodified => $datemodified,
			)

		);

//Mother identifiers
$personID = $_POST['motherpersonId'];
$firstname = $_POST['mothername'];
$lastname = $_POST['mothersurname'];
$birthdate = $_POST['motherB_day'];
$birthdatetr = $birthdate;
$birthplace = $_POST['motherB_Place'];
$deathdate = $_POST['motherD_day'];
$deathdatetr = $deathdate;
$deathplace = $_POST['motherD_Place'];
$sex = $_POST['mothersex'];
$famc = $_POST['motherfamc'];
$living = $_POST['motherliving'];
$personevent = $_POST['motherevent'];
echo "first name=". $firstname;
// Insert Mother in PEOPLE
	$wpdb->insert(
		$people_table,
		array(
			headpersonID =>$headpersonID,
			personID =>$personID,
			tnguser =>$tnguser,
			firstname =>$firstname,
			lastname =>$lastname,
			personevent =>$personevent,
			birthdate =>$birthdate,
			birthdatetr =>$birthdatetr,
			birthplace =>$birthplace,
			deathdate =>$deathdate,
			deathdatetr =>$deathdatetr,
			deathplace =>$deathplace,
			sex =>$sex,
			famc =>$famc,
			living =>$living,
			datemodified => $datemodified,
			)

		);
//parents identifiers
$familyID = $_POST['personfamc'];
$husband = $_POST['fatherpersonId'];
$wife = $_POST['motherpersonId'];
$marrdate = $_POST['parentmarr_day'];
$marrplace = $_POST['parentmarr_place'];	
$husborder = $_POST['parents_husborder'];
$wifeorder = $_POST['parents_wifeorder'];
$living = $_POST['parents_living'];// not sure why this is required

// Insert Parents in FAMILIES
	$wpdb->insert(
		$families_table,
		array(
			headpersonID =>$headpersonID,
			tnguser =>$tnguser,
			familyID =>$familyID,
			husband =>$husband,
			wife =>$wife,
			marrdate =>$marrdate,
			marrdatetr =>$marrdatetr,
			marrplace =>$marrplace,
			husborder =>$husborder,
			wifeorder =>$wifeorder,
			living =>$living,
			datemodified => $datemodified,
			)

		);
/**		
//Spouse identifiers
//NOT RETURNING ARRAY

//Children identifiers
$child = $_POST['childID'];
foreach ($child as $value):
$personID = $_POST['childID']['childorder'];
$firstname = $_POST['childfirstname'];
$lastname = $_POST['childsurname'];
$birthdate = $_POST['childdateborn'];
$birthdatetr = $birthdate;
$birthplace = $_POST['childplaceborn'];
$deathdate = $_POST['childdatedied'];
$deathdatetr = $deathdate;
$deathplace = $_POST['childplacedied'];
$sex = $_POST['childsex'];
$famc = $_POST['motherfamilyID'];
$living = $_POST['childliving'];
$personevent = $_POST['motherevent'];
$childorder = $POST['childorder'];
print_r ($value);
echo "first name=". $personID;


/**
// Insert children in PEOPLE
	$wpdb->insert(
		$people_table,
		array(
			headpersonID =>$headpersonID,
			personID =>$personID,
			tnguser =>$tnguser,
			firstname =>$firstname,
			lastname =>$lastname,
			personevent =>$personevent,
			birthdate =>$birthdate,
			birthdatetr =>$birthdatetr,
			birthplace =>$birthplace,
			deathdate =>$deathdate,
			deathdatetr =>$deathdatetr,
			deathplace =>$deathplace,
			sex =>$sex,
			famc =>$famc,
			living =>$living,
			datemodified => $datemodified,
			)

		);

		endforeach;

//var_dump($_POST);


**/

//this the original

$date = date('c');
$email = esc_attr(get_option('tng-api-email'));
$msg = <<<MSG
Person Details Updated({$date}):

MSG;
$msg .= print_r($_REQUEST, true);
//echo "<pre>{$msg}</pre>";
//mail($email, 'New data', $msg);

?>
<html>
<head>
</head>
<body>
</body>
</html>