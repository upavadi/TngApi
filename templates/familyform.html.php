<!-- Family Add Page Modified for BootStrap March 2016-->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upavadi Update Family</title>
</head>
<?php
	$tngcontent = Upavadi_tngcontent::instance()->init();
 //get and hold current user
	$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
	$person = $tngcontent->getPerson($currentperson);
	$currentuser = ($person['firstname']. " ". $person['lastname']);
	$currentuserLogin = wp_get_current_user();
	$UserLogin = $currentuserLogin->user_login;
				//echo $UserLogin;	
//get and hold current user
	$person = $tngcontent->getPerson($personId, $tree);
	$personID = $person['personID'];
	$person_gedcom = $person['gedcom'];
	$person_birthdate = $person['birthdate'];
	$person_birthdatetr = ($person['birthdatetr']);
	$person_birthplace = $person['birthplace'];
	$person_deathdate = $person['deathdate'];
	$person_deathdatetr = ($person['deathdatetr']);
	$person_deathplace = $person['deathplace'];
	$person_name = $person['firstname'];
	$person_surname = $person['lastname'];
	$person_sex = $person['sex'];
	$person_famc = $person['famc'];
	$person_living = $person['living'];
//get Person Special Event
	$personRow = $tngcontent->getSpEvent($person['personID'], $tree);
	$person_SpEvent = $personRow['info'];
	$person_SpEventId = $personRow['eventID'];
//get Description of Event type
	$EventRow = $tngcontent->getEventDisplay($event['display']);	
	$EventDisplay = $EventRow['display'];
	$EventTypeID = $EventRow['eventtypeID'];
//get month of the events
	$currentmonth = date("m");
	if ($birthdatetr == '0000-00-00') {
	$birthmonth = null;
	} else {
	$birthmonth = substr($birthdatetr, -5, 2);
	}
	If ($currentmonth == $birthmonth) { $bornClass = 'born-highlight'; 
	} else { $bornClass="";
	}
	if ($deathdatetr == "0000-00-00") {
	$deathmonth = null;
	} else {
	$deathmonth = substr($deathdatetr, -5, 2);
	}
	If ($currentmonth == $birthmonth) { $bornClass = 'born-highlight';
	} else { $bornClass="";
	}			
//get Cause of Death for person
     $newEventId = 0;
	$personRow = $tngcontent->getCause($person['personID'], $tree);
	if ($personRow['eventtypeID'] == "0") {
		$cause_of_death = $personRow['cause'];
		$cause_of_death_id = $personRow['eventID'];
	} else {
		$cause_of_death = "";
		$cause_of_death_id = "NewEvent" . ++$newEventId;
	}
//get familyuser
	if ($person['sex'] == 'M') {
		$sortBy = 'husborder';
	} else if ($person['sex'] == 'F') {
		$sortBy = 'wifeorder';
	} else {
		$sortBy = null;
	}
	$families = $tngcontent->getfamilyuser($person['personID'], $tree, $sortBy);
//parents
	$personfamc = $person['famc']; 
	$parents = '';
	$parents = $tngcontent->getFamilyById($person['famc'], $tree);

	if ($person['famc'] !== '' and $parents['wife'] !== '') {
	$mother = $tngcontent->getPerson($parents['wife'], $tree);
	}
	if ($person['famc'] !== ''and $parents['husband'] !== '') {
	$father = $tngcontent->getPerson($parents['husband'], $tree);
	}
	$parents_familyID = $parents['familyID'];
	if ($person['famc'] == '') {
	$person_famc = "NewParents";
	$parents_familyID = "NewParents";
	$mother_ID = "NewMother";
	$father_ID = "NewFather";
	}
	$parents_marrdate = $parents['marrdate'];
	$parents_marrplace = $parents['marrplace'];
	$parenthusborder = $parents['husborder'];
	$parentwifeorder = $parents['wifeorder'];	
//Father - get Birth date and place
	$father_ID = $father['personID']; 
	$father_firstname = $father['firstname'];
	$father_lastname = $father['lastname'];
	$father_name = $father['firstname']. " ". $father['lastname'];
	$father_birthdate = $father['birthdate'];
	$father_birthplace = $father['birthplace'];
	$father_deathdate = $father['deathdate'];
	$father_deathplace = $father['deathplace'];				
	$father_living = $father['living'];
	$father_sex = $father['sex'];
	$father_famc = $father['famc'];
	if ($father_ID == "") {
	$father_ID = "NewFather";
	$father_lastname = $person['lastname'];
	$father_sex = "M";
	$father_famc = "";
	$parenthusborder = "1";
	}
// Father - Special Event
	if ($father_name !== ' ')
	{
	$fatherRow = $tngcontent->getSpEvent($father['personID'], $tree);
	$father_SpEvent = $fatherRow['info'];
	$father_SpEventId = $fatherRow['eventID'];
	} else {
	$father_SpEvent = "";
	}
