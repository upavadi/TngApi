<!-- Submit changes via emmail -->			
			<?php
				
				$tngcontent = Upavadi_TngContent::instance()->init();
				
				 //get and hold current user
				$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
				$person = $tngcontent->getPerson($currentperson);
				$currentuser = ($person['firstname']. $person['lastname']);
				
				?>
			
				<a href="?personId=<?php echo $person['personID']; ?>"><span style="color:#D77600; font-size:14pt">			
				<?php echo "Welcome ". $currentuser; ?></span>
				</a>
	
				<?php
//get person details
				$person = $tngcontent->getPerson($personId);
				$person_birthdate = $person['birthdate'];
				$person_birthdatetr = ($person['birthdatetr']);
				$person_birthplace = $person['birthplace'];
				$person_deathdate = $person['deathdate'];
				$person_deathdatetr = ($person['deathdatetr']);
				$person_deathplace = $person['deathplace'];
				$person_name = $person['firstname'];
				$person_surname = $person['lastname'];
							
//get Person gotra
				$personRow = $tngcontent->getGotra($person['personID']);
				$person_gotra = $personRow['info'];

				
// title for page	
				?>
				<br/><span float="left" style= "font-type:bold; font-size:12pt">			
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
//Person dates and places		
				if ($person['birthdate'] == '') {
			$person_birthdate = "Unknown";
			}
			else {
			$person_birthdate == $person['birthdate'];
			}
			
				if ($person['living'] == '0' AND $person['deathdatetr'] !== '0000-00-00') 
					{
					$person_deathdate = " died: " . $person['deathdate'];
					} else {
					$person_deathdate = " died: date unknown";
					}
					if ($person['living'] == '1') {
					$person_deathdate = "  (Living)";
				}
			if ($person['birthplace'] == '') {
			$person_birthplace = "Unknown";
			}
			else {
			$person_birthplace == $person['birthplace'];
			}
			if ($person['deathplace'] == '') {
			$person_deathplace = "Unknown";
			}
			else {
			$person_deathplace == $person['deathplace'];
			}	
				
//get familyuser
				if ($person['sex'] == 'M') {
					$sortBy = 'husborder';
				} else if ($person['sex'] == 'F') {
					$sortBy = 'wifeorder';
				} else {
					$sortBy = null;
				}
			
			$families = $tngcontent->getFamilyUser($person['personID'], $sortBy);
				
			?>		




<!--------Jquery smart wizard --------->
<html>
<head>
<script type="text/javascript" src="/wordpress/wp-content/plugins/tng-api/js/jquery-2.0.0.min.js"></script>
<link href="/wordpress/wp-content/plugins/tng-api/css/smart_wizard.css" rel="stylesheet" media="all" type="text/css">
<script type="text/javascript" src="/wordpress/wp-content/plugins/tng-api/js/jquery.smartWizard.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
      // Initialize Smart Wizard
        $('#wizard').smartWizard();
  });  
</script>
</head>
<body>
<style type="text/css" media="all">
@import "/wordpress/wp-content/plugins/tng-api/css/smart_wizard.css";
</style>

<div id="wizard" class="swMain">
  <ul>
    <li><a href="#step-1">
          <label class="stepNumber">1</label>
          <span class="stepDesc">
             Personal Data<br />
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
	  <span style="color:#D77600; font-size:10pt"></br><?php echo "Make changes below and then press NEXT. Do not Change or Refresh the page until you have submitted the changes by clicking on FINISH below"; ?>
       <!-- step content -->
	   <table class="form-table">
	<tbody>
		<tr>
			<td valign="bottom" class="tdback"><?php echo "Name"; ?></td>
			<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="name" value="<?php echo $person_name;?>" size="30"/></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" gotra="Gotra" value="<?php echo $person_gotra;?>" /></td></tr>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" surname="surname" value="<?php echo $person_surname;?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
					
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" bday="B.day" value="<?php echo $person_birthdate;?>"</td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<?php 
			
			?>
			<td valign="bottom" class="tdfront"><input type="text" bplace="B.Place" value="<?php echo $person_birthplace;?>"</td>
		<tr>	
			<td valign="bottom"class="tdback"><?php echo "Died"; ?></td>
			<td  valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" Dday="D.day" value="<?php echo $person_deathdate;?>"></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			
			<td valign="bottom" class="tdfront"><input type="text" dplace="D.Place" value="<?php echo $person_deathplace;?>" /></td></tr>
			
			
		</tr>
	</tbody>
