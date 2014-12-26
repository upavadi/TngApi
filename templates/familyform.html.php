 <!--UPDATE FAMILY -->
			
			<?php
				
				$tngcontent = Upavadi_tngcontent::instance()->init();
				
				 //get and hold current user
				$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
				$person = $tngcontent->getPerson($currentperson);
				$currentuser = ($person['firstname']. $person['lastname']);
				$currentuserLogin = wp_get_current_user();
				$UserLogin = $currentuserLogin->user_login;
				//echo $UserLogin;
				?>
			
				<a href="?personId=<?php echo $person['personID']; ?>"><span style="color:#D77600; font-size:14pt">			
				<?php echo "Welcome ". $currentuser; ?></span>
				</a><br>
	
				<?php
//get person details
				$person = $tngcontent->getPerson($personId);
				$personID = $person['personID'];
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
				$personRow = $tngcontent->getSpEvent($person['personID']);
				$person_SpEvent = $personRow['info'];
				
//get Description of Event type
				$EventRow = $tngcontent->getEventDisplay($event['display']);	
				$EventDisplay = $EventRow['display'];
	

				
// title for page	
				?>
				<span float="left" style= "font-type:bold; font-size:12pt">			
				<?php echo "Update Details for the Family of ". $person_name. $person_surname; ?></span>
				
				<?php

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
		$personRow = $tngcontent->getCause($person['personID']);
		if ($personRow['eventtypeID'] == "0") {
			$cause_of_death = $personRow['cause'];
		} else {
		$cause_of_death = "";
		}
//Person dates and places		
				//$person_birthdate == $person['birthdate'];
				//$person_deathdate = $person['deathdate'];
				//$person_birthplace == $person['birthplace'];
				//$person_deathplace == $person['deathplace'];
			
				
//get familyuser
				if ($person['sex'] == 'M') {
					$sortBy = 'husborder';
				} else if ($person['sex'] == 'F') {
					$sortBy = 'wifeorder';
				} else {
					$sortBy = null;
				}
				
			$families = $tngcontent->getfamilyuser($person['personID'], $sortBy);
				
			?>		




<!--------Jquery smart wizard --------- 
<script type="text/javascript" src="<?php echo plugins_url('js/jquery-2.0.0.min.js', dirname(__FILE__)); ?>"></script>
--------->
<script type="text/javascript" src="<?php echo plugins_url('js/jquery.smartWizard.js', dirname(__FILE__)); ?>"></script>

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
<style type="text/css" media="all">
</style>
<form id="edit-family-form" action = "<?php echo plugins_url('templates/processfamily-update.php', dirname(__FILE__)); ?>" method = "POST">
<input type="hidden" name="User" value="<?php echo $UserLogin; ?>" />
<input type="hidden" name="personID" value="<?php echo $personID; ?>" />
<input type="hidden" name="person[personID]" value="<?php echo $personID; ?>" />
<input type="hidden" name="person[sex]" value="<?php echo $person_sex; ?>" />
<input type="hidden" name="person[famc]" value="<?php echo $person_famc; ?>" />

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
      <h2 class="StepTitle">Update Details for <?php echo $person_name.$person_surname;?></h2> 
	  <span style="color:#D77600; font-size:10pt"></br><?php echo "Make changes below and then press NEXT. Do not Change or Refresh the page until you have submitted the changes by clicking on SAVE below"; ?>
       <!-- step content -->
	   <table>
	<tbody>
		<tr>
			<td valign="bottom" class="tdback"><?php echo "First, 2nd Name"; ?></td>
			<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="person[firstname]" value="<?php echo $person_name;?>" size="30"/></td>
			<?php if ($EventDisplay != "") {  ?>
			<td valign="bottom" class="tdback"><?php echo $EventDisplay; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" name="person[event]" value="<?php echo $person_SpEvent;?>" /></td></tr>
			<?php } else { ?><td class="tdback"></td><class="tdfront"></td><?php }?>
			
		<tr>
			
			<td class="tdback"><?php echo "Last Name"; ?></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" name="person[surname]" value="<?php echo $person_surname;?>" size="30"/></td>
			<td class="tdback"><?php echo "Living / Deceased"; ?></td>
			<td valign="bottom" class="tdfront">
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
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="person[birthdate]" value="<?php echo $person_birthdate;?>"</td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<?php 
			
			?>
			<td valign="bottom" class="tdfront"><input type="text" name="person[birthplace]" value="<?php echo $person_birthplace;?>"</td>
		<tr>	
			<td valign="bottom"class="tdback"><?php echo "Died"; ?></td>
			<td  valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="person[deathdate]" value="<?php echo $person_deathdate;?>"></td>
			<td valign="middle" class="tdback"><?php echo "Cause of Death". '<br><br/>'. "Place"; ?></td>
			<td valign="middle" class="tdfront"><input type="text" name="person[cause]" value="<?php echo $cause_of_death;?>"><br/><br/><input type="text" name="person[deathplace]" value="<?php echo $person_deathplace;?>" /></td></tr>
		</tr>
	</tbody>
