<?php
require_once '../../../../wp-load.php';
require_once __DIR__ . '/../autoload.php';
require_once '../../../../wp-config.php';

header('Location: /thank-you');
//error_reporting(E_ALL);
ini_set('display_errors', 1);

global $wpdb;
$people_table = $wpdb->prefix . "tng_people";
$families_table = $wpdb->prefix . "tng_families";
$children_table = $wpdb->prefix . "tng_children";
$events_table = $wpdb->prefix . "tng_events";

$update = new Upavadi_Update_FamilyUpdate($wpdb, $people_table, $families_table, $children_table, $events_table);

$update->process($_POST);
//header('Location: /thank-you');

/* ------Cause of Death -------------- */
//Person Identifiers
$person = array($_POST['person']);
$headpersonid = $_POST['personID'];
$tnguser = $_POST['User'];
$gedcom = $_POST['gedcom'];
$persfamID = $person[0]['personID'];
$eventtypeID = 0;
$eventdatetr = '0000-00-00';
$cause = $person[0]['cause'];
$causeEventID = $person[0]['causeEventID'];
$parenttag = 'DEAT';
$info = '';
$datemodified = date('Y-m-d H:i:s');


$people = array();
$people[] = $person[0];

//Insert CauseOfDeath - Person

$wpdb->insert(
    $events_table, array(
    'headpersonid' => $headpersonid,
    'tnguser' => $tnguser,
    'persfamID' => $persfamID,
    'gedcom' => $gedcom,
    'eventtypeID' => $eventtypeID,
    'eventdatetr' => $eventdatetr,
    'cause' => $cause,
    'eventID' => $causeEventID,
    'parenttag' => $parenttag,
    'info' => $info,
    'datemodified' => $datemodified,
    )
);

//Father Identifiers
$father = array($_POST['father']);
$headpersonid = $_POST['personID'];
$tnguser = $_POST['User'];
$gedcom = $_POST['gedcom'];
$persfamID = $father[0]['personID'];
$eventtypeID = 0;
$eventdatetr = '0000-00-00';
$cause = $father[0]['cause'];
$causeEventID = $father[0]['causeEventID'];
$parenttag = 'DEAT';
$info = '';

$people[] = $father[0];

//Insert CauseOfDeath - Father
$wpdb->insert(
    $events_table, array(
    'headpersonid' => $headpersonid,
    'tnguser' => $tnguser,
    'persfamID' => $persfamID,
    'gedcom' => $gedcom,
    'eventtypeID' => $eventtypeID,
    'eventdatetr' => $eventdatetr,
    'cause' => $cause,
    'eventID' => $causeEventID,
    'parenttag' => $parenttag,
    'info' => $info,
    'datemodified' => $datemodified,
    )
);

//Mother Identifiers
$mother = array($_POST['mother']);
$headpersonid = $_POST['personID'];
$tnguser = $_POST['User'];
$gedcom = $_POST['gedcom'];
$persfamID = $mother[0]['personID'];
$eventtypeID = 0;
$eventdatetr = '0000-00-00';
$cause = $mother[0]['cause'];
$causeEventID = $mother[0]['causeEventID'];
$parenttag = 'DEAT';
$info = '';

$people[] = $mother[0];

//Insert CauseOfDeath - Mother
$wpdb->insert(
    $events_table, array(
    'headpersonid' => $headpersonid,
    'tnguser' => $tnguser,
    'persfamID' => $persfamID,
    'gedcom' => $gedcom,
    'eventtypeID' => $eventtypeID,
    'eventdatetr' => $eventdatetr,
    'cause' => $cause,
    'eventID' => $causeEventID,
    'parenttag' => $parenttag,
    'info' => $info,
    'datemodified' => $datemodified,
    )
);

//Spouse Identifiers
$family = array();
if (isset($_POST['family'])) {
    $family = array($_POST['family']);
}

foreach ($family as $array1):
    if (!is_array($array1)) {
        continue;
    }
    foreach ($array1 as $array2):
        foreach ($array2 as $personType => $array3):
            foreach ($array3 as $person):
                $people[] = $person;
                
                // If (is_array($_POST['spouse'])) {
                if (empty($person['firstname'])) {
                    continue;
                }
				if (isset($person['order']) and ($person['personID'] == "NewChild-")) {

				$personid = "NewChild-". $person['spouseorder']. ".". $person['order'];
				$person['causeEventID'] = "NewEvent". $person['spouseorder']. ".". $person['order'];
				} else {
				$personid = $person['personID'];
				}
                $headpersonid = $_POST['personID'];
                $tnguser = $_POST['User'];
                $gedcom = $_POST['gedcom'];
                $persfamID = $personid;
                $eventtypeID = 0;
                $eventdatetr = '0000-00-00';
                $cause = $person['cause'];
                $causeEventID = $person['causeEventID'];
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
                    'eventdatetr' => $eventdatetr,
                    'cause' => $cause,
                    'eventID' => $causeEventID,
                    'parenttag' => $parenttag,
                    'info' => $info,
                    'datemodified' => $datemodified,
                    )
                );
//}
            endforeach;
        endforeach;
    endforeach;
endforeach;


foreach ($people as $index => $person) {
    if (isset($person['order']) and ($person['personID'] == "NewChild-")) {

	$personid = "NewChild-". $person['spouseorder']. ".". $person['order'];

	} else {
	$personid = $person['personID'];
	}

	if (empty($person['eventTypeID'])) {
        continue;
    }

    if (empty($person['eventID']) and isset($person['order'])) {
	$order = 1;
        if (isset($person['spouseorder'])) {
            $order = $person['spouseorder'];
        }
        $person['eventID'] = 'NewSpEvent' . $order . ".". $person['order']; 
    }
 if (empty($person['eventID'])) {
        $person['eventID'] = 'NewSpEvent' . $index; 
    }
     
   if (empty($person['eventID']) && empty($person['event'])) {
        continue;
    }

    $row = array(
        'headpersonid' => $headpersonid,
        'tnguser' => $tnguser,
        'persfamID' => $personid,
        'gedcom' => $gedcom,
        'eventtypeID' => $person['eventTypeID'],
        'eventdatetr' => $eventdatetr,
        'eventID' => $person['eventID'],
        'parenttag' => '',
        'info' => $person['event'],
        'datemodified' => $datemodified,
    );
    $wpdb->insert($events_table, $row);
}

$date = date('c');
$email = esc_attr(get_option('tng-api-email'));
$msg = <<<MSG
	Person Details Updated ({$date}):

MSG;
$msg .= print_r($_REQUEST, true);
echo "<pre>{$msg}</pre>";
mail($email, 'New data', $msg);
?>
Person Notes Added /Updated
<html>
    <head>
    </head>
    <body>
    </body>
</html>