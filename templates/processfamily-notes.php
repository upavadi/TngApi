<?php
require_once '../../../../wp-load.php';
//header('Location: /thank-you');
//this the original

global $wpdb;
$wpdb->show_errors();
$people_table = $wpdb->prefix . "tng_people";
$notes_table = $wpdb->prefix . "tng_notes";

//Person identifiers
$tnguser = $_POST['User'];
$headpersonid = $_POST['personId'];
$personid = $_POST['personId'];
$firstname = $_POST['personfirstname'];
$lastname = $_POST['personsurname'];
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

//Notes identifiers
$personNotes = (array) $_POST['person_note'];
foreach ($personNotes as $Notes):
    $headpersonid = $_POST['personId'];
    $tnguser = $_POST['User'];
    $personid = $_POST['personId'];
    $gedcom = $_POST['persongedcom'];
    $xnoteID = $Notes['xnoteID'];
    $notelinkID = $Notes['notelinkID'];
    $note = $Notes['note'];
    $eventID = $Notes['xeventID'];
    $ordernum = $Notes['ordernum'];
    $secret = $Notes['secret'];
//var_dump($personNotes);
//Insert Notes
    $row = array(
        'headpersonid' => $headpersonid,
        'tnguser' => $tnguser,
        'persfamID' => $personid,
        'gedcom' => $gedcom,
        'xnoteID' => $xnoteID,
        'notelinkID' => $notelinkID,
        'note' => $note,
        'eventID' => $eventID,
        'ordernum' => $ordernum,
        'secret' => $secret,
        'datemodified' => $datemodified,
    );
    if (!$wpdb->insert($notes_table, $row)) {
        var_dump($row);
    }
    //print_r($Notes);
endforeach;
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
