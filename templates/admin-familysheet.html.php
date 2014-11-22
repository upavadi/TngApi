<?php
//NEW Page	
//User Submissions - This will be bundled with Thank-you oage
			$tngcontent = Upavadi_tngcontent::instance()->init();


//talk to wp tables
		global $wpdb;
//Check for User Submissions
			$usersubmissions = $wpdb->get_var( "SELECT count(*) FROM wp_tng_people where headpersonid = personid");
				if ($usersubmissions == "0") {
				echo '<h2>'. "No submissions". '</h2>';
				} else {
//Get Headperson IDs for Submissions and put them in an array
			$usercount = 1;
				$usersubmissionID = $wpdb->get_results( "SELECT * FROM wp_tng_people where headpersonid = personid");
			foreach ($usersubmissionID as $userID):
				$submissionID = $userID->id;
				$userentries[$usercount] = $submissionID;
				$usercount += 1;
				$usersubmitID = $userentries[$usercount];
			endforeach;	

//Get headpersonID from array and display submission				
			$usercount = 1;
			foreach ($userentries as $userentry):
				//$usercount += 1;
				$usersubmitID = $userentries[$usercount];
//get Submisions for each headperson
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
				$parents = $wpdb->get_row( "SELECT * FROM wp_tng_families where '$new_famc' = familyid AND datemodified = '$datemodified'");
				$new_father = $parents->husband;
				$new_mother = $parents->wife;
				$newparentsmarrdate = $parents->marrdate;
				$newparentsmarrplace = $parents->marrplace;
				$newparentshusborder = $parents->husborder;
				$newparentswifeorder = $parents->wifeorder;
/* New father and mother
		if ($new_famc == "") {
		$new_famc = "NewParents";
		$new_familyid = "NewParents";
		}
*/		


//Get Father
	$father = $wpdb->get_row( "SELECT * FROM wp_tng_people WHERE '$new_father' = personid AND datemodified = '$datemodified'");
		$newfather_lastname = $father->lastname;
		$newfather_firstname = $father->firstname;
		$newfather_personevent = $father->personevent;
		$newfather_birthdate = $father->birthdate;
		$newfather_birthplace = $father->birthplace;
		$newfather_deathdate = $father->deathdate;
		$newfather_deathplace = $father->deathplace;
		$newfather_sex = $father->sex;
		$newfather_famc = $father->famc;
		$newfather_living = $father->living;
		$newfather_cause = $father->cause;
	
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
//get Special Event
	if ($person['personID'] !== null)
		{
		$personRow = $tngcontent->getSpEvent($person['personID']);
		$SpEvent = $personRow['info'];
		} else {
		$SpEvent = '';
	}
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
				
				//$parents = '';
				$parents = $tngcontent->getFamilyById($person['famc']);

				if ($person['famc'] !== '' and $parents['wife'] !== '') {
					$mother = $tngcontent->getPerson($parents['wife']);
				} 
				if ($person['famc'] !== ''and $parents['husband'] !== '') {
					$father = $tngcontent->getPerson($parents['husband']);
				}
				$personfamc = $person['famc'];
/* Set Person famc for new parents
				if ($person['famc'] == '') {
					$personfamc = "NewParents";
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
					$xnote_generalID = '';
					$xnote_nameID = '';
					$xnote_birthID = '';
					$xnote_deathID = '';
					$xnote_funeralID = '';
					$note_general = '';
					$note_name = '';
					$note_name = '';
					$note_birth = '';
					$note_death = '';
					$note_funeral = '';					
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
	?>
	
	<B>Changes Submitted by <?php echo $UserName->display_name. " *** Submission " .$usercount ." of " .$usersubmissions ." (ID = ". $userentry. " )"; ?></B><br/>
	<b>for the Family of <?php echo $firstname. " ". $lastname;?> </b>
    <table width="100%" border="1">
       <tbody>
		<th style="background-color: #CACACA; width: 16%";>Head Person</th>
		<th style="background-color: #CACACA; padding-left: 1%; width: 4%"> &#10003 </th>
		<th style="background-color: #CACACA; width: 40%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 40%">FAMILY Database</th>
        <tr>
			<?php 
			if ($new_personid == $personID) { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"  width="10%"><?php echo "Person ID"; ?></td>
			<td><input type="checkbox" name="personid_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newpersonid" value="<?php echo $new_personid;?>" /></td>
			<td class="tdsheet1"><?php echo $personID; ?></td>
		</tr>
		<tr>
			<?php 
			if ($new_firstname == $firstname OR $new_firstname == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "First Name"; ?></td>
			<td><input type="checkbox" name="firstname_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newpersonfirstname" value="<?php echo $new_firstname;?>" /></td>
			<td class="tdsheet1"><?php echo $firstname; ?></td>
		</tr>
		<tr>
			<?php 
			if ($new_surname == $surname OR $new_surname == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Surname"; ?></td>
			<td><input type="checkbox" name="personsurname_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newpersonsurname" value="<?php echo $new_lastname;?>" /></td>
			<td class="tdsheet1"><?php echo $lastname; ?></td>
		</tr>
		<?php 
		if ($EventDisplay !== null)
		{
		?>		
		<tr>
			<?php 
			if ($new_personevent == $SpEvent OR $new_personevent == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo $EventDisplay; ?></td>
			<td><input type="checkbox" name="personSpEvent_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newpersonSpevent" value="<?php echo $new_personevent;?>" /></td>
			<td class="tdsheet1"><?php echo $SpEvent; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<?php 
			if ($new_birthdate == $birthdate OR $new_birthdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Born"; ?></td>
			<td><input type="checkbox" name="personbirthdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newpersonbirthdate" value="<?php echo $new_birthdate;?>" /></td>
			<td class="tdsheet1"><?php echo $birthdate; ?></td>
		</tr>
		<tr>
			<?php 
			if ($new_birthplace == $birthplace OR $new_birthplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Place of Birth"; ?></td>
			<td><input type="checkbox" name="personbirthplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newpersonbirthplace" value="<?php echo $new_birthplace;?>" /></td>
			<td class="tdsheet1"><?php echo $birthplace; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_deathdate == $deathdate OR $new_deathdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Died"; ?></td>
			<td><input type="checkbox" name="persondeathdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newpersondeathdate" value="<?php echo $new_deathdate;?>" /></td>
			<td class="tdsheet1"><?php echo $deathdate; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_deathplace == $deathplace OR $new_deathplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Place of Death"; ?></td>
			<td><input type="checkbox" name="persondeathplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newpersondeathplace" value="<?php echo $new_deathplace;?>" /></td>
			<td class="tdsheet1"><?php echo $deathplace; ?></td>
		</tr>
		<tr>
		<?php 
			if ($new_cause == $cause_of_death OR $new_cause == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Cause of Death"; ?></td>
			<td><input type="checkbox" name="personcause_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newperson_cause" value="<?php echo $new_cause;?>" /></td>
			<td class="tdsheet1"><?php echo $cause_of_death; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_living == $living OR $new_living == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Living"; ?></td>
			<td><input type="checkbox" name="personliving_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newpersonliving" value="<?php echo $new_living;?>" /></td>
			<td class="tdsheet1"><?php echo $living; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_sex == $sex OR $new_sex == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Sex"; ?></td>
			<td><input type="checkbox" name="personsex_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newpersonsex" value="<?php echo $new_sex;?>" /></td>
			<td class="tdsheet1"><?php echo $sex; ?></td>
		</tr>
		</table>		
	
    <table width="100%" border="1">
		<th style="background-color: #CACACA; width: 16%">Father</th>
		<th style="background-color: #CACACA; padding-left: 1%; width: 4%"> &#10003 </th>
		<th style="background-color: #CACACA; width: 40%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 40%">TNG Database</th>
		<tr>
			<?php 
			if ($new_father == $fatherID or $new_father == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Father ID"; ?></td>
			<td><input type="checkbox" name="newfatherid_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newfatherid" value="<?php echo $new_father;?>" /></td>
			<td class="tdsheet1"><?php echo $fatherID; ?></td>
		</tr>	
		<tr>
			<?php 
			if ($newfather_firstname == $fatherfirstname OR $newfather_firstname == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Father Name"; ?></td>
			<td><input type="checkbox" name="fatherfirstname_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newfatherfirstname" value="<?php echo $newfather_firstname;?>" /></td>
			<td class="tdsheet1"><?php echo $fatherfirstname; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($newfather_lastname == $fathersurname OR $newfather_lastname == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Father Surname"; ?></td>
			<td><input type="checkbox" name="fatherlastname_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newfatherlastname" value="<?php echo $newfather_lastname;?>" /></td>
			<td class="tdsheet1"><?php echo $fathersurname; ?></td>
		</tr>
		<?php 
		if ($EventDisplay !== null)
		{
		?>		
		<tr>		
			<?php 
			if ($newfather_personevent == $father_SpEvent OR $newfather_personevent == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo $EventDisplay; ?></td>
			<td><input type="checkbox" name="fatherSpEvent_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newfatherSpEvnet" value="<?php echo $newfather_personevent;?>" /></td>
			<td class="tdsheet1"><?php echo $father_SpEvent; ?></td>
		</tr>
		<?php } ?>
		<tr>	
			<?php 
			if ($newfather_birthdate == $fatherbirthdate OR $newfather_birthdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Birth Date"; ?></td>
			<td><input type="checkbox" name="fatherbirthdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newfatherbirthdate" value="<?php echo $newfather_birthdate;?>" /></td>
			<td class="tdsheet1"><?php echo $fatherbirthdate; ?></td>
		</tr>
		<tr>
			<?php 
			if ($newfather_birthplace == $fatherbirthplace OR $newfather_birthplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Place of Birth"; ?></td>
			<td><input type="checkbox" name="fatherbirthplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newfatherbirthplace" value="<?php echo $newfather_birthplace;?>" /></td>
			<td class="tdsheet1"><?php echo $fatherbirthplace; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($newfather_deathdate == $fatherdeathdate OR $newfather_deathdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Death Date"; ?></td>
			<td><input type="checkbox" name="fatherdeathdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newfatherdeathdate" value="<?php echo $newfather_deathdate;?>" /></td>
			<td class="tdsheet1"><?php echo $fatherdeathdate; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($newfather_deathplace == $fatherdeathplace OR $newfather_deathplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Place of Death"; ?></td>
			<td><input type="checkbox" name="fatherdeathplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newfatherdeathplace" value="<?php echo $newfather_deathplace;?>" /></td>
			<td class="tdsheet1"><?php echo $fatherdeathplace; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($newfather_cause == $fathercause OR $newfather_cause == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Cause of Death"; ?></td>
			<td><input type="checkbox" name="fathercause_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newnewfather_cause" value="<?php echo $newfather_cause;?>" /></td>
			<td class="tdsheet1"><?php echo $fathercause; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($newfather_living == $fatherliving OR $newfather_living == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Living"; ?></td>
			<td><input type="checkbox" name="fatherliving_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newfatherliving" value="<?php echo $newfather_living;?>" /></td>
			<td class="tdsheet1"><?php echo $fatherliving; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($newfather_sex == $fathersex OR $newfather_sex == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Sex"; ?></td>
			<td><input type="checkbox" name="fathersex_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newfathersex" value="<?php echo $newfather_sex;?>" /></td>
			<td class="tdsheet1"><?php echo $fathersex; ?></td>
		</tr>
        </table>		
    <table width="100%" border="1">
		<th style="background-color: #CACACA; width: 16%">Mother</th>
		<th style="background-color: #CACACA; padding-left: 1%; width: 4%"> &#10003 </th>
		<th style="background-color: #CACACA; width: 40%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 40%">TNG Database</th>
		<tr>
			<?php 
			if ($new_mother == $motherID or $new_mother == "" ) { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Mother ID"; ?></td>
			<td><input type="checkbox" name="newmotherid_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newmotherid" value="<?php echo $new_mother;?>" /></td>
			<td class="tdsheet1"><?php echo $motherID; ?></td>
		</tr>
		<tr>
			<?php 
			if ($newmother_firstname == $motherfirstname OR $newmother_firstname == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			
			<td class="tdback"><?php echo "Mother Name"; ?></td>
			<td><input type="checkbox" name="motherfirstname_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newmotherfirstname" value="<?php echo $newmother_firstname;?>" /></td>
			<td class="tdsheet1"><?php echo $motherfirstname; ?></td>
		</tr>
		<tr>
			<?php 
			if ($newmother_lastname == $mothersurname OR $newmother_lastname == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Mother Surname"; ?></td>
			<td><input type="checkbox" name="motherlastname_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newmotherlastname" value="<?php echo $newmother_lastname;?>" /></td>
			<td class="tdsheet1"><?php echo $mothersurname; ?></td>
		</tr>
		<?php 
		if ($EventDisplay !== null)
		{
		?>		
		<tr>		
			<?php 
			if ($newmother_personevent == $mother_SpEvent OR $newmother_personevent == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo $EventDisplay; ?></td>
			<td><input type="checkbox" name="motherSpEvent_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newmotherSpEvnet" value="<?php echo $newmother_personevent;?>" /></td>
			<td class="tdsheet1"><?php echo $mother_SpEvent; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<?php 
			if ($newmother_birthdate == $motherbirthdate OR $newmother_birthdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>	
			<td class="tdback"><?php echo "Birth Date"; ?></td>
			<td><input type="checkbox" name="motherbirthdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newmotherbirthdate" value="<?php echo $newmother_birthdate;?>" /></td>
			<td class="tdsheet1"><?php echo $motherbirthdate; ?></td>
		</tr>
		<tr>
			<?php 
			if ($newmother_birthplace == $motherbirthplace OR $newmother_birthplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Place of Birth"; ?></td>
			<td><input type="checkbox" name="motherbirthplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newmotherbirthplace" value="<?php echo $newmother_birthplace;?>" /></td>
			<td class="tdsheet1"><?php echo $motherbirthplace; ?></td>
		</tr>
		<tr>
			<?php 
			if ($newmother_deathdate == $motherdeathdate OR $newmother_deathdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Death Date"; ?></td>
			<td><input type="checkbox" name="motherdeathdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newmotherdeathdate" value="<?php echo $newmother_deathdate;?>" /></td>
			<td class="tdsheet1"><?php echo $motherdeathdate; ?></td>
		</tr>
		<tr>
			<?php 
			if ($newmother_deathplace == $motherdeathplace OR $newmother_deathplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Place of Death"; ?></td>
			<td><input type="checkbox" name="motherdeathplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newmotherdeathplace" value="<?php echo $newmother_deathplace;?>" /></td>
			<td class="tdsheet1"><?php echo $motherdeathplace; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($newmother_cause == $mothercause OR $newmother_cause == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Cause of Death"; ?></td>
			<td><input type="checkbox" name="mothercause_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newmother_cause" value="<?php echo $newmother_cause;?>" /></td>
			<td class="tdsheet1"><?php echo $mothercause; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($newmother_living == $motherliving OR $newmother_living == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Living"; ?></td>
			<td><input type="checkbox" name="motherliving_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newmotherliving" value="<?php echo $newmother_living;?>" /></td>
			<td class="tdsheet1"><?php echo $motherliving; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($newmother_sex == $mothersex OR $newmother_sex == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Sex"; ?></td>
			<td><input type="checkbox" name="mothersex_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newmothersex" value="<?php echo $newmother_sex;?>" /></td>
			<td class="tdsheet1"><?php echo $mothersex; ?></td>
		</tr>	
        </table>		
    </table>
		
		 
	<table width="100%" border="1">
		<th style="background-color: #CACACA; width: 16%">Parents</th>
		<th style="background-color: #CACACA; padding-left: 1%; width: 4%"> &#10003 </th>
		<th style="background-color: #CACACA; width: 40%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 40%">TNG Database</th>
		<tr>
			<?php 
			if ($new_famc == $personfamc or $new_famc == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "ParentsID (famc)"; ?></td>
			<td><input type="checkbox" name="newfamc_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newparentsid" value="<?php echo $new_famc;?>" /></td>
			<td class="tdsheet1"><?php echo $personfamc; ?></td>
		</tr>
		<tr>
			<?php 
			if ($newparentsmarrdate == $parentsmarrdate OR $newparentsmarrdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Marriage date"; ?></td>
			<td><input type="checkbox" name="parentsmarrdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newparentsmarrdate" value="<?php echo $newparentsmarrdate;?>" /></td>
			<td class="tdsheet1"><?php echo $parentsmarrdate; ?></td>
		</tr>
		<tr>
			<?php 
			if ($newparentsmarrplace == $parentsmarrplace OR $newparentsmarrplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Marriage Place"; ?></td>
			<td><input type="checkbox" name="parentsmarrplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newparentsmarrplace" value="<?php echo $newparentsmarrplace;?>" /></td>
			<td class="tdsheet1"><?php echo $parentsmarrplace; ?></td>
		</tr>
		<tr>
			<?php 
			if ($newparentshusborder == $parentshusborder OR $newparentshusborder == '') { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Husband Order"; ?></td>
			<td><input type="checkbox" name="parentshusborder_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newparentshusborder" value="<?php echo $newparentshusborder;?>" /></td>
			<td class="tdsheet1"><?php echo $parentshusborder; ?></td>
		</tr>
		<tr>
			<?php 
			if ($newparentswifeorder == $parentswifeorder OR $newparentswifeorder == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Wife Order"; ?></td>
			<td><input type="checkbox" name="parentswifworder_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newparentswifeorder" value="<?php echo $newparentswifeorder;?>" /></td>
			<td class="tdsheet1"><?php echo $parentswifeorder; ?></td>
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
		$order = $new_spousehusborder;
		} else {
		$order == $new_spousewifeorder;
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
// Spouse - SpecialEvent
				if ($spouse['personID'] !== null)
				{
				$spouseRow = $tngcontent->getSpEvent($spouse['personID']);
				$spouse_spevent = $spouseRow['info'];
				}
//get Cause of Death for Spouse
				$spouseRow = $tngcontent->getCause($spouse['personID']);
				if ($spouseRow['eventtypeID'] == "0") {
					$spousecause = $spouseRow['cause'];
				} else {
				$spousecause = "";
				} 				
 		
		?>
		<table width="100%" border="1">
		
		<th style="background-color: #CACACA; width: 16%">Spouse Order <?php echo " ". $order; ?></th>
		<th style="background-color: #CACACA; padding-left: 1%; width: 4%"> &#10003 </th>
		<th style="background-color: #CACACA; width: 40%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 40%">TNG Database</th>
        <tr>
			<?php 
			if ($new_spousefamilyid == $spousefamilyID) { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Family ID"; ?></td>
			<td><input type="checkbox" name="newspousefamily_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousefamilyid" value="<?php echo $new_spousefamilyid;?>" /></td>
			<td class="tdsheet1"><?php echo $spousefamilyID; ?></td>
		</tr>
		<tr>
			<?php 
			if ($new_spouseid == $spouseID) { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Spouse ID"; ?></td>
			<td><input type="checkbox" name="newspouseid_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspouseid" value="<?php echo $new_spouseid;?>" /></td>
			<td class="tdsheet1"><?php echo $spouseID; ?></td>
		</tr>
		
		<tr>
			<?php 
			if ($new_spousefirstname == $spousefirstname OR $new_spousefirstname == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"  ><?php echo "First Name"; ?></td>
			<td><input type="checkbox" name="spousefirstname_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousefirstname" value="<?php echo $new_spousefirstname;?>" /></td>
			<td class="tdsheet1"><?php echo $spousefirstname; ?></td>
		</tr>
		<tr>
			<?php 
			if ($new_spouselastname == $spouselastname OR $new_spouselastname == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Surname"; ?></td>
			<td><input type="checkbox" name="spousesurname_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousesurname" value="<?php echo $new_spouselastname;?>" /></td>
			<td class="tdsheet1"><?php echo $spouselastname; ?></td>
		</tr>
		<?php 
		if ($EventDisplay !== null)
		{
		?>		
		<tr>		
			<?php 
			if ($new_spousepersonevent == $spouse_spevent OR $new_spousepersonevent == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo $EventDisplay; ?></td>
			<td><input type="checkbox" name="spouseSpEvent_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspouseSpEvnet" value="<?php echo $new_spousepersonevent;?>" /></td>
			<td class="tdsheet1"><?php echo $spouse_spevent; ?></td>
		</tr>
		<?php } ?>
		<tr>	
			<?php 
			if ($new_spousebirthdate == $spousebirthdate OR $new_spousebirthdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Birth Date"; ?></td>
			<td><input type="checkbox" name="spousebirthdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousebirthdate" value="<?php echo $new_spousebirthdate;?>" /></td>
			<td class="tdsheet1"><?php echo $spousebirthdate; ?></td>
		</tr>
		<tr>
			<?php 
			if ($new_spousebirthplace == $spousebirthplace OR $new_spousebirthplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Place of Birth"; ?></td>
			<td><input type="checkbox" name="spousebirthplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousebirthplace" value="<?php echo $new_spousebirthplace; ?>" /></td>
			<td class="tdsheet1"><?php echo $spousebirthplace; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_spousedeathdate == $spousedeathdate OR $new_spousedeathdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Death Date"; ?></td>
			<td><input type="checkbox" name="spousedeathdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousedeathdate" value="<?php echo $new_spousedeathdate;?>" /></td>
			<td class="tdsheet1"><?php echo $spousedeathdate; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_spousedeathplace == $spousedeathplace OR $new_spousedeathplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Place of Death"; ?></td>
			<td><input type="checkbox" name="spousedeathplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousedeathplace" value="<?php echo $new_spousedeathplace;?>" /></td>
			<td class="tdsheet1"><?php echo $spousedeathplace; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_spousecause == $spousecause OR $new_spousecause == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Cause of Death"; ?></td>
			<td><input type="checkbox" name="spousecause_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousecause" value="<?php echo $new_spousecause;?>" /></td>
			<td class="tdsheet1"><?php echo $spousecause; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_spouseliving == $spouseliving OR $new_spouseliving == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Living"; ?></td>
			<td><input type="checkbox" name="spouseliving_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspouseliving" value="<?php echo $new_spouseliving;?>" /></td>
			<td class="tdsheet1"><?php echo $spouseliving; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_spousesex == $spousesex OR $new_spousesex == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Sex"; ?></td>
			<td><input type="checkbox" name="spousesex_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousesex" value="<?php echo $new_spousesex;?>" /></td>
			<td class="tdsheet1"><?php echo $spousesex; ?></td>
		</tr>
		</table>
		<table width="100%" border="1">
		
		<th style="background-color: #CACACA; width: 16%">Marriage<br/>Spouse Order <?php echo " ". $order; ?></th>
		<th style="background-color: #CACACA; padding-left: 1%; width: 4%"> &#10003 </th>
		<th style="background-color: #CACACA; width: 40%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 40%">TNG Database</th>
        <tr>	
			<?php 
			if ($new_spousefamilyid == $spousefamilyID OR $new_spousefamilyid == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Family ID"; ?></td>
			<td><input type="checkbox" name="spousefamilyid_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousefamilyid" value="<?php echo $new_spousefamilyid;?>" /></td>
			<td class="tdsheet1"><?php echo $spousefamilyID; ?></td>
		</tr>
		
		<tr>	
			<?php 
			if ($new_spousemarrdate == $spousemarrdate OR $new_spousemarrdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Marriage Date"; ?></td>
			<td><input type="checkbox" name="spousemarrdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousemarrdate" value="<?php echo $new_spousemarrdate;?>" /></td>
			<td class="tdsheet1"><?php echo $spousemarrdate; ?></td>
		</tr>
		<tr>
			<?php 
			if ($new_spousemarrplace == $spousemarrplace OR $new_spousemarrplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Marriage Place"; ?></td>
			<td><input type="checkbox" name="spousemarrplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousemarrplace" value="<?php echo $new_spousemarrplace;?>" /></td>
			<td class="tdsheet1"><?php echo $spousemarrplace; ?></td>
		</tr>
		<tr>
			<?php 
			if ($new_spousehusborder == $spousehusborder OR $new_spousehusborder == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Husband Order"; ?></td>
			<td><input type="checkbox" name="spousehusborder_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousehusborder" value="<?php echo $new_spousehusborder;?>" /></td>
			<td class="tdsheet1"><?php echo $spousehusborder; ?></td>
		</tr>
		<tr>
			<?php 
			if ($new_spousewifeorder == $spousewifeorder OR $new_spousewifeorder == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Wife Order"; ?></td>
			<td><input type="checkbox" name="spousewifworder_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newspousewifeorder" value="<?php echo $new_spousewifeorder;?>" /></td>
			<td class="tdsheet1"><?php echo $spousewifeorder; ?></td>
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
			if ($childID !== null)
			{
			$childRow = $tngcontent->getSpEvent($childID);
			$childSpevent = $childRow['info'];
			} else {$
			$childSpevent = '';
			}
			
//get Cause of Death for Child
				$childRow = $tngcontent->getCause($child['personID']);
				
				if ($childRow['eventtypeID'] == "0") {
					$childcause = $childRow['cause'];
				} else {
				$childcause = "";
				} 
		?>		
 	<table width="100%" border="1">
		<th style="background-color: #CACACA; width: 16%">Child - <?php echo $new_childordernum; ?></th>
		<th style="background-color: #CACACA; padding-left: 1%; width: 4%"> &#10003 </th>
		<th style="background-color: #CACACA; width: 40%">Changes Submitted</th>
		<th style="background-color: #CACACA; width: 40%">TNG Database</th>
		<tr>
			<?php 
			if ($new_childordernum == $childordernum OR $new_childordernum == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Child Order"; ?></td>
			<td><input type="checkbox" name="childorder_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchildordernum" value="<?php echo $new_childordernum;?>" /></td>
			<td class="tdsheet1"><?php echo $childordernum; ?></td>
		</tr>
		<tr>
			<?php 
			if ($new_childID == $childID) { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Child ID"; ?></td>
			<td><input type="checkbox" name="newchildid_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchildid" value="<?php echo $new_childID;?>" /></td>
			<td class="tdsheet1"><?php echo $childID; ?></td>
		</tr>	
		<tr>
			<?php 
			if ($new_childfirstname == $childFirstName OR $new_childfirstname == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Child Name"; ?></td>
			<td><input type="checkbox" name="childfirstname_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchildfirstname" value="<?php echo $new_childfirstname;?>" /></td>
			<td class="tdsheet1"><?php echo $childFirstName; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_childlastname == $childSurName OR $new_childlastname == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Child Surname"; ?></td>
			<td><input type="checkbox" name="childlastname_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchildlastname" value="<?php echo $new_childlastname;?>" /></td>
			<td class="tdsheet1"><?php echo $childSurName; ?></td>
		</tr>
		<?php 
		if ($EventDisplay !== null)
		{
		?>		
		<tr>		
			<?php 
			if ($new_childSpevent == $childSpevent OR $new_childSpevent == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo $EventDisplay; ?></td>
			<td><input type="checkbox" name="childSpEvent_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchildSpEvnet" value="<?php echo $new_childSpevent;?>" /></td>
			<td class="tdsheet1"><?php echo $childSpevent; ?></td>
		</tr>
		<?php } ?>
		<tr>	
			<?php 
			if ($new_childbirthdate == $childbirthdate OR $new_childbirthdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Birth Date"; ?></td>
			<td><input type="checkbox" name="childbirthdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchildbirthdate" value="<?php echo $new_childbirthdate;?>" /></td>
			<td class="tdsheet1"><?php echo $childbirthdate; ?></td>
		</tr>
		<tr>
			<?php 
			if ($new_childbirthplace == $childbirthplace OR $new_childbirthplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Place of Birth"; ?></td>
			<td><input type="checkbox" name="childbirthplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchildbirthplace" value="<?php echo $new_childbirthplace;?>" /></td>
			<td class="tdsheet1"><?php echo $childbirthplace; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_childdeathdate == $childdeathdate OR $new_childdeathdate == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Death Date"; ?></td>
			<td><input type="checkbox" name="childdeathdate_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchilddeathdate" value="<?php echo $new_childdeathdate;?>" /></td>
			<td class="tdsheet1"><?php echo $childdeathdate; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_childdeathplace == $childdeathplace OR $new_childdeathplace == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Place of Death"; ?></td>
			<td><input type="checkbox" name="childdeathplace_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchilddeathplace" value="<?php echo $new_childdeathplace;?>" /></td>
			<td class="tdsheet1"><?php echo $childdeathplace; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_childcause == $childcause OR $new_childcause == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Cause of Death"; ?></td>
			<td><input type="checkbox" name="childcause_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchild_cause" value="<?php echo $new_childcause;?>" /></td>
			<td class="tdsheet1"><?php echo $childcause; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_childliving == $childliving OR $new_childliving == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Living"; ?></td>
			<td><input type="checkbox" name="childliving_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchildliving" value="<?php echo $new_childliving;?>" /></td>
			<td class="tdsheet1"><?php echo $childliving; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_childsex == $childsex OR $new_childsex == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Sex"; ?></td>
			<td><input type="checkbox" name="childsex_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchildsex" value="<?php echo $new_childsex;?>" /></td>
			<td class="tdsheet1"><?php echo $childsex; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_childhaskids == $childhaskids OR $new_childhaskids == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Has Kids"; ?></td>
			<td><input type="checkbox" name="childhaskids_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchildhaskids" value="<?php echo $new_childhaskids;?>" /></td>
			<td class="tdsheet1"><?php echo $childhaskids; ?></td>
		</tr>
		<tr>	
			<?php 
			if ($new_childparentorder == $childparentorder OR $new_childparentorder == "") { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Parent Order"; ?></td>
			<td><input type="checkbox" name="childparentorder_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newchildparentorder" value="<?php echo $new_childparentorder;?>" /></td>
			<td class="tdsheet1"><?php echo $childparentorder; ?></td>
		</tr>
        </table>
		
		<?php
		endforeach;
		
		endforeach;
		
		if ($new_notepersonid !== null)
		{
		?>
<table width="100%" border="1">
		<th style="background-color: #CACACA; width: 16%">Notes <?php echo " (ID=". $new_notepersonid. ")"; ?></th>
		<th style="background-color: #CACACA; padding-left: 1%; width: 4%"> &#10003 </th>
		<th style="background-color: #CACACA; width: 40%;">Changes Submitted for <?php echo " ". $Notes_for; ?></th>
		<th style="background-color: #CACACA; width: 40%" >TNG Database</th>
        <tr>
			<?php 
			if ($new_xnote_generalID == $xnote_generalID or $new_xnote_generalID == '' ) { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Notes ID"; ?></td>
			<td><input type="checkbox" name="newnotesgeneralID_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newnotegeneralID" value="<?php echo $new_xnote_generalID;?>"</td>
			<td class="tdsheet1"><?php echo $xnote_generalID;?></td>
		</tr>
		<tr>
			<?php 
			if ($new_notegeneral == $note_general or $new_notegeneral == '') { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "General Notes"; ?></td>
			<td><input type="checkbox" name="newnotesgeneral_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><textarea name="newnotegeneral" rows="3" cols="40"><?php echo $new_notegeneral;?></textarea></td>
			<td class="tdsheet1"><textarea name="notegeneral" rows="3" cols="40"><?php echo $note_general;?></textarea></td>
		</tr>
		<tr>
			<?php 
			if ($new_xnote_nameID == $xnote_nameID or $new_xnote_nameID == '') { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Names Note ID"; ?></td>
			<td><input type="checkbox" name="newnotesnameID_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newnotesnameID" value="<?php echo $new_xnote_nameID;?>"</td>
			<td class="tdsheet1"><?php echo $xnote_nameID;?></td>
		</tr>
		<tr>
			
			<?php 
			if ($new_notename == $note_name or $new_notename == '') { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "About Name"; ?></td>
			<td><input type="checkbox" name="newnotename_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><textarea name="newnotename" rows="3" cols="40"><?php echo $new_notename;?></textarea></td>
			<td class="tdsheet1"><textarea name="notename" rows="3" cols="40"><?php echo $note_name;?></textarea></td>
		</tr>
		<tr>
			<?php 
			if ($new_xnote_birthID == $xnote_birthID or $new_xnote_birthID == '') { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Birth Note ID"; ?></td>
			<td><input type="checkbox" name="newnotebirthID_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newnotesbirthID" value="<?php echo $new_xnote_birthID;?>"</td>
			<td class="tdsheet1"><?php echo $xnote_birthID;?></td>
		</tr>
		<tr>
			
			<?php 
			if ($new_notebirth == $note_birth or $new_notebirth == '') { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "About Birth"; ?></td>
			<td><input type="checkbox" name="newnotebirth_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><textarea name="newnotebirth" rows="3" cols="40"><?php echo $new_notebirth;?></textarea></td>
			<td class="tdsheet1"><textarea name="notebirth" rows="3" cols="40"><?php echo $note_birth;?></textarea></td>
		</tr>
		<tr>
			<?php 
			if ($new_xnote_deathID == $xnote_deathID or $new_xnote_deathID == '') { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Death Note ID"; ?></td>
			<td><input type="checkbox" name="newnotedeathID_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newnotesdeathID" value="<?php echo $new_xnote_deathID;?>"</td>
			<td class="tdsheet1"><?php echo $xnote_deathID;?></td>
		</tr>
		<tr>
			
			<?php 
			if ($new_notedeath == $note_death or $new_notedeath == '') { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "About Death"; ?></td>
			<td><input type="checkbox" name="newnotedeath_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><textarea name="newnotedeath" rows="3" cols="40"><?php echo $new_notedeath;?></textarea></td>
			<td class="tdsheet1"><textarea name="notedeath" rows="3" cols="40"><?php echo $note_death;?></textarea></td>
		</tr>
		<tr>
			<?php 
			if ($new_xnote_funeralID == $xnote_funeralID or $new_xnote_funeralID == '') { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Funeral/Burial Note ID"; ?></td>
			<td><input type="checkbox" name="newnotefuneralID_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><input type="text" name="newnotesfuneralID" value="<?php echo $new_xnote_funeralID;?>"</td>
			<td class="tdsheet1"><?php echo $xnote_funeralID;?></td>
		</tr>
		<tr>
			
			<?php 
			if ($new_notefuneral == $note_death or $new_notefuneral == '') { 
			$checked = ""; 
			} else { 
			$checked = "checked";
			}
			?>
			<td class="tdback"><?php echo "Burial/Cremation"; ?></td>
			<td><input type="checkbox" name="newnotefuneral_save" value="1" <?php echo $checked;?> /></td>
			<td class="tdsheet1"><textarea name="newnotefuneral" rows="3" cols="40"><?php echo $new_notefuneral;?></textarea></td>
			<td class="tdsheet1"><textarea name="notefuneral" rows="3" cols="40"><?php echo $note_funeral;?></textarea></td>
		</tr>
		</table>
		<?php } ?>
<!------------------------------------------------------------------------------>
		<?php
		$usercount += 1;
		endforeach;
		}
		?>