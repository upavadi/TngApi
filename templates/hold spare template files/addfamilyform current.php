<!-- Submit changes via emmail -->			
<html>
<head>	
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
				<?php echo "ADD Details for the Family of ". $person_name. $person_surname; ?></span>
				
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
			if ($person['sex'] == 'M') {
				$spousesex = "F";
			} else {
				$spousesex = "F";
			}	
			echo "sex= ". $spousesex;
			?>		



</html>
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
             Spouse<br />
             <small>ADD </small>
          </span>
      </a></li>
	  
    <li><a href="#step-2">
          <label class="stepNumber">2</label>
          <span class="stepDesc">
             Children<br />
             <small>ADD </small>
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
      <h2 class="StepTitle">Add Details of Spouse for <?php echo $person_name.$person_surname;?></h2> 
	  <span style="color:#D77600; font-size:10pt"></br><?php echo "Make changes below and then press NEXT. Do not Change or Refresh the page until you have submitted the changes by clicking on FINISH below"; ?>
       <!-- step content -->
	<?php
			// Spouses
			foreach ($families as $family):
				
				$order = null;
				if ($sortBy && count($families) > 0) {
					$order = $family[$sortBy];
				
				}
			endforeach;
	$spouseorder = ($order+1);				
	?>
	<span style="color:#D77600; font-size:12pt">			
			<?php echo "ADD Spouse ". $spouseorder; ?><br/></span>
			
	<table class="form-table">
	<tbody>
	
<!-- THIS LINE FOR TEST ONLY---------------->
	
	<form action = "../wordpress/wp-content/plugins/tng-api/templates/processfamily2.php" method = "GET">	
	
	<input type="hidden" name="order" value="<?php echo $spouseorder; ?>" />
			
		<tr>	
			<td class="tdback" width="auto">Name of </br><?php echo "Spouse ". ($order+1); ?></td>
		<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="spousefirstname" value="spouse first name" size="30"/></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" name="spousesurname" value="" size="30"/></td>
			
		</tr>
			<tr>	
			<td class="tdback"><br/>Birth</br>Details</td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Date Born(dd mmm yyyy)<br/></span><input type="text" name="spousebirthdate" value="" size="30"/></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Place Born(dd mmm yyyy)<br/></span><input type="text" name="spousebirthplace" value="" size="30"/></td>
		</tr>	
		<tr>	
			<td class="tdback"><br/>Death</br>Details</td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Date Died(dd mmm yyyy)<br/></span><input type="text" name="spousedeathdate" value="" size="30"/></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Place Died(dd mmm yyyy)<br/></span><input type="text" name="spousedeathplace" value="" size="30"/></td>
		</tr>
		
		<tr>
		<td class="tdback"><br/>Gender</br>Living</td>
		<td valign="bottom" class="tdfront"><span style="color:#777777">Gender<br/></span><select name="spousesex">
		<option value="M">Male</option>
		<option value="F">Female</option>
		</select>
		<td valign="bottom" class="tdfront"><span style="color:#777777">Living / Deceased<br/></span>
		<select name="spouseliving" >
		<option value="1">Living</option>
		<option value="0">Deceased</option>
		<option value="U">unknown</option>
		</select>
		
		</td>
		</tr>
	</tbody>
	</table>
	<input type="submit" value="submit">
	<input type="reset" value="clear">
	</form>
			
	</div>
	<div id="step-2">
      <h2 class="StepTitle">Children for <?php echo $person_name.$Person_surname;?></h2> 
       <!-- step content -->
	   <table>	
		<tr>	
		<td>First Name</td>	
		<td>Last Name</td>
		<td>Sex</td>
		<td>Date Born</td>
		<td>Place Born</td>
		<td>Date Died</td>
		<td>Place Died</td>
		<td>Living</td>
		</tr>
		<tr>
		<td><input type="text" name="Childfirstname" value="" size="12"/></td>
		<td><input type="text" name="Childsurname" value="" size="12"/></td>	
		<td> <select name="childsex" size"3">
		<option value="M">M</o ption>
		<option value="F">F</option>
		</select>
		</td>
		<td><input type="text" name="Childdateborn" value="" size="10"/></td>
		<td><input type="text" name="Childplaceborn" value="" size="10"/></td>
		<td><input type="text" name="Childdatedied" value="" size="10"/></td>
		<td><input type="text" name="Childplacedied" value="" size="10"/></td>
		<td><select name="childliving" >
		<option value="1">L</option>
		<option value="0">D</option>
		<option value="U">u</option>
		</select>
</tr>
			
	</tbody>
	</table>
  </div>                      
  <div id="step-3">
      <h2 class="StepTitle">Update Details of Spouse(s) for <?php echo $person_name.$person_surname;?></h2>   
       <!-- step content -->
  			
  </div>
  <div id="step-4">
      <h2 class="StepTitle">Update Details of Children </h2>   
       <!-- step content --> 

  
  </div>
</div>
 
 
</body>
</html>