//get Cause of Death for Father
	$fatherRow = $tngcontent->getCause($father['personID'], $tree);
	if ($fatherRow['eventtypeID'] == "0") {
		$father_cause_of_death = $fatherRow['cause'];
		$father_cause_of_death_id = $fatherRow['eventID'];
	} else {
		$father_cause_of_death = "";
		$father_cause_of_death_id = "NewEvent" . ++$newEventId;
	} 
//Mother - get Birth date and place
	$mother_ID = $mother['personID']; 
	$mother_firstname = $mother['firstname'];
	$mother_lastname = $mother['lastname'];
	$mothername = $mother['firstname']. " ". $mother['lastname'];
	$mother_birthdate = $mother['birthdate'];
	$mother_birthplace = $mother['birthplace'];
	$mother_deathdate = $mother['deathdate'];
	$mother_deathplace = $mother['deathplace'];
	$mother_living = $mother['living'];
	$mother_sex = $mother['sex'];
	$mother_famc = $mother['famc'];
	if ($mother_ID == "") {
		$mother_ID = "NewMother";
		$mother_sex = "F";
		$parentwifeorder = "1";
		$mother_famc = "";
	}			
//Mother - Get Special Event
	if ($mother_ID != "NewMother")
	{
		$motherRow = $tngcontent->getSpEvent($mother_ID, $tree);
		$mother_SpEvent = $motherRow['info'];	
		$mother_SpEventId = $motherRow['eventID'];	
	} else {
		$mother_SpEvent = "";
	}
//get Cause of Death for Mother
	$motherRow = $tngcontent->getCause($mother['personID'], $tree);
	if ($motherRow['eventtypeID'] == "0") {
		$mother_cause_of_death = $motherRow['cause'];
		$mother_cause_of_death_id = $motherRow['eventID'];
	} else {
		$mother_cause_of_death = "";
		$mother_cause_of_death_id = "NewEvent" . ++$newEventId;
	}				
?>		

<!--BS cotainer-->	
<div class="container-fluid">
	<div class="col-md-12 col-sm-12 col-xs-12">	
	<span float="left" style= "font-type:bold; font-size:12pt">			