</table>


  </div>
  <div id="step-2">
      <h2 class="StepTitle">Update Details of Parents for <?php echo $person_name.$Person_surname;?></h2> 
       <!-- step content -->
<?php
			$personfamc = $person['famc']; 
			$parents = '';
			$parents = $tngcontent->getFamilyById($person['famc']);

			if ($person['famc'] !== '' and $parents['wife'] !== '') {
			$mother = $tngcontent->getPerson($parents['wife']);
			}
			if ($person['famc'] !== ''and $parents['husband'] !== '') {
			$father = $tngcontent->getPerson($parents['husband']);
			}
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
				$father_name = $father['firstname']. $father['lastname'];
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
				if ($father_name !== '')
				{
				$fatherRow = $tngcontent->getSpEvent($father['personID']);
				$father_SpEvent = $fatherRow['info'];
				} else {
				$father_SpEvent = "";
				}
//get Cause of Death for Father
				$fatherRow = $tngcontent->getCause($father['personID']);
				if ($fatherRow['eventtypeID'] == "0") {
					$father_cause_of_death = $fatherRow['cause'];
				} else {
				$father_cause_of_death = "";
				} 
		
				
//Mother - get Birth date and place
				
				$mother_ID = $mother['personID']; 
				
				$mother_firstname = $mother['firstname'];
				$mother_lastname = $mother['lastname'];
				$mothername = $mother['firstname']. $mother['lastname'];
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
				$motherRow = $tngcontent->getSpEvent($mother_ID);
				$mother_SpEvent = $motherRow['info'];	
				
				} else {
				$mother_SpEvent = "";
				}
				
//get Cause of Death for Mother
				$motherRow = $tngcontent->getCause($mother['personID']);
				if ($motherRow['eventtypeID'] == "0") {
					$mother_cause_of_death = $motherRow['cause'];
				} else {
				$mother_cause_of_death = "";
				}
			?>
