<?php
require_once '../../../../wp-load.php';
require_once __DIR__ . '/../autoload.php';

//header('Location: /thank-you');

error_reporting(E_ALL);
ini_set('display_errors', 1);

global $wpdb;
$people_table = $wpdb->prefix . "tng_people";
$families_table = $wpdb->prefix . "tng_families";
$children_table = $wpdb->prefix . "tng_children";

$update = new \Upavadi_Update_FamilyUpdate($wpdb, $people_table, $families_table, $children_table);

$update->process($_POST);
header('Location: /thank-you');

/*
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
  if (($_POST['personfamc'] == '' AND $_POST['fathername'] !== '') OR ( $_POST['personfamc'] == '' AND $_POST['fathername'] !== '')) {
  $famc = "NewParents";
  } else {
  $famc = $_POST['personfamc'];
  }
  $living = $_POST['personliving'];
  $personevent = $_POST['personevent'];
  $cause = $_POST['cause_of_death'];
  $datemodified = date('Y-m-d H:i:s');

  // Insert Head Person in PEOPLE
  $wpdb->insert(
  $people_table, array(
  'headpersonid' => $headpersonid,
  'personid' => $personid,
  'tnguser' => $tnguser,
  'firstname' => $firstname,
  'lastname' => $lastname,
  'personevent' => $personevent,
  'birthdate' => $birthdate,
  'birthdatetr' => $birthdatetr,
  'birthplace' => $birthplace,
  'deathdate' => $deathdate,
  'deathdatetr' => $deathdatetr,
  'deathplace' => $deathplace,
  'sex' => $sex,
  'famc' => $famc,
  'living' => $living,
  'cause' => $cause,
  'datemodified' => $datemodified,
  )
  );


  //Father identifiers
  $personid == "";
  if ($_POST['fathername'] !== '') {
  $fatherstatus = "yes";
  $personid = $_POST['fatherpersonId'];
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
  $cause = $_POST['father_cause_of_death'];
  // Insert Father in PEOPLE
  $wpdb->insert(
  $people_table, array(
  'headpersonid' => $headpersonid,
  'personid' => $personid,
  'tnguser' => $tnguser,
  'firstname' => $firstname,
  lastname => $lastname,
  personevent => $personevent,
  birthdate => $birthdate,
  birthdatetr => $birthdatetr,
  birthplace => $birthplace,
  deathdate => $deathdate,
  deathdatetr => $deathdatetr,
  deathplace => $deathplace,
  sex => $sex,
  famc => $famc,
  living => $living,
  cause => $cause,
  datemodified => $datemodified,
  )
  );
  }
  //Mother identifiers

  if ($_POST['mothername'] !== '') {
  $motherstatus == "yes";
  $personid = $_POST['motherpersonId'];
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
  $cause = $_POST['mother_cause_of_death'];
  // Insert Mother in PEOPLE
  $wpdb->insert(
  $people_table, array(
  headpersonid => $headpersonid,
  personid => $personid,
  tnguser => $tnguser,
  firstname => $firstname,
  lastname => $lastname,
  personevent => $personevent,
  birthdate => $birthdate,
  birthdatetr => $birthdatetr,
  birthplace => $birthplace,
  deathdate => $deathdate,
  deathdatetr => $deathdatetr,
  deathplace => $deathplace,
  sex => $sex,
  famc => $famc,
  living => $living,
  cause => $cause,
  datemodified => $datemodified,
  )
  );
  }

  //parents identifiers
  if ($fatherstatus == "yes" OR $motherstatus == "yes") {


  if ($_POST['personfamc'] == '') {
  $familyID = "NewParents";
  } else {
  $familyID = $_POST['personfamc'];
  }

  $husband = $_POST['fatherpersonId'];
  $wife = $_POST['motherpersonId'];
  $marrdate = $_POST['parentmarr_day'];
  $marrplace = $_POST['parentmarr_Place'];
  $husborder = $_POST['parents_husborder'];
  $wifeorder = $_POST['parents_wifeorder'];
  $living = "0"; // set to zero. Set 1 for ban on viewing living family
  // Insert Parents in FAMILIES
  $wpdb->insert(
  $families_table, array(
  headpersonid => $headpersonid,
  tnguser => $tnguser,
  familyid => $familyID,
  husband => $husband,
  wife => $wife,
  marrdate => $marrdate,
  marrdatetr => $marrdatetr,
  marrplace => $marrplace,
  husborder => $husborder,
  wifeorder => $wifeorder,
  living => $living,
  datemodified => $datemodified,
  )
  );
  }

  //Spouse identifiers
  $family = array($_POST['family']);
  If (is_array($_POST['family'])) {
  foreach ($family as $array1):
  foreach ($array1 as $array2):
  foreach ($array2 as $spouse):
  $personid = $spouse['spouseID'];
  $firstname = $spouse['spousename'];
  $lastname = $spouse['spousesurname'];
  $birthdate = $spouse['spouseB.day'];
  $birthdatetr = $birthdate;
  $birthplace = $spouse['spouseB.place'];
  $deathdate = $spouse['spouseD.day'];
  $deathdatetr = $deathdate;
  $deathplace = $spouse['spouseD.Place'];
  $sex = $spouse['spousesex'];
  $famc = $spouse['spousefamc'];
  $living = $spouse['spouseliving'];
  $personevent = $spouse['spouseevent'];
  $cause = $spouse['spouse_cause_of_death'];
  // Insert Spouse(s) in PEOPLE
  $wpdb->insert(
  $people_table, array(
  headpersonid => $headpersonid,
  personid => $personid,
  tnguser => $tnguser,
  firstname => $firstname,
  lastname => $lastname,
  personevent => $personevent,
  birthdate => $birthdate,
  birthdatetr => $birthdatetr,
  birthplace => $birthplace,
  deathdate => $deathdate,
  deathdatetr => $deathdatetr,
  deathplace => $deathplace,
  sex => $sex,
  famc => $famc,
  living => $living,
  cause => $cause,
  datemodified => $datemodified,
  )
  );
  //Spouse Family identifiers
  $familyID = $spouse['spousefamilyID'];
  $husband = $spouse['spousehusband'];
  $wife = $spouse['spousewife'];
  $marrdate = $spouse['spousemarr.day'];
  $marrplace = $spouse['spousemarr.place'];
  $husborder = $spouse['spousehusborder'];
  $wifeorder = $spouse['spousewifeorder'];
  $living = "0"; // set to zero. Set 1 for ban on viewing living family
  //Insert Spouses in FAMILIES

  $wpdb->insert(
  $families_table, array(
  headpersonid => $headpersonid,
  tnguser => $tnguser,
  familyid => $familyID,
  husband => $husband,
  wife => $wife,
  marrdate => $marrdate,
  marrdatetr => $marrdatetr,
  marrplace => $marrplace,
  husborder => $husborder,
  wifeorder => $wifeorder,
  living => $living,
  datemodified => $datemodified,
  )
  );
  endforeach;
  endforeach;
  endforeach;
  }

  //Children identifiers
  If (is_array($_POST['child'])) {
  $child = array($_POST['child']);

  foreach ($child as $array1):
  foreach ($array1 as $array2):
  foreach ($array2 as $children):
  $personid = $children['childID'];
  $firstname = $children['childfirstname'];
  $lastname = $children['childsurname'];
  $birthdate = $children['childdateborn'];
  $birthdatetr = $birthdate;
  $birthplace = $children['childplaceborn'];
  $deathdate = $children['childdatedied'];
  $deathdatetr = $deathdate;
  $deathplace = $children['childplacedied'];
  $sex = $children['childsex'];
  $famc = $children['childfamc'];
  $living = $children['childliving'];
  $personevent = $children['childevent'];
  $childorder = $children['childorder'];
  $cause = $children['childcause'];
  $familyID = $children['childfamilyID'];
  $personid = $children['childID'];
  $haskids = $children['childhaskids'];
  $ordernum = $children['childorder'];
  $parentorder = $children['childparentorder'];

  //Check to see if chld exists
  //Insert children in children
  if ($firstname !== "") {
  $wpdb->insert(
  $children_table, array(
  headpersonid => $headpersonid,
  personid => $personid,
  tnguser => $tnguser,
  familyID => $familyID,
  haskids => $haskids,
  ordernum => $ordernum,
  parentorder => $parentorder,
  datemodified => $datemodified,
  )
  );

  //Insert children in people
  $wpdb->insert(
  $people_table, array(
  headpersonid => $headpersonid,
  personid => $personid,
  tnguser => $tnguser,
  firstname => $firstname,
  lastname => $lastname,
  personevent => $personevent,
  birthdate => $birthdate,
  birthdatetr => $birthdatetr,
  birthplace => $birthplace,
  deathdate => $deathdate,
  deathdatetr => $deathdatetr,
  deathplace => $deathplace,
  sex => $sex,
  famc => $famc,
  living => $living,
  cause => $cause,
  datemodified => $datemodified,
  )
  );
  }

  endforeach;
  endforeach;
  endforeach;
  }
  //print_r($children);
  //echo "<pre>{$_POST}</pre>";
  //this the original
  $date = date('c');
  $email = esc_attr(get_option('tng-api-email'));
  $msg = <<<MSG
  Person Details Updated({$date}):

  MSG;
  $msg .= print_r($_REQUEST, true);
  echo "<pre>{$msg}</pre>";
  mail($email, 'New data', $msg);
 * 
 */
?>
<html>
    <head>
    </head>
    <body>
    </body>
</html>