<!--------Jquery smart wizard --------- 
<script type="text/javascript" src="<?php echo plugins_url('js/jquery-2.0.0.min.js', dirname(__FILE__)); ?>"></script>
--------->
<script type="text/javascript" src="<?php echo plugins_url('js/jquery.smartWizard.js', dirname(__FILE__)); ?>"></script>
<style type="text/css" media="all">
</style>
<form id="edit-family-form" action = "<?php echo plugins_url('templates/processfamily-update.php', dirname(__FILE__)); ?>" method = "POST">
<input type="hidden" name="User" value="<?php echo $UserLogin; ?>" />
<input type="hidden" name="gedcom" value="<?php echo $person_gedcom; ?>" />
<input type="hidden" name="personID" value="<?php echo $personID; ?>" />
<input type="hidden" name="person[personID]" value="<?php echo $personID; ?>" />
<input type="hidden" name="person[sex]" value="<?php echo $person_sex; ?>" />
<input type="hidden" name="person[famc]" value="<?php echo $person_famc; ?>" />
<input type="hidden" name="person[causeEventID]" value="<?php echo $cause_of_death_id; ?>" />
<input type="hidden" name="person[eventTypeID]" value="<?php echo $EventTypeID; ?>" />
<input type="hidden" name="person[eventID]" value="<?php echo $person_SpEventId; ?>" />
<div id="wizard-update" class="swMain">
  <ul>
    <li><a href="#step-1">
          <label class="stepNumber">1</label>
          <span class="stepDesc">
			Person<br />
             <small>Update </small>
          </span>
      </a></li>
	  
    <li><a href="#step-2">
          <label class="stepNumber">2</label>
          <span class="stepDesc">
             Parents<br />
             <small>Update </small>
          </span>
      </a></li>
    <li><a href="#step-3">
          <label class="stepNumber">3</label>
          <span class="stepDesc">
            Spouse(s)<br />
             <small>Update </small>
          </span>                   
       </a></li>
    <li><a href="#step-4">
          <label class="stepNumber">4</label>
          <span class="stepDesc">
             Children<br />
             <small>Update</small>
          </span>                   
      </a></li>
  </ul>
  <div id="step-1">   
      <h2 class="StepTitle">Update Details for <?php echo $person_name." ". $person_surname;?></h2> 
	  <span style="color:#D77600; font-size:10pt"></br><?php echo "Make changes below and then press NEXT. Do not Change or Refresh the page until you have submitted the changes by clicking on SAVE below"; ?>
       <!-- step ONE content -->
		<div class="table-responsive">
		<table class="table table-bordered"> 
		<tbody>
			<tr class="row">
				<td valign="bottom" class="tdback col-md-2"><?php echo "First, 2nd Name"; ?></td>
				<td class="col-md-6"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="person[firstname]" value="<?php echo $person_name;?>" size="30"/></td>
				<?php if ($EventDisplay != "") {  ?>
				<td valign="bottom" class="tdback col-md-2"><?php echo $EventDisplay; ?></td>
				<td valign="bottom" class="col-md-2"><input type="text" name="person[event]" value="<?php echo $person_SpEvent;?>" /></td></tr>
				<?php } else { ?><td class="tdback col-md-2"></td></tr><?php }?>
			<tr class="row">
				<td class="tdback col-md-2"><?php echo "Last Name"; ?></td>
				<td class="6"><span style="color:#777777">(Surname)<br/></span><input type="text" name="person[surname]" value="<?php echo $person_surname;?>" size="30"/></td>
				<td class="tdback col-md-2"><?php echo "Living / Deceased"; ?></td>
				<td valign="bottom" class="col-md-2">
				<select name="person[living]" value="U">
				<?php 
				if ($person['living'] == '1') { 
					echo '<option value="1" selected>Living</option>'; 
				} else {
					echo '<option value="1">Living</option>';
				}
				if ($person['living'] == '0') { 
					echo '<option value="0" selected>Deceased</option>';
				} else {
					echo '<option value="0">Deceased</option>';
				}
				if ($person['living'] == '') { 
					echo '<option value="U" selected>unknown</option>';
				} else {
					echo '<option value="">Unknown</option>';
				}
				?>
				</select>
			</tr>		
			<tr class="row">	
				<td valign="bottom" class="tdback col-md-2"><?php echo "Born"; ?></td>
				<td valign="bottom" class="col-md-6"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="person[birthdate]" value="<?php echo $person_birthdate;?>"</td>
				<td valign="bottom" class="tdback col-md-2"><?php echo "Place"; ?></td>
				<td valign="bottom" class="col-md-2"><input type="text" name="person[birthplace]" value="<?php echo $person_birthplace;?>"</td>
			</tr>
			<tr class="row">	
				<td valign="bottom"class="tdback col-md-2"><?php echo "Died"; ?></td>
				<td  valign="bottom" class="col-md-6"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="person[deathdate]" value="<?php echo $person_deathdate;?>"></td>
				<td valign="middle" class="tdback col-md-2"><?php echo "Cause of Death". '<br><br/>'. "Place"; ?></td>
				<td valign="middle" class="col-md-2"><input type="text" name="person[cause]" value="<?php echo $cause_of_death;?>"><br/><br/><input type="text" name="person[deathplace]" value="<?php echo $person_deathplace;?>" /></td>
			</tr>	
		</tbody>
		</table>
		</div>
	</div>
	<div id="step-2">
<h2 class="StepTitle">Update Details of Parents for <?php echo $person_name.$Person_surname;?></h2> 
<span style="color:#D77600; font-size:10pt"></br><?php echo "Make changes below and then press NEXT. Do not Change or Refresh the page until you have submitted the changes by clicking on SAVE below"; ?>
       