<table>
	<tbody>		
		<tr>
			<input type="hidden" name="father[personID]" value="<?php echo $father_ID; ?>" />
			<input type="hidden" name="father[sex]" value="<?php echo $father_sex; ?>" />
			<input type="hidden" name="father[famc]" value="<?php echo $father_famc; ?>" />
			<input type="hidden" name="father[living]" value="<?php echo $father_living; ?>" />

			<td valign="bottom" class="tdback">Father</td>
			<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="father[firstname]" value="<?php echo $father_firstname;?>"></td>
			<?php if ($EventDisplay != "") {  ?>
			<td valign="bottom" class="tdback"><?php echo $EventDisplay; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" name="father[event]" value="<?php echo $father_SpEvent;?>" /></td></tr>
			<?php } else { ?><td class="tdback"></td><class="tdfront"></td><?php }?>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" name="father[surname]" value="<?php echo $father_lastname;?>" size="30"/></td>
			<td class="tdback"><?php echo "Living / Deceased"; ?></td>
			<td valign="bottom" class="tdfront">
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
			</select>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="father[birthdate]" value="<?php echo $father_birthdate;?>" size="10"/></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			
			<td valign="bottom"class="tdfront"><input type="text" name="father[birthplace]" value="<?php echo $father_birthplace;?>" /></td>
		<tr>	 
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="father[deathdate]" value="<?php echo $father_deathdate;?>" /></td>
			<td valign="middle" class="tdback"><?php echo "Cause of Death". '<br><br/>'. "Place"; ?></td>
			<td valign="middle" class="tdfront"><input type="text" name="father[cause]" value="<?php echo $father_cause_of_death;?>"><br/><br/><input type="text" name="father[deathplace]" value="<?php echo $father_deathplace;?>" /></td></tr>
		
		</tr>
		
			<td valign="bottom" class="tdback">Mother</td>
			<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="mother[firstname]" value="<?php echo $mother_firstname;?>" size="30"/></td>
			<?php if ($EventDisplay != "") {  ?>
			<td valign="bottom" class="tdback"><?php echo $EventDisplay; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" name="mother[event]" value="<?php echo $mother_SpEvent;?>" /></td></tr>
			<?php } else { ?><td class="tdback"></td><class="tdfront"></td><?php }?>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" name="mother[surname]" value="<?php echo $mother_lastname;?>" size="30"/></td>
			
			<td class="tdback"><?php echo "Living / Deceased"; ?></td>
			<td valign="bottom" class="tdfront">
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
			</select>
		</tr>	
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="mother[birthdate]" value="<?php echo $mother_birthdate;?>" size="10"/></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			
			<td valign="bottom" class="tdfront"><input type="text" name="mother[birthplace]" value="<?php echo $mother_birthplace;?>" /></td>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" name="mother[deathdate]" value="<?php echo $mother_deathdate;?>" /></td>
			<td valign="middle" class="tdback"><?php echo "Cause of Death". '<br><br/>'. "Place"; ?></td>
			<td valign="middle" class="tdfront"><input type="text" name="mother[cause]" value="<?php echo $mother_cause_of_death;?>"><br/><br/><input type="text" name="mother[deathplace]" value="<?php echo $mother_deathplace;?>" /></td></tr>
		</tr>
		<tr>
		<td class="tdback"><?php echo "Married" ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" name="parents[marrdate]" value="<?php echo $parents_marrdate;?>" /></td>
			
			<td class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" name="parents[marrplace]" value="<?php echo $parents_marrplace;?>" /></td>
		
		</tr>
	</tbody>


</table>

