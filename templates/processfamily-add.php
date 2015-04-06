<?php
require_once '../../../../wp-load.php';
require_once '../../../../wp-config.php';
//header('Location: /thank-you');
global $wpdb;
$wpdb->show_errors();

$people_table = $wpdb->prefix . "tng_people";
$families_table = $wpdb->prefix . "tng_families";
$children_table = $wpdb->prefix . "tng_children";
$notes_table = $wpdb->prefix . "tng_notes";
$events_table = $wpdb->prefix . "tng_events";
$update = new \Upavadi_Update_FamilyAdd($wpdb, $people_table, $families_table, $children_table, $notes_table, $events_table);
$datemodified = $update->process($_POST);

//SpecialEvent Identifiers
$Spouse = array($_POST['spouse']);
$headpersonid = $_POST['personID'];
$tnguser = $_POST['User'];
$persfamID = $_POST['personId'];
$gedcom = $_POST['gedcom'];
$persfamID = $Spouse[0]['personID'];
$eventtypeID = $_POST['EventID'];
$eventdatetr = 0000 - 00 - 00;
$info = $Spouse[0]['event'];

//Insert Special Events
$wpdb->insert(
    $events_table, array(
    headpersonid => $headpersonid,
    tnguser => $tnguser,
    persfamID => $persfamID,
    gedcom => $gedcom,
    eventtypeID => $eventtypeID,
    eventdatetr => $eventdateter,
    cause => '',
    parenttag => '',
    info => $info,
    datemodified => $datemodified,
    )
);
//CauseOfDeath - Spouse Identifiers
$Spouse = array($_POST['spouse']);
$headpersonid = $_POST['personID'];
$tnguser = $_POST['User'];
//$persfamID = $_POST['personId'];
$gedcom = $_POST['gedcom'];
$persfamID = $Spouse[0]['personID'];
$eventtypeID = 0;
$eventdatetr = '0000-00-00';
$cause = $Spouse[0]['cause'];
$parenttag = 'DEAT';
$info = '';

//Insert CauseOfDeath - Spouse
$wpdb->insert(
    $events_table, array(
    'headpersonid' => $headpersonid,
    'tnguser' => $tnguser,
    'persfamID' => $persfamID,
    'gedcom' => $gedcom,
    'eventtypeID' => $eventtypeID,
    'eventdatetr' => $eventdateter,
    'cause' => $cause,
    'eventID' => 'NewSpouseEvent',
    'parenttag' => $parenttag,
    'info' => $info,
    'datemodified' => $datemodified,
    )
);

//CauseOfDeath - Children identifiers
$child = array($_POST['child']);

$ordernum = 0;
foreach ($child as $array2):
    foreach ($array2 as $children):
        $ordernum += 1;
        $headpersonid = $_POST['personID'];
        $tnguser = $_POST['User'];
        $persfamID = "NewChild" . $ordernum;
        $gedcom = $_POST['gedcom'];
        $eventtypeID = 0;
        $eventdatetr = '0000-00-00';
        $cause = $children['cause'];
        $parenttag = 'DEAT';
        $info = '';

//Insert CauseOfDeath - Child
        $wpdb->insert(
            $events_table, array(
            'headpersonid' => $headpersonid,
            'tnguser' => $tnguser,
            'persfamID' => $persfamID,
            'gedcom' => $gedcom,
            'eventtypeID' => $eventtypeID,
            'eventdatetr' => $eventdateter,
            'cause' => $cause,
            'eventID' => 'NewChildEvent' . $ordernum,
            'parenttag' => $parenttag,
            'info' => $info,
            'datemodified' => $datemodified,
            )
        );

    endforeach;
endforeach;

//Notes identifiers
$personNotes = array($_POST['spouse_note']);
foreach ($personNotes as $Array):
    foreach ($Array as $Notes):
        $headpersonid = $_POST['personID'];
        $tnguser = $_POST['User'];
        $persfamID = $Spouse[0]['personID'];
        $gedcom = $_POST['gedcom'];
        $xnoteID = $Notes['xnote_ID'];
        $note = $Notes['note'];
        $eventID = $Notes['xeventID'];
        $ordernum = $Notes['ordernum'];
        $secret = $Notes['secret'];