<!-- step TWO content  -->
	<div class="table-responsive">
		<table class="table table-bordered"> 
		<tbody>		
			<tr class="row">
			<input type="hidden" name="father[personID]" value="<?php echo $father_ID; ?>" />
			<input type="hidden" name="father[sex]" value="<?php echo $father_sex; ?>" />
			<input type="hidden" name="father[famc]" value="<?php echo $father_famc; ?>" />
			<input type="hidden" name="father[living]" value="<?php echo $father_living; ?>" />
			<input type="hidden" name="father[causeEventID]" value="<?php echo $father_cause_of_death_id; ?>" />
			<input type="hidden" name="father[eventTypeID]" value="<?php echo $EventTypeID; ?>">
			<input type="hidden" name="father[eventID]" value="<?php echo $father_SpEventId; ?>">
				<td valign="bottom" class="tdback col-md-2">Father</td>
				<td class="col-md-6"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="father[firstname]" value="<?php echo $father_firstname;?>"></td>
				<?php if ($EventDisplay != "") {  ?>
				<td valign="bottom" class="tdback col-md-2"><?php echo $EventDisplay; ?></td>
				<td valign="bottom" class="col-md-2"><input type="text" name="father[event]" value="<?php echo $father_SpEvent;?>" /></td></tr>
				<?php } else { ?><td class="tdback col-md-2"></td><class="col-md-2"></td></tr><?php }?>
				<tr class="row">	
				<td class="tdback col-md-2"></td>
				<td class="col-md-6"><span style="color:#777777">(Surname)<br/></span><input type="text" name="father[surname]" value="<?php echo $father_lastname;?>"></td>
				<td class="tdback col-md-2">Living /<br />Deceased</td>
				<td valign="bottom" class="col-md-2">
				<select name="father[living]" value="U">
				<?php 
				if ($father['living'] == '1') { 
					echo '<option value="1" selected>Living</option>'; 
				} else {
					echo '<option value="1">Living</option>';
				}
				if ($father['living'] == '0') { 
					echo '<option value="0" selected>Deceased</option>';
				} else {
					echo '<option value="0">Deceased</option>';
				}
				if ($father['living'] == '') { 
					echo '<option value="U" selected>unknown</option>';
				} else {
					echo '<option value="">Unknown</option>';
				}
				?>
				</select></td>
			<tr class="row">	
				<td valign="bottom" class="tdback col-md-2"><?php echo "Born"; ?></td>
				<td valign="bottom" class="col-md-6"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="father[birthdate]" value="<?php echo $father_birthdate;?>"></td>
				<td valign="bottom" class="tdback col-md-2"><?php echo "Place"; ?></td>
				<td valign="bottom"class="col-md-2"><input type="text" name="father[birthplace]" value="<?php echo $father_birthplace;?>"></td>
			<tr class="row">	 
				<td valign="bottom" class="tdback col-md-2"><?php echo "Died"; ?></td>
				<td valign="bottom" class="col-md-6"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="father[deathdate]" value="<?php echo $father_deathdate;?>"></td>
				<td valign="middle" class="tdback col-md-2"><?php echo "Cause of Death". '<br><br/>'. "Place"; ?></td>
				<td valign="middle" class="col-md-2"><input type="text" name="father[cause]" value="<?php echo $father_cause_of_death;?>"><br/><br/><input type="text" name="father[deathplace]" value="<?php echo $father_deathplace;?>"></td></tr>
			<tr class="row">
		<td class="col-md-2"></td><td class="col-md-6"></td><td class="col-md-2"></td><td class="col-md-2"></td><td></td>
			</tr>
			<tr class="row">	
				<td valign="bottom" class="tdback col-md-2">Mother</td>
				<td class="col-md-6"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="mother[firstname]" value="<?php echo $mother_firstname;?>"></td>
				<?php if ($EventDisplay != "") {  ?>
				<td valign="bottom" class="tdback col-md-2"><?php echo $EventDisplay; ?></td>
				<td valign="bottom" class="col-md-2"><input type="text" name="mother[event]" value="<?php echo $mother_SpEvent;?>" /></td></tr>
				<?php } else { ?><td class="tdback col-md-2"></td></tr><?php }?>
			<tr class="row">	
				<td class="tdback col-md-2"></td>
				<td class="col-md-6"><span style="color:#777777">(Surname)<br/></span><input type="text" name="mother[surname]" value="<?php echo $mother_lastname;?>"></td>
				<td class="tdback col-md-2">Living /<br />Deceased</td>
				<td valign="bottom" class="col-md-2">
				<select name="mother[living]">
				<?php 
				if ($mother_living == '1') { 
					echo '<option value="1" selected>Living</option>'; 
				} else {
					echo '<option value="1">Living</option>';
				}
				if ($mother['living'] == '0') { 
					echo '<option value="0" selected>Deceased</option>';
				} else {
					echo '<option value="0">Deceased</option>';
				}
				if ($mother['living'] == '') { 
					echo '<option value="U" selected>unknown</option>';
				} else {
					echo '<option value="">Unknown</option>';
				}
				?>
				</select></td>
			
			<tr class="row">	
				<td valign="bottom" class="tdback col-md-2"><?php echo "Born"; ?></td>
				<td valign="bottom" class="col-md-6"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="mother[birthdate]" value="<?php echo $mother_birthdate;?>"></td>
				<td valign="bottom" class="tdback col-md-2"><?php echo "Place"; ?></td>
				<td valign="bottom" class="col-md-2"><input type="text" name="mother[birthplace]" value="<?php echo $mother_birthplace;?>"></td>
			<tr class="row">	
				<td valign="bottom" class="tdback col-md-2"><?php echo "Died"; ?></td>
				<td valign="bottom" class="col-md-6"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" name="mother[deathdate]" value="<?php echo $mother_deathdate;?>"></td>
				<td valign="middle" class="tdback col-md-2"><?php echo "Cause of Death". '<br><br/>'. "Place"; ?></td>
				<td valign="middle" class="col-md-2"><input type="text" name="mother[cause]" value="<?php echo $mother_cause_of_death;?>"><br/><br/><input type="text" name="mother[deathplace]" value="<?php echo $mother_deathplace;?>"></td></tr>
			</tr>
			<tr class="row">
				<td class="tdback col-md-2"><?php echo "Married" ?></td>
				<td valign="bottom" class="col-md-6"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" name="parents[marrdate]" value="<?php echo $parents_marrdate;?>"></td>
				<td class="tdback col-md-2"><?php echo "Place"; ?></td>
				<td valign="bottom" class="col-md-2"><input type="text" name="parents[marrplace]" value="<?php echo $parents_marrplace;?>"></td>
			</tr>
		</tbody>
		</table>
		<input type="hidden" name="father[personID]" value="<?php echo $father_ID; ?>" />