<input type="hidden" name="father[personID]" value="<?php echo $father_ID; ?>" />
<input type="hidden" name="father[sex]" value="<?php echo $father_sex; ?>" />
<input type="hidden" name="father[famc]" value="<?php echo $father_famc; ?>" />
<input type="hidden" name="mother[personID]" value="<?php echo $mother_ID; ?>" />
<input type="hidden" name="mother[sex]" value="<?php echo $mother_sex; ?>" />
<input type="hidden" name="mother[famc]" value="<?php echo $mother_famc; ?>" />
<input type="hidden" name="parents[husborder]" value="<?php echo $parenthusborder; ?>" />
<input type="hidden" name="parents[wifeorder]" value="<?php echo $parentwifeorder; ?>" />
<input type="hidden" name="parents[living]" value="<?php echo $parents['parents_living']; ?>" />


	   
  </div>                      
  <div id="step-3">
      <h2 class="StepTitle">Update Details of Spouse(s) for <?php echo $person_name.$person_surname;?></h2>   
       <!-- step content -->
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
				
				$spouse = $tngcontent->getPerson($family['husband']);
				$spousehusband = $family['husband'];
				$husbandname = $spouse['lastname'];
				$spousewife = $person['personID'];
				$spousehusborder = $family['husborder'];
				$spousewifeorder = $family['wifeorder'];
				} else {
					$spouse = $tngcontent->getPerson($family['wife']);
					$spousehusband = $person['personID'];
					$spousewife = $family['wife'];
					$spousehusborder = $family['husborder'];
					$spousewifeorder = $family['wifeorder'];
					$husbandname = $person['lastname'];
				}
				 
				$spousedeathdate = $spouse['deathdate'];
				//$spousemarrdate = $family['marrdate'];
				$spouseRow = $tngcontent->getSpEvent($spouse['personID']);
				$spouseSpEvent = $spouseRow['info'];
				$spouseName = $spouse['firstname'] . $spouse['lastname'];
								
				$children = $tngcontent->getchildren($family['familyID']);

				//get Cause of Death for Spouse
				$spouseRow = $tngcontent->getCause($spouse['personID']);
				if ($spouseRow['eventtypeID'] == "0") {
					$spouse_cause_of_death = $spouseRow['cause'];
				} else {
				$spouse_cause_of_death = "";
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
		<input type="hidden" name="spouseorder" value="<?php echo $order; ?>" />
		<input type="hidden" name="spouse_name" value="<?php echo $spousename; ?>" />

<table>
		
	<tbody>		
		<tr>
		<td colspan="0"><span style="color:#D77600; font-size:12pt">			
				<?php echo "Spouse ". $order; ?></span></td>
		</tr>
		
	</tbody>
		<tr>
			<td class="tdback"><?php echo "Spouse ". $order; ?></td>
			<td class="tdfront"><span style="color:#777777">(Spouse Name-2nd name or Father's Name)<br/></span><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][firstname]" value="<?php echo $spouse['firstname'];?>"></td>
			<?php if ($EventDisplay != "") {  ?>
			<td valign="bottom" class="tdback"><?php echo $EventDisplay; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][event]" value="<?php echo $spouseSpEvent;?>" /></td>
			<?php } else { ?><td class="tdback"></td><class="tdfront"></td><?php }?>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][surname]" value="<?php echo $spouse['lastname'];?>" size="30"/></td>
			<td class="tdback"><?php echo "Living / Deceased"; ?></td>
			<td valign="bottom" class="tdfront">
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
		<tr>		
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][birthdate]" value="<?php echo $spouse['birthdate'];?>"</td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][birthplace]" value="<?php echo $spouse['birthplace'];?>" size="10"/></td>
		</tr>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][deathdate]" value="<?php echo $spouse['deathdate'];?>" /></td>
			<td valign="middle" class="tdback"><?php echo "Cause of Death". '<br><br/>'. "Place"; ?></td>
			<td valign="middle" class="tdfront"><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][cause]" value="<?php echo $spouse_cause_of_death;?>"><br/><br/><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][deathplace]" value="<?php echo $spouse['deathplace'];?>" /></td></tr>
		
		<tr>
		</tr>
		<tr>
		<td class="tdback"><?php echo "Married" ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][marrdate]" value="<?php echo $spousemarrdate;?>" /></td>
			<td class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][marrplace]" value="<?php echo $spousemarrplace;?>" /></td>
		</tr>
			<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][familyID]" value="<?php echo $family['familyID'] ?>" />
			
			<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][order]" value="<?php echo $order ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][personID]" value="<?php echo $spouse['personID'] ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][sex]" value="<?php echo $spouse['sex'] ?>" />
			<!--
			<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][living]" value="<?php echo $spouse['living'] ?>" />
			-->
			<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][famc]" value="<?php echo $spouse['famc'] ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][husband]" value="<?php echo $spousehusband ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][wife]" value="<?php echo $spousewife ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][husborder]" value="<?php echo $spousehusborder ?>" />
			<input type="hidden" name="family[<?php echo $order; ?>][spouse][<?php echo $index; ?>][wifeorder]" value="<?php echo $spousewifeorder ?>" />
			
		</table>
			
		<?php
		endforeach; 
		?> 
  
  
  
  
  </div>
  <div id="step-4">
      <h2 class="StepTitle">Update Details of Children </h2>   
       <!-- step content --> 
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
			inp.name=inp.name.replace(/\]\[\d\]/g, '][' + idx + ']');
                        if ($(inp).prop('type') === 'hidden') {
                            continue;
                        }
                        $(inp).val(null);
		}
		var selects=newclone.getElementsByTagName('select'), sel, i=0;
                i=0;
		while(sel=selects[i++]) {
			sel.name=sel.name.replace(/\]\[\d\]/g, '][' + idx + ']');
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
				
				$spouse = $tngcontent->getPerson($family['husband']);
				} else {
					$spouse = $tngcontent->getPerson($family['wife']);
				}
				$deathdate = $spouse['deathdate'];
				$spouseRow = $tngcontent->getSpEvent($spouse['personID']);
				$spouseSpEvent = $spouseRow['info'];
				$spouseName = $spouse['firstname'] . $spouse['lastname'];
								
				$children = $tngcontent->getchildren($family['familyID']);
			
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
<table>
		
			
		<tr>
		<td><?php //echo "Spouse ". $order?></td>
		<td colspan="0"><span style="color:#D77600; font-size:14pt">			
				<?php echo $spouse['firstname'].$spouse['lastname']; ?></span></td>
		
	
		</tr>
		<tr><td colspan="2">
		<button class="js-addChild-edit" data-id="<?php echo $order; ?>">add child</button>
		
