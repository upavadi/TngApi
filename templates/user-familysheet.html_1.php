    
		<?php

//NEW Page	
//User Submissions - This will be bundled with Thank-you oage
			
			$tngcontent = Upavadi_tngcontent::instance()->init();

			
//get and hold current user
				$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
				$person = $tngcontent->getPerson($currentperson);
				$currentuser = ($person['firstname']. $person['lastname']);
				$currentuserLogin = wp_get_current_user();
				$UserLogin = $currentuserLogin->user_login;
			echo "<b>Submissions for updates by ". $currentuser;	
//Check for Current User Submissions
			global $wpdb;
			$usersubmissions = $wpdb->get_var( "SELECT count(*) FROM wp_tng_people where tnguser = '$UserLogin' AND headpersonid = personid");
				if ($usersubmissions == "0") {
				echo '<h2>'. "No submissions". '</h2>';
				//Suppress Thank you message
				} else {
			
//Get Headperson ID  for User and put in array 
				$usersubmissionID = $wpdb->get_results( "SELECT * FROM wp_tng_people where tnguser = '$UserLogin' AND headpersonid = personid ORDER BY datemodified DESC");
			foreach ($usersubmissionID as $usercount => $userID):
				$submissionID = $userID->id;
				$userentries[$usercount] = $submissionID;
			endforeach;	
			

//Get headpersonID from array and display submission				
			foreach ($userentries as $usercount => $userentry):
				//$usercount += 1;
				$usersubmitID = $userentries[$usercount];
				$person = $wpdb->get_row( "SELECT * FROM wp_tng_people WHERE id = '$userentry'");
				$datemodified = $person->datemodified;
				$headpersonid = $person->headpersonid;
				$User = $person->tnguser;
				$headpersonid = $person->headpersonid;
				$new_personid = $person->personid;
				$new_lastname = $person->lastname;
				$new_firstname = $person->firstname;
				$new_personevent = $person->personevent;
				$new_birthdate = $person->birthdate;
				$new_birthplace = $person->birthplace;
				$new_deathdate = $person->deathdate;
				$new_deathplace = $person->deathplace;
				$new_sex = $person->sex;
				$new_famc = $person->famc;
				$new_living = $person->living;
				$new_cause = $person->cause;
				
//get newParents
	$parents = $wpdb->get_row( "SELECT * FROM wp_tng_families where familyid = '$new_famc' AND datemodified = '$datemodified'");
		$new_father = $parents->husband;
		$new_mother = $parents->wife;
		$newparentsmarrdate = $parents->marrdate;
		$newparentsmarrplace = $parents->marrplace;
		$newparentshusborder = $parents->husborder;
		$newparentswifeorder = $parents->wifeorder;
		
		
/*New father and mother
		if ($new_famc == "") {
		$new_famc = "NewFamily";
		$new_familyid = "NewFamily";
		}
*/		
//Get Father
	$newfather = $wpdb->get_row( "SELECT * FROM wp_tng_people WHERE '$new_father' = personid AND datemodified = '$datemodified'");
		$newfather_lastname = $newfather->lastname;
		$newfather_firstname = $newfather->firstname;
		$newfather_personevent = $newfather->personevent;
		$newfather_birthdate = $newfather->birthdate;
		$newfather_birthplace = $newfather->birthplace;
		$newfather_deathdate = $newfather->deathdate;
		$newfather_deathplace = $newfather->deathplace;
		$newfather_sex = $newfather->sex;
		$newfather_famc = $newfather->famc;
		$newfather_living = $newfather->living;
		$newfather_cause = $newfather->cause;
	
//Get Mother
	$newmother = $wpdb->get_row( "SELECT * FROM wp_tng_people WHERE '$new_mother' = personid AND datemodified = '$datemodified'");
		$newmother_lastname = $newmother->lastname;
		$newmother_firstname = $newmother->firstname;
		$newmother_personevent = $newmother->personevent;
		$newmother_birthdate = $newmother->birthdate;
		$newmother_birthplace = $newmother->birthplace;
		$newmother_deathdate = $newmother->deathdate;
		$newmother_deathplace = $newmother->deathplace;
		$newmother_sex = $newmother->sex;
		$newmother_famc = $newmother->famc;
		$newmother_living = $newmother->living;
		$newmother_cause = $newmother->cause;
		
//talk to TNG   
    $tngcontent = Upavadi_TngContent::instance();
	$UserName = get_user_by( 'login', $User ); 
	
//get person details
	$personID = $new_personid; 
    $person = $tngcontent->getPerson($personID);
    $birthdate = $person['birthdate'];
    $birthdatetr = ($person['birthdatetr']);
    $birthplace = $person['birthplace'];
    $deathdate = $person['deathdate'];
    $deathdatetr = ($person['deathdatetr']);
    $deathplace = $person['deathplace'];
    $firstname = $person['firstname'];
	$lastname = $person['lastname'];
	$smarrdate = $person['marrdate'];
    $marrplace = $person['marrplace'];
	$living = $person['living'];
	$sex = $person['sex'];
	$personfamc = $person['famc'];
//get Special Event
	
	$personRow = $tngcontent->getSpEvent($person['personID']);
	$SpEvent = $personRow['info'];

	//get Description of Event type 
		
	$EventRow = $tngcontent->getEventDisplay($event['display']);	
	$EventDisplay = $EventRow['display'];
//get Cause of Death for person
		$personRow = $tngcontent->getCause($person['personID']);
		if ($personRow['eventtypeID'] == "0") {
			$cause_of_death = $personRow['cause'];
		} else {
		$cause_of_death = "";
		}
	
//get family Details

				if ($person['sex'] == 'M') {
					$sortBy = 'husborder';
				} else if ($person['sex'] == 'F') {
					$sortBy = 'wifeorder';
				} else {
					$sortBy = null;
				}
    
		$families = $tngcontent->getFamilyUser($person['personID'], $sortBy);
				
				$parents = '';
				$parents = $tngcontent->getFamilyById($person['famc']);

				if ($person['famc'] !== '' and $parents['wife'] !== '') {
					$mother = $tngcontent->getPerson($parents['wife']);
				} 
				if ($person['famc'] !== ''and $parents['husband'] !== '') {
					$father = $tngcontent->getPerson($parents['husband']);
				}
	
			
/* Set Person famc for new parents
				
				if ($person['famc'] == '') {
					$personfamc = "NewFamily";
					} else {
					$personfamc = $person['famc'];
					}
*/				
//set Father details 
				if ($parents['husband'] == '') {
				$father = array(null);
				}
				$parentsID = $personfamc;
                $fatherID = $father['personID'];
                $fatherbirthdate = $father['birthdate'];
                $fatherbirthplace = $father['birthplace'];
                $fatherdeathdate = $father['deathdate'];
                $fatherdeathplace = $father['deathplace'];
				$fatherfirstname = $father['firstname'];
				$fathersurname = $father['lastname'];
				$fatherliving = $father['living'];
				$fathersex = $father['sex'];
				 				
// Father -Special Event
				if ($father['personID'] !== null)
				{
				$fatherRow = $tngcontent->getSpEvent($father['personID']);
				$father_SpEvent = $fatherRow['info'];
				} else {
				$father_SpEvent = '';
				}
//get Cause of Death for Father
				$fatherRow = $tngcontent->getCause($father['personID']);
				if ($fatherRow['eventtypeID'] == "0") {
					$fathercause = $fatherRow['cause'];
				} else {
				$fathercause = "";
				} 				
				
//set Mother details
				if ($parents['wife'] == '') {
				$mother = array(null);
				}
				$motherID = $mother['personID'];
				$motherbirthdate = $mother['birthdate'];
                $motherbirthplace = $mother['birthplace'];
                $motherdeathdate = $mother['deathdate'];
                $motherdeathplace = $mother['deathplace'];
				$motherfirstname = $mother['firstname'];
				$mothersurname = $mother['lastname'];
				$motherliving = $mother['living'];
				$mothersex = $mother['sex'];

// Mother - Special Event
				if ($mother['personID'] !== null)
				{
				$motherRow = $tngcontent->getSpEvent($mother['personID']);
				$mother_SpEvent = $motherRow['info'];
				} else {
				$mother_SpEvent = '';
				} 
//get Cause of Death for Mother
				$motherRow = $tngcontent->getCause($mother['personID']);
				if ($motherRow['eventtypeID'] == "0") {
					$mothercause = $motherRow['cause'];
				} else {
				$mothercause = "";
				}
		 
// Parents Marriage Data
		        $parentsmarrdate = $parents['marrdate'];
                $parentsmarrplace = $parents['marrplace'];
				$parentshusborder = $parents['husborder'];
				$parentswifeorder = $parents['wifeorder'];
			
// get Notes			
	$noteperson = $wpdb->get_row( "SELECT * FROM wp_tng_notes WHERE headpersonid ='$headpersonid' AND datemodified = '$datemodified'");
		$new_notepersonid = $noteperson->persfamID;
		$new_xnote_generalID = $noteperson->noteID;
		$new_xnote_nameID = $noteperson->notenameID;
		$new_xnote_birthID = $noteperson->notebirtID;
		$new_xnote_deathID = $noteperson->notedeatID;
		$new_xnote_funeralID = $noteperson->noteburiID;
		$new_notegeneral = $noteperson->note;
		$new_notename = $noteperson->notename;
		$new_notebirth = $noteperson->notebirt;
		$new_notedeath = $noteperson->notedeat;
		$new_notefuneral = $noteperson->noteburi;
	$notepersonname = $wpdb->get_row( "SELECT * FROM wp_tng_people WHERE personid ='$new_notepersonid' AND datemodified = '$datemodified'");	
	$Notes_for = $notepersonname->firstname. $notepersonname->lastname;	
	
	 	
//get All notes
		$personId =$new_personid;
		$allnotes = $tngcontent->getnotes($personId);
					$note_general = "";
					$note_name = "";
					$note_name = "";
					$note_birth = "";
					$note_death = "";
					$note_funeral = "";
	foreach($allnotes as $PersonNote):
		if ($PersonNote['eventID'] == null) {
					$xnote_generalID = $PersonNote['xnoteID'];
					$note_general = $PersonNote['note'];
					}
		if ($PersonNote['eventID'] == "NAME") {
					$xnote_nameID = $PersonNote['xnoteID'];
					$note_name = $PersonNote['note'];
					}
		if ($PersonNote['eventID'] == "BIRT") {
					$xnote_birthID = $PersonNote['xnoteID'];
					$note_birth = $PersonNote['note'];
					}
		
		if ($PersonNote['eventID'] == "DEAT") {
					$xnote_deathID = $PersonNote['xnoteID'];
					$note_death = $PersonNote['note'];
					}
		if ($PersonNote['eventID'] == "BURI") {
					$xnote_funeralID = $PersonNote['xnoteID'];
					$note_funeral = $PersonNote['note'];
					}			
	endforeach; 		
	
	echo "<br/><b>Changes for the Family of ". $new_firstname. " ". $new_lastname. " *** Submission " . ($usersubmissions - $usercount) ." of " .$usersubmissions ." (ID = ". $userentry. " )";		
	?>
	<table class="table-sheet1">
		<tbody>
		<th style="background-color: #CACACA; width: 16%";>Head Person</th>
		<th style="background-color: #CACACA; width: 42%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 42%">FAMILY Database</th>
        <tr>
			<?php 
			if ($new_personid !== $personID AND $new_personid == "" ) { 
			?>
			<td class="tdback"  width="10%"><?php echo "Person ID"; ?></td>
			
			<td class="tdsheet1"><?php echo $new_personid;?></td>
			<td class="tdsheet1"><?php echo $personID; ?></td>
		<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_firstname == $firstname OR $new_firstname == "") { 
			?>
			<td><?php echo "First Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_firstname;?></td>
			<td class="tdsheet1"><?php echo $firstname; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "First Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_firstname;?></td>
			<td class="tdsheet1"><?php echo $firstname; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_lastname == $lastname OR $new_lastname == "") { 
			?>
			<td><?php echo "Last Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_lastname;?></td>
			<td class="tdsheet1"><?php echo $lastname ; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "First Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_lastname;?></td>
			<td class="tdsheet1"><?php echo $lastname; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_personevent !== $SpEvent and $new_personevent != '') { 
			?>
			<td class="tdback"><?php echo $EventDisplay; ?></td>
			<td class="tdsheet1"><?php echo $new_personevent;?></td>
			<td class="tdsheet1"><?php echo $SpEvent; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_birthdate !== $birthdate and $new_birthdate !== '') { 
			?>
			<td class="tdback"><?php echo "Born"; ?></td>
			<td class="tdsheet1"><?php echo $new_birthdate;?></td>
			<td class="tdsheet1"><?php echo $birthdate; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_birthplace !== $birthplace and $new_birthplace !== '') { 
			?>
			<td class="tdback"><?php echo "Place of Birth"; ?></td>
			<td class="tdsheet1"><?php echo $new_birthplace;?></td>
			<td class="tdsheet1"><?php echo $birthplace; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_deathdate !== $deathdate and $new_deathdate !== '') { 
			?>
			<td class="tdback"><?php echo "Died"; ?></td>
			<td class="tdsheet1"><?php echo $new_deathdate;?></td>
			<td class="tdsheet1"><?php echo $deathdate; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_deathplace !== $deathplace and $new_deathplace !== '') { 
			?>
			<td class="tdback"><?php echo "Place of Death"; ?></td>
			<td class="tdsheet1"><?php echo $new_deathplace;?></td>
			<td class="tdsheet1"><?php echo $deathplace; ?></td>
			<?php } ?>
		</tr>
		<tr>
		<?php 
			if ($new_cause !== $cause_of_death and $new_cause !== '') { 
			?>
			<td class="tdback"><?php echo "Cause of Death"; ?></td>
			<td class="tdsheet1"><?php echo $new_cause;?></td>
			<td class="tdsheet1"><?php echo $cause_of_death; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_living !== $living and $new_living !== '') { 
			?>
			<td class="tdback"><?php echo "Living"; ?></td>
			<td class="tdsheet1"><?php echo $new_living;?></td>
			<td class="tdsheet1"><?php echo $living; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_sex !== $sex and $new_sex !== '') { 
			?>
			<td class="tdback"><?php echo "Sex"; ?></td>
			<td class="tdsheet1"><?php echo $new_sex;?></td>
			<td class="tdsheet1"><?php echo $sex; ?></td>
			<?php } ?>
		</tr>
		</table>		
		
		<table class="table-sheet1">
		<th style="background-color: #CACACA; width: 16%";>Father</th>
		<th style="background-color: #CACACA; width: 42%";>Changes Submitted</th>
		<th style="background-color: #CACACA; width: 42%";>FAMILY Database</th>
		<tr>
			<?php 
			if ($new_father !== $fatherID AND $new_father != '') { 
			?>
			<td class="tdback"><?php echo "Father ID"; ?></td>
			<td class="tdsheet1"><?php echo $new_father;?></td>
			<td class="tdsheet1"><?php echo $fatherID; ?></td>
			<?php } ?>
		</tr>	
		<tr>
			<?php 
			if ($newfather_firstname == $fatherfirstname OR $newfather_firstname == "") { 
			?>
			<td><?php echo "Father Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $newfather_firstname;?></td>
			<td class="tdsheet1"><?php echo $fatherfirstname; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "Father Name"; ?></td>
			<td class="tdsheet1"><?php echo $newfather_firstname;?></td>
			<td class="tdsheet1"><?php echo $fatherfirstname; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($newfather_lastname == $fathersurname AND $newfather_lastname != '') { 
			?>
			<td><?php echo "Last Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $newfather_lastname;?></td>
			<td class="tdsheet1"><?php echo $fathersurname; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "Last Name"; ?></td>
			<td class="tdsheet1"><?php echo $newfather_lastname;?></td>
			<td class="tdsheet1"><?php echo $fathersurname; ?></td>
			<?php } ?>
		</tr>
		<tr>		
			<?php 
			if ($newfather_personevent !== $father_SpEvent AND $newfather_personevent != '') { 
			?>
			<td class="tdback"><?php echo $EventDisplay; ?></td>
			
			<td class="tdsheet1"><?php echo $newfather_personevent;?></td>
			<td class="tdsheet1"><?php echo $father_SpEvent; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newfather_birthdate !== $fatherbirthdate AND $newfather_birthdate != '') { 
			?>
			<td class="tdback"><?php echo "Birth Date"; ?></td>
			
			<td class="tdsheet1"><?php echo $newfather_birthdate;?></td>
			<td class="tdsheet1"><?php echo $fatherbirthdate; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($newfather_birthplace !== $fatherbirthplace AND $newfather_birthplace != '') { 
			?>
			<td class="tdback"><?php echo "Place of Birth"; ?></td>
			<td class="tdsheet1"><?php echo $newfather_birthplace;?></td>
			<td class="tdsheet1"><?php echo $fatherbirthplace; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newfather_deathdate !== $fatherdeathdate AND $newfather_deathdate != '') { 
			?>
			<td class="tdback"><?php echo "Death Date"; ?></td>
			<td class="tdsheet1"><?php echo $newfather_deathdate;?></td>
			<td class="tdsheet1"><?php echo $fatherdeathdate; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newfather_deathplace !== $fatherdeathplace AND $newfather_deathplace != '') { 
			?>
			<td class="tdback"><?php echo "Place of Death"; ?></td>
			<td class="tdsheet1"><?php echo $newfather_deathplace;?></td>
			<td class="tdsheet1"><?php echo $fatherdeathplace; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newfather_cause !== $fathercause AND $newfather_cause != '') { 
			?>
			<td class="tdback"><?php echo "Cause of Death"; ?></td>
			<td class="tdsheet1"><?php echo $newfather_cause;?></td>
			<td class="tdsheet1"><?php echo $fathercause; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newfather_living !== $fatherliving AND $newfather_living != '') { 
			?>
			<td class="tdback"><?php echo "Living"; ?></td>
			
			<td class="tdsheet1"><?php echo $newfather_living;?></td>
			<td class="tdsheet1"><?php echo $fatherliving; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newfather_sex !== $fathersex AND $newfather_sex != '') { 
			?>
			<td class="tdback"><?php echo "Sex"; ?></td>
			<td class="tdsheet1"><?php echo $newfather_sex;?></td>
			<td class="tdsheet1"><?php echo $fathersex; ?></td>
			<?php } ?>
		</tr>
        </table>

	
 <!-- Mother table -->   	
				
		<table class="table-sheet1">
		<th style="background-color: #CACACA; width: 16%">Mother</th>
		<th style="background-color: #CACACA; width: 42%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 42%">FAMILY Database</th>
		<tr>
			<?php 
			if ($new_mother !== $motherID AND $new_mother != "") { 
			?>
			<td class="tdback"><?php echo "Mother ID"; ?></td>
			<td class="tdsheet1"><?php echo $new_mother;?></td>
			<td class="tdsheet1"><?php echo $motherID; ?></td>
			<?php } ?>
		</tr>	
		<tr>
			<?php 
			if ($newmother_firstname == $motherfirstname OR $newmother_firstname == '') { 
			?>
			<td><?php echo "Mother Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $newmother_firstname;?></td>
			<td class="tdsheet1"><?php echo $motherfirstname; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "Mother Name"; ?></td>
			<td class="tdsheet1"><?php echo $newmother_firstname;?></td>
			<td class="tdsheet1"><?php echo $motherfirstname; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($newmother_lastname == $mothersurname OR $newmother_lastname == '') { 
			?>
			<td><?php echo "Last Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $newmother_lastname;?></td>
			<td class="tdsheet1"><?php echo $mothersurname; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "Last Name"; ?></td>
			<td class="tdsheet1"><?php echo $newmother_lastname;?></td>
			<td class="tdsheet1"><?php echo $mothersurname; ?></td>
			<?php } ?>
		</tr>
		<tr>		
			<?php 
			if ($newmother_personevent !== $mother_SpEvent AND $newmother_personevent != '') { 
			?>
			<td class="tdback"><?php echo $EventDisplay; ?></td>
			<td class="tdsheet1"><?php echo $newmother_personevent;?></td>
			<td class="tdsheet1"><?php echo $mother_SpEvent; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newmother_birthdate !== $motherbirthdate AND $newmother_birthdate != '') { 
			?>
			<td class="tdback"><?php echo "Birth Date"; ?></td>
			<td class="tdsheet1"><?php echo $newmother_birthdate;?></td>
			<td class="tdsheet1"><?php echo $motherbirthdate; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($newmother_birthplace !== $motherbirthplace AND $newmother_birthplace != '') { 
			?>
			<td class="tdback"><?php echo "Place of Birth"; ?></td>
			<td class="tdsheet1"><?php echo $newmother_birthplace;?></td>
			<td class="tdsheet1"><?php echo $motherbirthplace; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newmother_deathdate !== $motherdeathdate AND $newmother_deathdate != '') { 
			?>
			<td class="tdback"><?php echo "Death Date"; ?></td>
			<td class="tdsheet1"><?php echo $newmother_deathdate;?></td>
			<td class="tdsheet1"><?php echo $motherdeathdate; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newmother_deathplace !== $motherdeathplace AND $newmother_deathplace != '') { 
			?>
			<td class="tdback"><?php echo "Place of Death"; ?></td>
			<td class="tdsheet1"><?php echo $newmother_deathplace;?></td>
			<td class="tdsheet1"><?php echo $motherdeathplace; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newmother_cause !== $mothercause AND $newmother_cause != '') { 
			?>
			<td class="tdback"><?php echo "Cause of Death"; ?></td>
			<td class="tdsheet1"><?php echo $newmother_cause;?></td>
			<td class="tdsheet1"><?php echo $mothercause; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newmother_living !== $motherliving AND $newmother_living != '') { 
			?>
			<td class="tdback"><?php echo "Living"; ?></td>
			<td class="tdsheet1"><?php echo $newmother_living;?></td>
			<td class="tdsheet1"><?php echo $motherliving; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($newmother_sex !== $mothersex AND $newmother_sex != '') { 
			?>
			<td class="tdback"><?php echo "Sex"; ?></td>
			<td class="tdsheet1"><?php echo $newmother_sex;?></td>
			<td class="tdsheet1"><?php echo $mothersex; ?></td>
			<?php } ?>
		</tr>
        </table>		

<table width="100%" border="1">
		<th style="background-color: #CACACA; width: 16%">Parents</th>
		<th style="background-color: #CACACA; width: 42%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 42%">Family Database</th>
		<tr>
			<?php 
			if ($new_famc == $personfamc or $new_famc == "") { 
			?>
			<td><?php echo "Parents ID - famc (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_famc;?></td>
			<td class="tdsheet1"><?php echo $personfamc; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "ParentsID (famc)"; ?></td>
			<td class="tdsheet1"><?php echo $new_famc;?></td>
			<td class="tdsheet1"><?php echo $personfamc; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($newparentsmarrdate !== $parentsmarrdate AND $newparentsmarrdate != '') { 
			?>
			<td class="tdback"><?php echo "Marriage date"; ?></td>
			<td class="tdsheet1"><?php echo $newparentsmarrdate;?></td>
			<td class="tdsheet1"><?php echo $parentsmarrdate; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($newparentsmarrplace !== $parentsmarrplace AND $newparentsmarrplace != '') { 
			?>
			<td class="tdback"><?php echo "Marriage Place"; ?></td>
			<td class="tdsheet1"><?php echo $newparentsmarrplace;?></td>
			<td class="tdsheet1"><?php echo $parentsmarrplace; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($newparentshusborder !== $parentshusborder AND $newparentshusborder != '') { 
			?>
			<td class="tdback"><?php echo "Husband Order"; ?></td>
			<td class="tdsheet1"><?php echo $newparentshusborder;?></td>
			<td class="tdsheet1"><?php echo $parentshusborder; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($newparentswifeorder !== $parentswifeorder AND $newparentswifeorder != '') { 
			?>
			<td class="tdback"><?php echo "Wife Order"; ?></td>
			<td class="tdsheet1"><?php echo $newparentswifeorder;?></td>
			<td class="tdsheet1"><?php echo $parentswifeorder; ?></td>
			<?php } ?>
		</tr>
        </table>		
<!-- Spouse(s) -->
	<?php
	//get New Spouse(s)
	
	$newfamilies = $wpdb->get_results( "SELECT * FROM wp_tng_families WHERE ('$headpersonid' = husband AND datemodified = '$datemodified') OR ('$headpersonid' = wife AND datemodified = '$datemodified')");
	foreach ($newfamilies as $newfamily):
		$new_spousefamilyid = $newfamily->familyid;
	if ($headpersonid == $newfamily->husband) {
		$new_spouseid = $newfamily->wife;
		}
	if ($headpersonid == $newfamily->wife) {
		$new_spouseid = $newfamily->husband;
		}		
		$newspouse = $wpdb->get_row( "SELECT * FROM wp_tng_people WHERE personid = '$new_spouseid' AND datemodified = '$datemodified'");
		$new_spouselastname = $newspouse->lastname;
		$new_spousefirstname = $newspouse->firstname;
		$new_spousepersonevent = $newspouse->personevent;
		$new_spousebirthdate = $newspouse->birthdate;
		$new_spousebirthplace = $newspouse->birthplace;
		$new_spousedeathdate = $newspouse->deathdate;
		$new_spousedeathplace = $newspouse->deathplace;
		$new_spousesex = $newspouse->sex;
		$new_spousefamc = $newspouse->famc;
		$new_spouseliving = $newspouse->living;
		$new_spousecause = $newspouse->cause;
		$new_spousemarrdate = $newfamily->marrdate;
		$new_spousemarrplace = $newfamily->marrplace;
		$new_spousehusborder = $newfamily->husborder;
		$new_spousewifeorder = $newfamily->wifeorder;
		if ($new_spousesex == "M") {
		$neworder = $new_spousehusborder;
		} else {
		$neworder == $new_spousewifeorder;
		}
		
//Get spouse(s) from TNG database	
	$family= $tngcontent->getFamilyById($new_spousefamilyid);
	$spouse = $tngcontent->getPerson($new_spouseid);	
		$spouseID = $spouse['personID'];
		$spouselastname = $spouse['lastname'];
		$spousefirstname = $spouse['firstname'];
		$spousebirthdate = $spouse['birthdate'];
		$spousebirthplace = $spouse['birthplace'];
		$spousedeathdate = $spouse['deathdate'];
		$spousedeathplace = $spouse['deathplace'];
		$spousesex = $spouse['sex'];
		$spousefamc = $spouse['famc'];
		$spouseliving = $spouse['living'];
		$spousefamilyID = $family['familyID'];
		$spousemarrdate = $family['marrdate'];
		$spousemarrplace = $family['marrplace'];
		$spousehusborder = $family['husborder'];
		$spousewifeorder = $family['wifeorder'];
//Spouse order
		if ($spousesex == "F") {
		$order = $new_spousehusborder;
		} else {
		$order = $new_spousewifeorder;
		}	

// Spouse - SpecialEvent
				if ($spouse['personID'] !== null)
				{
				$spouseRow = $tngcontent->getSpEvent($spouse['personID']);
				$spouse_spevent = $spouseRow['info'];
				} else {
				$spouse_spevent = "";
				}
//get Cause of Death for Spouse
				$spouseRow = $tngcontent->getCause($spouse['personID']);
				if ($spouseRow['eventtypeID'] == "0") {
					$spousecause = $spouseRow['cause'];
				} else {
				$spousecause = "";
				} 				
 	
		?>
		<table class="table-sheet1">
		
		<th style="background-color: #CACACA; width: 16%">Spouse Order <?php echo " ". $order; ?></th>
		<th style="background-color: #CACACA; width: 42%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 42%">FAMILY Database</th>
        <tr>
			<?php 
			if ($new_spousefamilyid !== $spousefamilyID) { 
			?>
			<td class="tdback"><?php echo "Family ID"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousefamilyid;?></td>
			<td class="tdsheet1"><?php echo $spousefamilyID; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_spouseid == $spouseID or $newspouse = '') { 
			?>
			<td><?php echo "Spouse ID (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_spouseid;?></td>
			<td class="tdsheet1"><?php echo $spouseID; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "Spouse ID"; ?></td>
			<td class="tdsheet1"><?php echo $new_spouseid;?></td>
			<td class="tdsheet1"><?php echo $spouseID; ?></td>
			<?php } ?>
		</tr>
		
		<tr>
			<?php 
			if ($new_spousefirstname == $spousefirstname OR $new_spousefirstname == '') { 
			?>
			<td><?php echo "Spouse Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousefirstname;?></td>
			<td class="tdsheet1"><?php echo $spousefirstname; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "Spouse Name"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousefirstname;?></td>
			<td class="tdsheet1"><?php echo $spousefirstname; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_spouselastname == $spouselastname OR $new_spouselastname == "") { 
			?>
			<td><?php echo "Last Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_spouselastname;?></td>
			<td class="tdsheet1"><?php echo $spouselastname; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "Last Name"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousefirstname;?></td>
			<td class="tdsheet1"><?php echo $spousefirstname; ?></td>
			<?php } ?>
		</tr>
		<tr>	  	
			<?php 
			if ($new_spousepersonevent !== $spouse_spevent AND $new_spousepersonevent != '') { 
			?>
			<td class="tdback"><?php echo $EventDisplay; ?></td>
			
			<td class="tdsheet1"><?php echo $new_spousepersonevent;?></td>
			<td class="tdsheet1"><?php echo $spouse_spevent; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_spousebirthdate !== $spousebirthdate and  $new_spousebirthdate != '') { 
			?>
			<td class="tdback"><?php echo "Birth Date"; ?></td>
			
			<td class="tdsheet1"><?php echo $new_spousebirthdate;?></td>
			<td class="tdsheet1"><?php echo $spousebirthdate; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($spousebirthplace !== $new_spousebirthplace AND $spousebirthplace != '') { 
			?>
			<td class="tdback"><?php echo "Place of Birth"; ?></td>
			
			<td class="tdsheet1"><?php echo $new_spousebirthplace;?></td>
			<td class="tdsheet1"><?php echo $spousebirthplace; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_spousedeathdate !== $spousedeathdate AND $new_spousedeathdate != '') { 
			?>
			<td class="tdback"><?php echo "Death Date"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousedeathdate;?></td>
			<td class="tdsheet1"><?php echo $spousedeathdate; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_spousedeathplace !== $spousedeathplace AND $new_spousedeathplace != '') { 
			?>
			<td class="tdback"><?php echo "Place of Death"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousedeathplace;?></td>
			<td class="tdsheet1"><?php echo $spousedeathplace; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_spousecause !== $spousecause AND $new_spousecause != '') { 
			?>
			<td class="tdback"><?php echo "Cause of Death"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousecause;?></td>
			<td class="tdsheet1"><?php echo $spousecause; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_spouseliving !== $spouseliving AND $new_spouseliving != '') { 
			?>
			<td class="tdback"><?php echo "Living"; ?></td>
			
			<td class="tdsheet1"><?php echo $new_spouseliving;?></td>
			<td class="tdsheet1"><?php echo $spouseliving; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_spousesex !== $spousesex AND $new_spousesex != '') { 
			?>
			<td class="tdback"><?php echo "Sex"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousesex;?></td>
			<td class="tdsheet1"><?php echo $spousesex; ?></td>
			<?php } ?>
		</tr>
        </tr>
		</table>
		<table class="table-sheet1">
		
		<th style="background-color: #CACACA; width: 16%">Marriage<br/>Spouse Order <?php echo " ". $order; ?></th>
		<th style="background-color: #CACACA; width: 42%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 42%">FAMILY Database</th>
        <tr>	
			<?php 
			if ($new_spousefamilyid == $spousefamilyID AND $new_spousefamilyid != '') { 
			?>
			<td><?php echo "Family (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousefamilyid;?></td>
			<td class="tdsheet1"><?php echo $spousefamilyID; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "Family ID"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousefamilyid;?></td>
			<td class="tdsheet1"><?php echo $spousefamilyID; ?></td>
			<?php } ?>
		</tr>
		
		<tr>	
			<?php 
			if ($new_spousemarrdate !== $spousemarrdate AND $new_spousemarrdate != '') { 
			?>
			<td class="tdback"><?php echo "Marriage Date"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousemarrdate;?></td>
			<td class="tdsheet1"><?php echo $spousemarrdate; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_spousemarrplace !== $spousemarrplace AND $new_spousemarrplace != '') { 
			?>
			<td class="tdback"><?php echo "Marriage Place"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousemarrplace;?></td>
			<td class="tdsheet1"><?php echo $spousemarrplace; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_spousehusborder !== $spousehusborder AND $new_spousehusborder != '') { 
			?>
			<td class="tdback"><?php echo "Husband Order"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousehusborder;?></td>
			<td class="tdsheet1"><?php echo $spousehusborder; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_spousewifeorder !== $spousewifeorder AND $new_spousewifeorder != '') { 
			?>
			<td class="tdback"><?php echo "Wife Order"; ?></td>
			<td class="tdsheet1"><?php echo $new_spousewifeorder;?></td>
			<td class="tdsheet1"><?php echo $spousewifeorder; ?></td>
			<?php } ?>
		</tr>
		 </table>

		<?php
// Get new Children		
		$newchildren = $wpdb->get_results( "SELECT * FROM wp_tng_children WHERE '$new_spousefamilyid' = familyID AND datemodified = '$datemodified'");
		foreach ($newchildren as $children):
		$new_childID = $children->personID;
		$new_childhaskids = $children->haskids;
		$new_childordernum = $children->ordernum;
		$new_childparentorder = $children->parentorder;
		$newchild = $wpdb->get_row( "SELECT * FROM wp_tng_people WHERE personid = '$new_childID' AND datemodified = '$datemodified'");
		$new_childlastname = $newchild->lastname;
		$new_childfirstname = $newchild->firstname;
		$new_childSpevent = $newchild->personevent;
		$new_childbirthdate = $newchild->birthdate;
		$new_childbirthplace = $newchild->birthplace;
		$new_childdeathdate = $newchild->deathdate;
		$new_childdeathplace = $newchild->deathplace;
		$new_childsex = $newchild->sex;
		$new_childfamc = $newchild->famc;
		$new_childliving = $newchild->living;
		$new_childcause = $newchild->cause;

//Get Child from TNG database	
	
	$childPerson = $tngcontent->getPerson ($new_childID);
		$childID = $childPerson['personID'];
		$childFirstName = $childPerson['firstname'];
		$childSurName = $childPerson['lastname'];
		$childbirthdate = $childPerson['birthdate'];
		$childbirthplace = $childPerson['birthplace'];
		$childdeathdate = $childPerson['deathdate'];
		$childdeathplace = $childPerson['deathplace'];
		$childliving = $childPerson['living'];
		$childsex = $childPerson['sex'];
	
$childdata = $tngcontent->getChildrow($childID);
       	$childhaskids = $childdata[0]['haskids'];
		$childordernum = $childdata[0]['ordernum'];
		$childparentorder = $childdata[0]['parentorder'];
			
//get Child Special Evnet
		if ($childPerson['personID'] !== "") {
				$childRow = $tngcontent->getSpEvent($childID);
		
				$childSpevent = $childRow['info'];
				} else {
				$childSpevent = " ";
				}
				//var_dump($childSpevent);	
//get Cause of Death for Child
				$childRow = $tngcontent->getCause($child['personID']);
				
				if ($childRow['eventtypeID'] == "0") {
					$childcause = $childRow['cause'];
				} else {
				$childcause = "";
				}
				
		?>		
 	<table class="table-sheet1">
		<th style="background-color: #CACACA; width: 16%">Child - <?php echo $new_childordernum; ?></th>
		<th style="background-color: #CACACA; width: 42%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 42%">FAMILY Database</th>
		<tr>
			<?php 
			if ($new_childordernum == $childordernum OR $new_childordernum == "") { 
			?>
			<td><?php echo "Child Order (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_childordernum;?></td>
			<td class="tdsheet1"><?php echo $childordernum; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "Child Order"; ?></td>
			<td class="tdsheet1"><?php echo $new_childordernum;?></td>
			<td class="tdsheet1"><?php echo $childordernum; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_childID !== $childID) { 
			?>
			<td class="tdback"><?php echo "Child ID"; ?></td>
			<td class="tdsheet1"><?php echo $new_childID;?></td>
			<td class="tdsheet1"><?php echo $childID; ?></td>
			<?php } ?>
		</tr>	
		<tr>
			<?php 
			if ($new_childfirstname == $childFirstName OR $new_childfirstname == "") { 
			?>
			<td><?php echo "Child Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_childfirstname;?></td>
			<td class="tdsheet1"><?php echo $childFirstName; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "Child Name"; ?></td>
			<td class="tdsheet1"><?php echo $new_childfirstname;?></td>
			<td class="tdsheet1"><?php echo $childFirstName; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_childlastname == $childSurName OR $new_childlastname == "") { 
			?>
			<td border="1"><?php echo "Last Name (OK)"; ?></td>
			<td class="tdsheet1"><?php echo $new_childlastname;?></td>
			<td class="tdsheet1"><?php echo $childSurName; ?></td>
			<?php } else { ?>
			<td class="tdback"><?php echo "Last Name"; ?></td>
			<td class="tdsheet1"><?php echo $new_childlastname; ?></td>
			<td class="tdsheet1"><?php echo $childSurName; ?></td>
			<?php } ?>
		</tr>
		<tr>	  	
			<?php 
			if ($new_childSpevent !== $childSpevent AND $new_childSpevent != '') { 
			?>
			<td class="tdback"><?php echo $EventDisplay; ?></td>
			
			<td class="tdsheet1"><?php echo $new_childSpevent;?></td>
			<td class="tdsheet1"><?php echo $childSpevent; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_childbirthdate !== $childbirthdate AND $new_childbirthdate != '') { 
			?>
			<td class="tdback"><?php echo "Birth Date"; ?></td>
			
			<td class="tdsheet1"><?php echo $new_childbirthdate;?></td>
			<td class="tdsheet1"><?php echo $childbirthdate; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_childbirthplace !== $new_childbirthplace AND $new_childbirthplace != '') { 
			?>
			<td class="tdback"><?php echo "Place of Birth"; ?></td>
			
			<td class="tdsheet1"><?php echo $new_childbirthplace;?></td>
			<td class="tdsheet1"><?php echo $spousebirthplace; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_childdeathdate !== $childdeathdate AND $new_childdeathdate != '') { 
			?>
			<td class="tdback"><?php echo "Death Date"; ?></td>
			<td class="tdsheet1"><?php echo $new_childdeathdate;?></td>
			<td class="tdsheet1"><?php echo $childdeathdate; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_childdeathplace !== $childdeathplace AND $new_childdeathplace != '') { 
			?>
			<td class="tdback"><?php echo "Place of Death"; ?></td>
			<td class="tdsheet1"><?php echo $new_childdeathplace;?></td>
			<td class="tdsheet1"><?php echo $childdeathplace; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_childcause !== $childcause AND $new_childcause != '') { 
			?>
			<td class="tdback"><?php echo "Cause of Death"; ?></td>
			<td class="tdsheet1"><?php echo $new_childcause;?></td>
			<td class="tdsheet1"><?php echo $childcause; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_childliving !== $childliving AND $new_childliving != '') { 
			?>
			<td class="tdback"><?php echo "Living"; ?></td>
			<td class="tdsheet1"><?php echo $new_childliving;?></td>
			<td class="tdsheet1"><?php echo $childliving; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_childsex  !== $childsex AND $new_childsex  != '') { 
			?>
			<td class="tdback"><?php echo "Sex"; ?></td>
			<td class="tdsheet1"><?php echo $new_childsex ;?></td>
			<td class="tdsheet1"><?php echo $childsex; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_childhaskids !== $childhaskids AND $new_childhaskids != '') { 
			?>
			<td class="tdback"><?php echo "Has KIds"; ?></td>
			<td class="tdsheet1"><?php echo $new_childhaskids;?></td>
			<td class="tdsheet1"><?php echo $childhaskids; ?></td>
			<?php } ?>
		</tr>
		<tr>	
			<?php 
			if ($new_childparentorder  !== $childparentorder AND $new_childparentorder != '') { 
			?>
			<td class="tdback"><?php echo "Parent Order"; ?></td>
			<td class="tdsheet1"><?php echo $new_childparentorder ;?></td>
			<td class="tdsheet1"><?php echo $childparentorder; ?></td>
			<?php } ?>
		</tr>
		</table>
		
		<?php
		endforeach
		?>
		<?php
		endforeach;
		if ($new_notepersonid !== null)
		{
		?>

<table class="table-sheet1">
		<th style="background-color: #CACACA; width: 16%;">Notes <?php echo " (ID=". $new_notepersonid. ")"; ?></th>
		<th style="background-color: #CACACA; width: 42%;">Changes Submitted for <?php echo " ". $Notes_for; ?></th>
		<th style="background-color: #CACACA; width: 42%;" >FAMILY Database</th>
        <tr>
			<?php 
			if ($new_notegeneral !== $note_general and $new_notegeneral !== null ) { 
			?>
			<td class="tdback"><?php echo "General Notes"; ?></td>
			<td class="tdsheet1"><textarea rows="3" cols="40"><?php echo $new_notegeneral;?></textarea></td>
			<td class="tdsheet1"><textarea rows="3" cols="40"><?php echo $note_general;?></textarea></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_notename !== $note_name and $new_notename != '' ) { 
			?>
			<td class="tdback"><?php echo "About Name"; ?></td>
			<td class="tdsheet1"><textarea rows="3" cols="40"><?php echo $new_notename;?></textarea></td>
			<td class="tdsheet1"><textarea rows="3" cols="40"><?php echo $note_name;?></textarea></td>
			<?php } ?>
		</tr>
		<tr>
			
			<?php 
			if ($new_notebirth !== $note_birth and $new_notebirth != '' ) { 
			?>
			<td class="tdback"><?php echo "About Birth"; ?></td>
			<td class="tdsheet1"><textarea rows="3" cols="40"><?php echo $new_notebirth;?></textarea></td>
			<td class="tdsheet1"><textarea rows="3" cols="40"><?php echo $note_birth;?></textarea></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_notedeath !== $note_death and $new_notedeath != '' ) { 
			?>
			<td class="tdback"><?php echo "About Death"; ?></td>
			<td class="tdsheet1"><textarea rows="3" cols="40"><?php echo $new_notedeath;?></textarea></td>
			<td class="tdsheet1"><textarea rows="3" cols="40"><?php echo $note_death;?></textarea></td>
			<?php } ?>
		</tr>
		<tr>
			<?php 
			if ($new_notefuneral !== $note_funeral and $new_notefuneral != '' ) { 
			?>
			<td class="tdback"><?php echo "Burial/Cremation"; ?></td>
			<td class="tdsheet1"><textarea rows="3" cols="40"><?php echo $new_notefuneral;?></textarea></td>
			<td class="tdsheet1"><textarea rows="3" cols="40"><?php echo $note_funeral;?></textarea></td>
			<?php } ?>
		</tr>
		</table>
						
	<?php
	}
//end of array
	endforeach;
	}
	
	//end of else
?>					