<input type="hidden" name="father[sex]" value="<?php echo $father_sex; ?>" />
<input type="hidden" name="father[famc]" value="<?php echo $father_famc; ?>" />
<input type="hidden" name="mother[personID]" value="<?php echo $mother_ID; ?>" />
<input type="hidden" name="mother[sex]" value="<?php echo $mother_sex; ?>" />
<input type="hidden" name="mother[famc]" value="<?php echo $mother_famc; ?>" />
<input type="hidden" name="mother[causeEventID]" value="<?php echo $mother_cause_of_death_id; ?>" />
<input type="hidden" name="mother[eventTypeID]" value="<?php echo $EventTypeID; ?>" />
<input type="hidden" name="mother[eventID]" value="<?php echo $mother_SpEventId; ?>" />
<input type="hidden" name="parents[husborder]" value="<?php echo $parenthusborder; ?>" />
<input type="hidden" name="parents[wifeorder]" value="<?php echo $parentwifeorder; ?>" />
<input type="hidden" name="parents[living]" value="<?php echo $parents['parents_living']; ?>" />

		</div>
	</div>                     
	<div id="step-3">
<!-- step THREE header -->
<h2 class="StepTitle">Update Details of Spouse(s) for <?php echo $person_name." ". $person_surname;?></h2>   
<span style="color:#D77600; font-size:10pt"></br><?php echo "Make changes below and then press NEXT. Do not Change or Refresh the page until you have submitted the changes by clicking on SAVE below"; ?>
  	<?php
// Spouses
	foreach ($families as $index => $family):
		$spousemarrdate = $family['marrdate'];
		$spousemarrplace = $family['marrplace'];
		$order = 1;
		if ($sortBy && count($families) > 1) {
			$order = $family[$sortBy];
		}
		if ($person['personID'] == $family['wife']) {
			$spouse = $tngcontent->getPerson($family['husband'], $tree);
			$spousehusband = $family['husband'];
			$husbandname = $spouse['lastname'];
			$spousewife = $person['personID'];
			$spousehusborder = $family['husborder'];
			$spousewifeorder = $family['wifeorder'];
		} else {
			$spouse = $tngcontent->getPerson($family['wife'], $tree);
			$spousehusband = $person['personID'];
			$spousewife = $family['wife'];
			$spousehusborder = $family['husborder'];
			$spousewifeorder = $family['wifeorder'];
			$husbandname = $person['lastname'];
		}
	$spousedeathdate = $spouse['deathdate'];
	//$spousemarrdate = $family['marrdate'];
	$spouseRow = $tngcontent->getSpEvent($spouse['personID'], $tree);
	$spouseSpEvent = $spouseRow['info'];
	$spouseSpEventId = $spouseRow['eventID'];
	$spouseName = $spouse['firstname'] . " ". $spouse['lastname'];
	$children = $tngcontent->getchildren($family['familyID'], $tree);
//get Cause of Death for Spouse
	$spouseRow = $tngcontent->getCause($spouse['personID'], $tree);
	if ($spouseRow['eventtypeID'] == "0") {
		$spouse_cause_of_death = $spouseRow['cause'];
		$spouse_cause_of_death_id = $spouseRow['eventID'];
	} else {
		$spouse_cause_of_death = "";
		$spouse_cause_of_death_id = "NewEvent" . ++$newEventId;
	}	
// if wife name is not in database
	if ($family['wife'] == "" and $family['husband'] !== "") {
		$spouse['firstname'] = "";
		$spouse['lastname'] = "";
		$spouse['birthdate'] = "";
		$spouse['birthplace'] = "";
		$spouse['deathdate'] = "";
		$spouse['deathplace'] = "";
		$spouse['marrdate'] = "";
		$spouse['marrplace'] = "";
		$spouseSpEvent = "";
	}
// if Husband name is not in database
	if ($family['wife']!== "" and $family['husband'] == "") {
		$spouse['firstname'] = "";
		$spouse['lastname'] = "";
		$spouse['birthdate'] = "";
		$spouse['birthplace'] = "";
		$spouse['deathdate'] = "";
		$spouse['deathplace'] = "";
		$spouse['marrdate'] = "";
		$spouse['marrplace'] = "";
		$spouseSpEvent = "";
	}
	?>