</table>
  </div>
  <div id="step-2">
      <h2 class="StepTitle">Update Details of Parents for <?php echo $person_name.$Person_surname;?></h2> 
       <!-- step content -->
<?php
			$parents = '';
			$parents = $tngcontent->getFamilyById($person['famc']);

			if ($person['famc'] !== '' and $parents['wife'] !== '') {
			$mother = $tngcontent->getPerson($parents['wife']);
			}
			if ($person['famc'] !== ''and $parents['husband'] !== '') {
			$father = $tngcontent->getPerson($parents['husband']);
			}
			
			$parents_marrdate = $parents['marrdate'];
			
			$parents_marrplace == $parents['marrplace'];
			if ($parents_marrdate == "") {
			$parents_marrdate = "Unknown";
			}
			if ($parents_marrplace == '') {
			$parents_marrplace = "Unknown";
			}
			?>
	
			<?php 	
				//Father - get Birth date and place
				$father_firstname = $father['firstname'];
				$father_lastname = $father['lastname'];
				$father_name = $father['firstname']. $father['lastname'];
				
				if ($father_name !== '')
				{	
										
					if ($father['birthdate'] !== '') 
								{
								$father_birthdate = $father['birthdate'];
								} else {
								$father_birthdate = "Unknown";
								}
					if ($father['birthplace'] !== '') 
								{
								$father_birthplace = $father['birthplace'];
								} else {
								$father_birthplace = "Unknown";
								}			
					
				}
				
								
// Father - Gotra
				$fatherRow = $tngcontent->getGotra($father['personID']);
				$father_gotra = $fatherRow['info'];
				
//Father - get death date and place				
				if ($father_name !== '')
				{	
					if ($father['living'] == '0') 
						{
								if ($father['deathdate'] !== '') 
								{
								$father_deathdate = " died: ". $father['deathdate'];
								}
								if ($father['deathdate'] == '')  
								{
								$father_deathdate = " died: date unknown";
								}
								if ($father['deathplace'] == '')
								{
								$father_deathplace = "Unknown";
								} else {
								$father_deathplace = $father['deathplace'];
						}
					}
				}
					if ($father['living'] == '1')
							{
							$father_deathdate = "  (Living)";
							$father_deathplace = "(Living)" ;
							}
							
				

				
				//Mother - get Birth date and place
				$mother_firstname = $mother['firstname'];
				$mother_lastname = $mother['lastname'];
				$mothername = $mother['firstname']. $mother['lastname'];
				
				if ($mother_name !== '')
				{	
										
					if ($mother['birthdate'] !== '') 
								{
								$mother_birthdate = $mother['birthdate'];
								} else {
								$mother_birthdate = "Unknown";
								}
					if ($mother['birthplace'] !== '') 
								{
								$mother_birthplace = $mother['birthplace'];
								} else {
								$mother_birthplace = "Unknown";
								}			
					
				}
				
								
				//Mother - get death date and place
				$motherRow = $tngcontent->getGotra($mother['personID']);
				$mother_gotra = $motherRow['info'];
				if ($mother_name !== '')
				{	
					if ($mother['living'] == '0') 
							{
								if ($mother['deathdate'] !== '') 
								{
								$mother_deathdate = " died: ". $mother['deathdate'];
								}
								if ($mother['deathdate'] == '')  
								{
								$mother_deathdate = " died: date unknown";
								}
								if ($mother['deathplace'] == '')
								{
								$mother_deathplace = "Unknown";
								} else {
								$mother_deathplace = $mother['deathplace'];
								}
							}
					if ($mother['living'] == '1')
							{
							$mother_deathdate = "  (Living)";
							$mother_deathplace = "  (Living)";
							}
				}
					
			if ($mother['birthplace'] == '') {
				$mother['birthplace'] = "Unknown";
			}
			
			if ($mother['deathplace'] == '') {
			$mother['deathplace'] = "Unknown";
			}
		
		?>
		
	
				