//Insert Notes
        $row = array(
            'headpersonid' => $headpersonid,
            'tnguser' => $tnguser,
            'persfamID' => $persfamID,
            'gedcom' => $gedcom,
            'xnoteID' => $xnoteID,
            'note' => $note,
            'eventID' => $eventID,
            'ordernum' => $ordernum,
            'secret' => $secret,
            'datemodified' => $datemodified,
        );
        if (!$wpdb->insert($notes_table, $row)) {
            var_dump($row);
        }
    endforeach;
endforeach;
//var_dump($personNotes);
//header('Location: /thank-you');
// $wpdb->print_error();

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

  //New Spouse identifiers
  $headpersonid = $_POST['personId'];
  $tnguser = $_POST['User'];
  $personid = $_POST['spouseID'];
  $firstname = $_POST['spousefirstname'];
  $lastname = $_POST['spousesurname'];
  $birthdate = $_POST['spousebirthdate'];
  $birthdatetr = $birthdate;
  $birthplace = $_POST['spousebirthplace'];
  $deathdate = $_POST['spousedeathdate'];
  $deathdatetr = $deathdate;
  $deathplace = $_POST['spousedeathplace'];
  $sex = $_POST['spousesex'];
  $famc = "";
  $living = $_POST['spouseliving'];
  $personevent = $_POST['spouseevent'];
  $cause = $_POST['spouse_cause_of_death'];
  $datemodified = date('Y-m-d H:i:s');
  // Insert Spouse in PEOPLE
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

  //Spouse identifiers
  $familyID = "NewFamily";
  $husband = $_POST['spousehusband'];
  $wife = $_POST['spousewife'];
  $marrdate = $_POST['spousemarr_day'];
  $marrplace = $_POST['spousemarr_place'];
  $husborder = $_POST['spousehusbandorder'];
  $wifeorder = $_POST['spousewifeorder'];
  $husbandSpEvent = $_POST['husbandSpEvent'];
  $living = "";// not sure why this is required

  // Insert Parents in FAMILIES
  $wpdb->insert(
  $families_table,
  array(
  headpersonid =>$headpersonid,
  tnguser =>$tnguser,
  familyid =>$familyID,
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

  //Children identifiers
  $child = array($_POST['child']);

  $ordernum = 0;
  foreach ($child as $array2):
  foreach ($array2 as $children):
  $ordernum += 1;
  $personid = "NewChild". $ordernum;
  $firstname = $children['childfirstname'];
  $lastname = $children['childsurname'];
  $birthdate = $children['childdateborn'];
  $birthdatetr = $birthdate;
  $birthplace = $children['childplaceborn'];
  $deathdate = $children['childdatedied'];
  $deathdatetr = $deathdate;
  $deathplace = $children['childplacedied'];
  $sex = $children['childsex'];
  $famc = "";
  $living = $children['childliving'];
  if ($living != "1") {
  $living = "0";
  }
  $personevent = $husbandSpEvent;
  $childorder = $children['childorder'];
  $cause = $children['childcause'];

  //Set has kids to 0 at this stage
  //$haskids = $children['childhaskids'];
  $haskids = 0;
  $parentorder = $children['childparentorder'];

  //Check to see if chld exists
  //Insert children in children
  if ($firstname !== "") {
  $wpdb->insert(
  $children_table,
  array(
  headpersonid =>$headpersonid,
  personid =>$personid,
  tnguser =>$tnguser,
  familyID =>$familyID,
  haskids =>$haskids,
  ordernum =>$ordernum,
  parentorder =>$parentorder,
  datemodified => $datemodified,
  )

  );

  //Insert children in people
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



  }

  endforeach;
  endforeach;

  //Notes identifiers
  $headpersonid = $_POST['personId'];
  $tnguser = $_POST[User];
  $personid = $_POST[spouseID];
  $xnote_generalID = $_POST['xnote_generalID'];
  $note_general = $_POST['note_general'];
  $xnote_nameID = $_POST['xnote_nameID'];
  $note_name = $_POST['note_name'];
  $xnote_birthID = $_POST['xnote_birthID'];
  $note_birth = $_POST['note_birth'];
  $xnote_deathID = $_POST['xnote_deathID'];
  $note_death = $_POST['note_death'];
  $xnote_funeralID = $_POST['xnote_funeralID'];
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


 * 
 */

$date = date('c');
$email = esc_attr(get_option('tng-api-email'));
$msg = <<<MSG
New Person Added ({$date}):

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