<!-- step THREE content -->
	<div class="table-responsive">
		<table class="table table-bordered"> 
			<tbody>		
			<tr class="row">
				<td class="tdback col-md-2"><?php echo "Spouse ". $order; ?></td>
				<td class="col-md-6"><span style="color:#777777">(Spouse Name-2nd name or Father's Name)<br/></span><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][firstname]" value="<?php echo $spouse['firstname'];?>"></td>
				<?php if ($EventDisplay != "") {  ?>
				<td valign="bottom" class="tdback col-md-2"><?php echo $EventDisplay; ?></td>
				<td valign="bottom" class="col-md-2"><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][event]" value="<?php echo $spouseSpEvent;?>" /></td></tr>
				<?php } else { ?><td class="tdback col-md-2"></td><td></td><?php }?>
			<tr class="row">	
				<td class="tdback col-md-2"></td>
				<td class="col-md-6"><span style="color:#777777">(Surname)<br/></span><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][surname]" value="<?php echo $spouse['lastname'];?>" size="30"/></td>
				<td class="tdback col-md-2"><?php echo "Living / Deceased"; ?></td>
				<td valign="bottom" class="col-md-2">
				<select name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][living]" value="U">
				<?php 
				if ($spouse['living'] == '1') { 
					echo '<option value="1" selected>Living</option>'; 
				} else {
					echo '<option value="1">Living</option>';
				}
				if ($spouse['living'] == '0') { 
					echo '<option value="0" selected>Deceased</option>';
				} else {
					echo '<option value="0">Deceased</option>';
				}
				if ($spouse['living'] == '') { 
					echo '<option value="U" selected>unknown</option>';
				} else {
					echo '<option value="">Unknown</option>';
				}
				?>
				</select>
			</tr>
			<tr class="row">		
				<td valign="bottom" class="tdback col-md-2"><?php echo "Born"; ?></td>
				<td valign="bottom" class="col-md-6"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][birthdate]" value="<?php echo $spouse['birthdate'];?>"</td>
				<td valign="bottom" class="tdback col-md-2"><?php echo "Place"; ?></td>
				<td valign="bottom" class="col-md-2"><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][birthplace]" value="<?php echo $spouse['birthplace'];?>" size="10"/></td>
			</tr>
			<tr class="row">	
				<td valign="bottom" class="tdback col-md-2"><?php echo "Died"; ?></td>
				<td valign="bottom" class="col-md-6"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][deathdate]" value="<?php echo $spouse['deathdate'];?>" /></td>
				<td valign="middle" class="tdback col-md-2"><?php echo "Cause of Death". '<br><br/>'. "Place"; ?></td>
				<td valign="middle" class="col-md-2"><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][cause]" value="<?php echo $spouse_cause_of_death;?>"><br/><br/><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][deathplace]" value="<?php echo $spouse['deathplace'];?>" /></td></tr>
			<tr>
			</tr>
			<tr class="row">
				<td class="tdback col-md-2"><?php echo "Married" ?></td>
				<td valign="bottom" class="col-md-6"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][marrdate]" value="<?php echo $spousemarrdate;?>" /></td>
				<td class="tdback col-md-2"><?php echo "Place"; ?></td>
				<td valign="bottom" class="col-md-2"><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][marrplace]" value="<?php echo $spousemarrplace;?>" /></td>
			</tr>
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][familyID]" value="<?php echo $family['familyID'] ?>" />
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][order]" value="<?php echo $order ?>" />
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][personID]" value="<?php echo $spouse['personID'] ?>" />
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][sex]" value="<?php echo $spouse['sex'] ?>" />
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][famc]" value="<?php echo $spouse['famc'] ?>" />
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][husband]" value="<?php echo $spousehusband ?>" />
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][wife]" value="<?php echo $spousewife ?>" />
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][husborder]" value="<?php echo $spousehusborder ?>" />
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][wifeorder]" value="<?php echo $spousewifeorder ?>" />
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][causeEventID]" value="<?php echo $spouse_cause_of_death_id ?>" />
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][eventTypeID]" value="<?php echo $EventTypeID; ?>" />
				<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][eventID]" value="<?php echo $spouseSpEventId; ?>" />
			</tbody>	
		</table>
	</div>	
	<?php
	endforeach; 
	?> 