<table class="form-table">
		
	<tbody>		
		
		
		<tr>
		
			<td valign="bottom" class="tdback">Father</td>
			<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" fathername="fathername" value="<?php echo $father_firstname;?>"></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" fathergotra="fatherGotra" value="<?php echo $father_gotra;?>" /></td></tr>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" fathersurname="fathersurname" value="<?php echo $father_lastname;?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" fatherbday="fatherB.day" value="<?php echo $father_birthdate;?>" size="10"/></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			
			<td valign="bottom"class="tdfront"><input type="text" fatherbplace="fatherB.Place" value="<?php echo $father_birthplace;?>" /></td>
		<tr>	 
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" fatherDday="fatherD.day" value="<?php echo $father_deathdate;?>" /></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" fatherdplace="fatherD.Place" value="<?php echo $father_deathplace;?>" /></td</tr>
			
			
		</tr>
		
		<td valign="bottom" class="tdback">Mother</td>
			<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" mothername="mothername" value="<?php echo $mother_firstname;?>" size="30"/></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" mothergotra="motherGotra" value="<?php echo $mother_gotra;?>" /></td></tr>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" mothersurname="mothersurname" value="<?php echo $mother_lastname;?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" motherbday="motherB.day" value="<?php echo $mother_birthdate;?>" size="10"/></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			
			<td valign="bottom" class="tdfront"><input type="text" motherbplace="motherB.Place" value="<?php echo $mother_birthplace;?>" /></td>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" motherDday="motherD.day" value="<?php echo $mother_deathdate;?>" /></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" motherdplace="motherD.Place" value="<?php echo $mother_deathplace;?>" /></td></tr>
		</tr>
		<tr>
		<td class="tdback"><?php echo "Married" ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" parentmarr.day="parentmarr.day" value="<?php echo $parents_marrdate;?>" /></td>
			
			<td class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" parentmarr.place="parentmarr.Place" value="<?php echo $parents_marrplace;?>" /></td>
		
		</tr>
	</tbody>


</table>
<?php echo $mother_firstname. $mothername; ?> 

	   
  </div>                      
  <div id="step-3">
      <h2 class="StepTitle">Update Details of Spouse(s) for <?php echo $person_name.$person_surname;?></h2>   
       <!-- step content -->
  		<?php
			// Spouses
			foreach ($families as $family):
				
				$spousemarrdate = $family['marrdate'];
				$spousemarrplace = $family['marrplace'];
				$order = null;
				if ($sortBy && count($families) > 1) {
					$order = $family[$sortBy];
				}

				if ($person['personID'] == $family['wife']) {
				
				$spouse = $tngcontent->getPerson($family['husband']);
				} else {
					$spouse = $tngcontent->getPerson($family['wife']);
				}
				
				if ($spouse['living'] == '0' AND $spouse['deathdate'] !== '') 
					{
					$deathdate = " died: " . $spouse['deathdate'];
					} else {
					$deathdate = " died: date unknown";
					}
					if ($spouse['living'] == '1') {
					$deathdate = "  (Living)";
				}
			
				$spouseRow = $tngcontent->getGotra($spouse['personID']);
				$spousegotra = $spouseRow['info'];
				$spouseName = $spouse['firstname'] . $spouse['lastname'];
								
				$children = $tngcontent->getChildren($family['familyID']);
			?>