<br/>
		<table id="children_<?php echo $order; ?>">	
	<thead>
		<tr>	 
		<td>First Name </td>	
		<td>Last Name</td>
		<td>Sex</td>
		<td>Date Born</br>dd mmm yyyy</td>
		<td>Place Born</td>
		<td>Date Died</br>dd mmm yyyy</td>
		<td>Place Died</td>
		<td>Living</td>
		<td>Cause of Death</td>
		</tr>
	</thead>
	<tbody>
<?php
	foreach ($children as $index => $child):
	
		$classes = array('child');
		$childPerson = $tngcontent->getPerson($child['personID']);
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
		
		if ($child['haskids']) {
			$classes[] = 'haskids';
		}

		// init sex selector
		$Msex = $Fsex = $Usex = "";
		
		if( $childsex == 'M' ) $Msex = "selected=\"selected\"";
		elseif( $childsex['sex'] == 'F' ) $Fsex = "selected=\"selected\"";
		else $Usex = "selected=\"selected\"";
// Child - Special Event
				$fathereventRow = $tngcontent->getSpEvent($family['husband']);
				$fatherevent = $fathereventRow['info'];
				if ($childFirstName !== '')
				{
				$childRow = $tngcontent->getSpEvent($child['personID']);
				
				$childevent = $childRow['info'];
				$childped = $tngcontent->getSpEvent($family['husband']);
				$fatherChildevent = $childped['info'];
				}
				/* This is to parse father value to child
				if ($childevent = "" OR $childevent = "Unknown") {
				$childevent = $fatherChildevent;
				}
				*/
//get Cause of Death for child
				$childcause = "";
				$childRow = $tngcontent->getCause($child['personID']);
				if ($childRow['eventtypeID'] == "0") {
					$childcause = $childRow['cause'];
				}
?>
		<tr class="child">
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][personID]" value="<?php echo $child['personID'] ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][familyID]" value="<?php echo $family['familyID'] ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][order]" value="<?php echo $child['ordernum'] ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][spouseorder]" value="<?php echo $family[$sortBy] ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][event]" value="<?php echo $childevent ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][haskids]" value="<?php echo $child['haskids'] ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][parentorder]" value="<?php echo $child['parentorder'] ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][living]" value="<?php echo $child['living'] ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][famc]" value="<?php echo $family['familyID'] ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][living]" value="0" /></td>
		
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][firstname]" value="<?php echo $childFirstName;?>" size="10"/></td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][surname]" value="<?php echo $childLastName;?>" size="10"/></td>	
		<td>           <select name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][sex]" size"3">
		
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
		$childID = "NewChild-". $childorder;
		//echo $childID. "order=". $order;
		?>
	<script>
initChildren(<?php echo $order; ?>);
</script>	
		<tr class="child">
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][personID]" value="<?php echo $childID ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][familyID]" value="<?php echo $family['familyID'] ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][order]" value="<?php echo $childorder ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][order]" value="<?php echo $family[$sortBy] ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][event]" value="<?php echo $childevent ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][haskids]" value="0" size="12"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][parentorder]" value="<?php echo $child['parentorder'] ?>" />
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][famc]" value="<?php echo $family['familyID'] ?>"/>
		<input type="hidden" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][living]" value="0" /></td>
		
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][firstname]" value="" size="10"/></td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][surname]" value="<?php echo $husbandname; ?>" size="10"/></td>	
		<td> <select name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][sex]" size"3">
		
		<option value="M">M</option>
		<option value="F">F</option>
		
		</select>
		</td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][birthdate]" value="" size="08"/></td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][birthplace]" value="" size="10"/></td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][deathdate]" value="" size="08"/></td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][deathplace]" value="" size="10"/></td>
		<td><input type="checkbox" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][living]" value="1" checked /></td>
		<td><input type="text" name="family[<?php echo $order; ?>][child][<?php echo $index; ?>][cause]" value="" size="10"/></td>
		<?php 
		$childorder += 1;
		$childID = "NewChild-". $childorder;
		//echo $childID. "order=". $order
		?>
		</tr>
	</table>	

	</td></tr>

<?php
	
	endforeach;
?>
	</tbody>
	</table>   
  </div>
</div>
		
</form>
<div style="clear:both"></div>