</div>
<div id="step-4">
<h2 class="StepTitle">Update Details of Children for <?php echo $person_name." ". $person_surname;?> </h2>   
<!-- step content 4--> 
<script>
function initChildren(order) {
	var clone;
	function cloneRow()  { // create clone of empty child line for use during session
		var rows=$('#children_' + order).find('tr.child');
		var idx=rows.length;
		if (idx) {
                    clone=rows[idx-1].cloneNode(true);
		}			
	}
	cloneRow();
	$('.js-addChild-edit[data-id="' + order + '"]').click(addRow);
	function addRow(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		var newclone = clone.cloneNode(true);
		var rows=$('#children_' + order).find('tr.child');
		var idx=rows.length;
		if( idx > 0 ) { 
			var field=rows.eq(idx-1).find('input').first();
			var firstname=field[0].value;
			if( !firstname ) {
				alert("Please fill in the new row before adding more");
				return;
			}
		}
		var inputs=newclone.getElementsByTagName('input'), inp, i=0;
                i=0;
		while(inp=inputs[i++]) {
			inp.name=inp.name.replace(/\]\[\d\]/g, '][' + (idx + 1) + ']');
                        
			$(inp).val(inp.value.replace(/^(NewChild-\d+\.)\d+$/g, '$1' + (idx + 1)));
			$(inp).val(inp.value.replace(/^(NewEvent-\d+\.)\d+$/g, '$1' + (idx + 1)));
                        if (inp.name.match(/\[order\]/)) {
                            $(inp).val(idx + 1);
                        }
                        var type = $(inp).prop('type');
                        if (type === 'hidden' || type === 'checkbox') {
                            continue;
                        }
                        $(inp).val(null);
		}
		var selects=newclone.getElementsByTagName('select'), sel, i=0;
                i=0;
		while(sel=selects[i++]) {
			sel.name=sel.name.replace(/\]\[\d\]/g, '][' + (idx + 1) + ']');
			sel.selectedItem = 0;
		}
                var tbo=document.getElementById('children_' + order).getElementsByTagName('tbody')[0];
                tbo.appendChild(newclone);
	}
	function deleteLastRow() {
		var tbo=document.getElementById('children_' + order).getElementsByTagName('tbody')[0];
		var rows = tbo.getElementsByTagName('tr');
		tbo.removeChild(rows[rows.length-1] );    
		if(rows.length < 1) {
			addRow();
		}
	}
}
</script>
	<?php
// Family
	foreach ($families as $family):
		$spousemarrdate = $family['marrdate'];
		$spousemarrplace = $family['marrplace'];
		$order = 1;
		if ($sortBy && count($families) > 1) {
			$order = $family[$sortBy];
		}
		if ($person['personID'] == $family['wife']) {
		$spouse = $tngcontent->getPerson($family['husband'], $tree);
		} else {
			$spouse = $tngcontent->getPerson($family['wife'], $tree);
		}
		$deathdate = $spouse['deathdate'];
		$spouseRow = $tngcontent->getSpEvent($spouse['personID'], $tree);
		$spouseSpEvent = $spouseRow['info'];
		$spouseName = $spouse['firstname'] . " ". $spouse['lastname'];
		$children = $tngcontent->getchildren($family['familyID'], $tree);
	
// if wife name is not in database
		if ($family['husband'] == "" and $family['wife'] !== "") {
			$spouse['firstname'] = "";
			$spouse['lastname'] = "";
		}
		if ($family['wife'] == "" and $family['husband'] !== "") {
			$spouse['firstname'] = "";
			$spouse['lastname'] = "";
		}
	?>
<!-- Step FOUR content-->
<div class="table-responsive" style="overflow: auto">
	<table class="table table-bordered" id="children_<?php echo $order; ?>">	
		<div class="col-md-12">
			<div class="row">
			<span style="color:#D77600; font-size:14pt">			
			<?php echo "Spouse (". $order. ") ". $spouse['firstname']." ". $spouse['lastname']; ?></span><br />
			</div>
			<div class="row col-md-12 add-padding">
			<button class="js-addChild-edit" data-id="<?php echo $order; ?>">add child</button>
			</div>		
	
			<thead>
			<tr>
				<th class="theader"><small>First Name </small></th>	
				<th class="theader"><small>Last Name</small></th>
				<th class="theader"><small>Sex</small></th>
				<th class="theader"><small>Date Born</br>dd mmm yyyy</small></th>
				<th class="theader"><small>Place Born</small></th>
				<th class="theader"><small>Date Died</br>dd mmm yyyy</small></th>
				<th class="theader"><small>Place Died</small></th>
				<th class="theader"><small>Living</small></th>
				<th class="theader"><small>Cause of Death</small></th>
			</tr>
		</thead>
		<tbody>
		<?php
        $childorder = 0;
		foreach ($children as $index => $child):
			$classes = array('child');
			$childPerson = $tngcontent->getPerson($child['personID'], $tree);
			$childFirstName = $childPerson['firstname'];
			$childLastName = $childPerson['lastname'];
			$childName = $childPerson['firstname']. $childPerson['lastname'];
			$childbirthdate = $childPerson['birthdate'];
			$childbirthplace = $childPerson['birthplace'];
			$childdeathdate = $childPerson['deathdate'];
			$childdeathplace = $childPerson['deathplace'];
			$childorder = $child['ordernum'];
			$childliving = $childPerson['living'];
			$childsex = $childPerson['sex'];
			
// init sex selector
			$Msex = $Fsex = $Usex = "";
		
			if( $childsex == 'M' ) $Msex = "selected=\"selected\"";
			elseif( $childsex == 'F' ) $Fsex = "selected=\"selected\"";
			else $Usex = "selected=\"selected\"";