<table class="form-table">
		
	<tbody>		
		<tr>
		<td colspan="0"><span style="color:#D77600; font-size:12pt">			
				<?php echo "Spouse ". $order; ?></span></td>
		</tr>
	</tbody>

				
		<tr>
			<td class="tdback"><?php echo "Spouse ". $order; ?></td>
			<td class="tdfront"><span style="color:#777777">(Spouse Name-2nd name or Father's Name)<br/></span><input type="text" spousename="spousename" value="<?php echo $spouse['firstname'];?>"></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" spousegotra="spouseGotra" value="<?php echo $spousegotra;?>" /></td>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" spousesurname="spousesurname" value="<?php echo $spouse['lastname'];?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
		</tr>
		<tr>		
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" spousebday="spouseB.day" value="<?php echo $spouse['birthdate'];?>"</td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" spousebplace="spouseB.place" value="<?php echo $spouse['birthplace'];?>" size="10"/></td>
			
		</tr>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" spouseDday="spouseD.day" value="<?php echo $spouse['deathdate'];?>" /></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" spousedplace="spouseD.Place" value="<?php echo $spouse['deathplace'];?>" /></td</tr>
			
			
		<tr>
			
		</tr>
		<tr>
		<td class="tdback"><?php echo "Married" ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" spousemarr.day="spousemarr.day" value="<?php echo $spousemarrdate;?>" /></td>
			
			<td class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" spousemarr.place="spousemarr.place" value="<?php echo $spousemarrplace;?>" /></td>
			
					
		</tr>
		</table>
		<?php
		endforeach; ?> 
  
  
  
  
  </div>
  <div id="step-4">
      <h2 class="StepTitle">Update Details of Children </h2>   
       <!-- step content --> 

<?php
			// Family
			foreach ($families as $family):
				
				$spousemarrdate = $family['marrdate'];
				$spousemarrplace = $family['marrplace'];
				$order = null;
				if ($sortBy && count($families) > 1) {
					$order = $family[$sortBy];
				}

				if ($person['personID'] == $family['wife']) {
				
				$spouse = $tngcontent->getPerson($family['husband']);
				} else {
					$spouse = $tngcontent->getPerson($family['wife']);
				}
				
				if ($spouse['living'] == '0' AND $spouse['deathdate'] !== '') 
					{
					$deathdate = " died: " . $spouse['deathdate'];
					} else {
					$deathdate = " died: date unknown";
					}
					if ($spouse['living'] == '1') {
					$deathdate = "  (Living)";
				}
			
				$spouseRow = $tngcontent->getGotra($spouse['personID']);
				$spousegotra = $spouseRow['info'];
				$spouseName = $spouse['firstname'] . $spouse['lastname'];
								
				$children = $tngcontent->getChildren($family['familyID']);
			?>
<table class="form-table">
		
	<tbody>		
		<tr>
		<td class="tdback"><?php echo "Spouse ". $order?></td>
		<td colspan="0"><span style="color:#D77600; font-size:14pt">			
				<?php echo $spouse['firstname'].$spouse['lastname']; ?></span></td>
		
	
		</tr>
		
			
			
			<?php
				foreach ($children as $child):
				
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
					
					if( $childsex == $text['ws_male'] ) $Msex = "selected=\"selected\"";
					elseif( $child[$i]['sex'] == $text['ws_female'] ) $Fsex = "selected=\"selected\"";
					else $Usex = "selected=\"selected\"";
						
					
					echo $childsex. $text['ws_male']
			
			?>
			<?php 
					if ($childPerson['living'] == '0' AND $childPerson['deathdate'] !== '') 
					{
					$childdeathdate = " died: " . $childPerson['deathdate'];
					} else {
					$childdeathdate = " died: date unknown";
					} 
					if ($childPerson['living'] == '1') {
					$childdeathdate = "  (Living)";
					
				}?>
					
		<tr>
		<td valign="bottom" class="tdback"><?php echo "Child Order ". $child['ordernum']; ?></td>	
			<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="childname" value="<?php echo $childFirstName;?>" size="20"/></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" surname="childsurname" value="<?php echo $childLastName;?>" size="20></td>
			</tr>
			<tr>
			<td class="tdback"></td><td class="tdfront"></td>
			</tr>		
			<tr>	
			<td class="tdback"></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Gotra<br/></span><input type="text" gotra="childgotra" value="<?php echo $father_gotra;?>"</td>
			<td valign="bottom" class="tdfront">
			
			</td>
		</tr>
			<tr>	
			<td class="tdback"></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Date Born(dd mmm yyyy)<br/></span><input type="text" bday="B.day" value="<?php echo $childbirthdate;?>"</td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Place Born(dd mmm yyyy)<br/></span><input type="text" bday="B.day" value="<?php echo $childbirthplace;?>"</td>
		</tr>	
		<tr>	
			<td class="tdback"></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Date Died(dd mmm yyyy)<br/></span><input type="text" bday="B.day" value="<?php echo $childdeathdate;?>"</td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Place Died(dd mmm yyyy)<br/></span><input type="text" bday="B.day" value="<?php echo $childdeathplace;?>"</td>
			
		</tr>
				<?php	
			
				endforeach;
				?>
				</tr>
	<tr>
			</tr>
				
				<?php
				
			endforeach;
			?>
	</body>
	</table>   
  </div>
</div>

</body>
</html>