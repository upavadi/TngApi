<?php
require_once '../../../../wp-load.php';
//header('Location: /thank-you');
//this the original

global $wpdb;
$people_table = $wpdb->prefix . "tng_people";
$notes_table = $wpdb->prefix . "tng_notes";

//Person identifiers
$tnguser = $_POST['User'];
$headpersonid = $_POST['personId'];
$personid = $_POST['personId'];
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
$cause = $_POST['cause_of_death'];
$datemodified = date('Y-m-d H:i:s');

// Insert Head Person in PEOPLE
$wpdb->insert(
		$people_table,
		array(
			headpersonid =>$headpersonid,
			personid =>$personid,
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
			cause =>$cause,
			datemodified => $datemodified,
			)
		
		);

//Notes identifiers
$headpersonid = $_POST['personId'];
$tnguser = $_POST[User];
$personid = $_POST['personId'];
$xnote_generalID = '';
$note_general = $_POST['note_general'];
$xnote_nameID = 'NAME';
$note_name = $_POST['note_name'];
$xnote_birthID = 'BIRT';
$note_birth = $_POST['note_birth'];
$xnote_deathID = 'DEAT';
$note_death = $_POST['note_death'];
$xnote_funeralID = 'BURI';
$note_funeral = $_POST['note_funeral'];

//Insert Notes
	$wpdb->insert(
		$notes_table,
		array(
			headpersonid =>$headpersonid,
			tnguser =>$tnguser,
			persfamID =>$personid,
			noteID =>$xnote_generalID,
			note =>$note_general,
			notenameID =>$xnote_nameID,
			notename =>$note_name,
			notebirtID =>$xnote_birthID,
			notebirt =>$note_birth,
			notedeatID =>$xnote_deathID,
			notedeat =>$note_death,
			noteburiID =>$xnote_funeralID,
			noteburi =>$note_funeral,
			datemodified =>$datemodified,
			)
		);
 

$date = date('c');
$email = esc_attr(get_option('tng-api-email'));
$msg = <<<MSG
Person Notes Added /Updated({$date}):

MSG;
$msg .= print_r($_REQUEST, true);
echo "<pre>{$msg}</pre>";
mail($email, 'New data', $msg);
?>
<html>
<head>
</head>
<body>
</body>
</html>