// Child - Special Event
			$fathereventRow = $tngcontent->getSpEvent($family['husband'], $tree);
			$fatherevent = $fathereventRow['info'];
			if ($childFirstName !== '')
			{
				$childRow = $tngcontent->getSpEvent($child['personID'], $tree);
				$childevent = $childRow['info'];
							$childeventId = $childRow['eventID'];
			} else {
				$childevent = "";
			}
				
				/* This is to parse father value to child
				if ($childevent = "" OR $childevent = "Unknown") {
				$childevent = $fatherChildevent;
				}
				*/
//get Cause of Death for child
			$childRow = $tngcontent->getCause($child['personID'], $tree);
			if ($childRow['eventtypeID'] == "0") {
				$childcause = $childRow['cause'];
				$childcause_id = $childRow['eventID'];
			} else {
				$childcause = "";
				$childcause_id = "NewEvent" . ++$newEventId;
			}
		if ($child['haskids']) {
				$classes[] = 'haskids';
			}
		?>
		
			<tr class="child">
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][personID]" value="<?php echo $child['personID'] ?>"/>
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][familyID]" value="<?php echo $family['familyID'] ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][order]" value="<?php echo $child['ordernum'] ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][spouseorder]" value="<?php echo $family[$sortBy] ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][event]" value="<?php echo $childevent ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][eventID]" value="<?php echo $childeventId ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][eventTypeID]" value="<?php echo $EventTypeID ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][haskids]" value="<?php echo $child['haskids'] ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][parentorder]" value="<?php echo $child['parentorder'] ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][living]" value="<?php echo $child['living'] ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][famc]" value="<?php echo $family['familyID'] ?>"/>
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][living]" value="0" /></td>
			<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][causeEventID]" value="<?php echo $childcause_id; ?>" /></td>
				<td>
				<input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][firstname]" value="<?php echo $childFirstName;?>" size="08"/></td>
				<td>
				<input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][surname]" value="<?php echo $childLastName;?>" size="08"/></td>	
				<td>           
                <select name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][sex]">
				<option value="M" <?php echo $Msex; ?>>M</option>
				<option value="F" <?php echo $Fsex; ?>>F</option>
				</select>
				</td>
				<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][birthdate]" value="<?php echo $childbirthdate;?>" size="08"/></td>
				<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][birthplace]" value="<?php echo $childbirthplace;?>" size="10"/></td>
				<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][deathdate]" value="<?php echo $childdeathdate;?>" size="08"/></td>
				<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][deathplace]" value="<?php echo $childdeathplace;?>" size="10"/></td>
				<td><input type="checkbox" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][living]" value="1" <?php echo ($childPerson['living']?'checked':NULL);?> /></td>
				<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][cause]" value="<?php echo $childcause;?>" size="10"/></td>
			</tr>
		<?php
		endforeach;
		$index += 1;
		$childorder += 1;
		$childID = "NewChild-";
		$childCauseEventID = "NewEvent-". $order . "." .$childorder;
        $parentOrder = $child['parentorder'];
		if (!$parentOrder) {
			$parentOrder = 1;
		}
		?>
<script>
        $(function () {
            initChildren(<?php echo $order; ?>);
        });
        </script>	
		<tr class="child">
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][personID]" value="<?php echo $childID.$order.'.'.$childorder ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][familyID]" value="<?php echo $family['familyID'] ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][order]" value="<?php echo $childorder ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][spouseorder]" value="<?php echo $family[$sortBy] ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][event]" value="<?php echo $childevent ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][eventTypeID]" value="<?php echo $EventTypeID ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][haskids]" value="0" size="12"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][parentorder]" value="<?php echo $parentOrder ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][famc]" value="<?php echo $family['familyID'] ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][living]" value="0" /></td>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][causeEventID]" value="<?php echo $childCauseEventID; ?>" /></td>
		
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][firstname]" value="" size="10"/></td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][surname]" value="<?php echo $husbandname; ?>" size="10"/></td>	
                <td> 
                    <select name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][sex]">

                        <option selected="selected" value="M">M</option>
                        <option value="F">F</option>

                    </select>
                </td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][birthdate]" value="" size="08"/></td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][birthplace]" value="" size="10"/></td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][deathdate]" value="" size="08"/></td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][deathplace]" value="" size="10"/></td>
		<td><input type="checkbox" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][living]" value="1" checked="checked" /></td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][cause]" value="" size="10"/></td>

	<?php
	endforeach;
	?>	
		</tbody>
		</div>
	</table>
		
	</div>   
</div>
  </div>
</div>		
</form>

</div>
<div style="clear:both"></div>

<script type="text/javascript">
  $(document).ready(function() {
      // Initialize Smart Wizard
        $('#wizard-update').smartWizard({
	// Properties
    keyNavigation: false, // Enable/Disable key navigation(left and right keys are used if enabled)
    onFinish: function () {
		$('#edit-family-form').submit();
			}
		});
   });  
</script>
