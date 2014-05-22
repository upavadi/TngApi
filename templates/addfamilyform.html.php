<!-- Submit changes via emmail -->			
	<?php
				
				$tngcontent = Upavadi_tngcontent::instance()->init();
				
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
				$personRow = $tngcontent->getgotra($person['personID']);
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
			
			$families = $tngcontent->getfamilyuser($person['personID'], $sortBy);
			if ($person['sex'] == 'M') {
				$spousesex = "F";
			} else {
				$spousesex = "F";
			}	
			?>		




<!--------Jquery smart wizard ---------
<script type="text/javascript" src="/wordpress/wp-content/plugins/tng-api/js/jquery-2.0.0.min.js"></script>
--------->
<link href="/wordpress/wp-content/plugins/tng-api/css/smart_wizard.css" rel="stylesheet" media="all" type="text/css">
<script type="text/javascript" src="/wordpress/wp-content/plugins/tng-api/js/jquery.smartWizard.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
      // Initialize Smart Wizard
        $('#wizard-add').smartWizard({
		// Properties
    keyNavigation: false, // Enable/Disable key navigation(left and right keys are used if enabled)
    onFinish: function () {
	$('#add-family-form').submit();
			}
		});
   });  
</script>
<style type="text/css" media="all">
@import "/wordpress/wp-content/plugins/tng-api/css/smart_wizard.css";
</style> 

<form id="add-family-form" action = "../wordpress/wp-content/plugins/tng-api/templates/processfamily-add.php" method = "POST">
<input type="hidden" name="User" value="<?php echo $currentuser; ?>" />
<input type="hidden" name="personId" value="<?php echo $person['personID']; ?>" />
<div id="wizard-add" class="swMain">
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
            Notes<br />
             <small>ADD </small>
          </span>                   
       </a></li>
    
  </ul>

  <div id="step-1">   
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
	<p><h2 class="StepTitle">Add Details of Spouse - <?php echo $spouseorder; ?> for <?php echo $person_name.$person_surname;?></h2> 
	  <span style="color:#D77600; font-size:10pt"></br><?php echo "Add Details of Spouse for ". $person_name.$person_surname;?> below and then press NEXT to add Children. If there are no children, go ahead and press NEXT to enter Notes about the spouse.Do not Change or Refresh the page until you have submitted the changes by clicking on FINISH below.</p>
    <p>At present, I do not have the facility to ADD Parents for the Spouse. I request that you visit again, once the data for the Spouse has been entered. You would, then be able to enter details about Parents.</br>
	I look forward toyour feedback on this.
	</P>
	
	<table class="form-table">
	<tbody>
	
	
	
	<input type="hidden" name="order" value="<?php echo $spouseorder; ?>" />
			
		<tr>	
			<td class="tdback" width="auto">Name of </br><?php echo "Spouse ". ($order+1); ?></td>
		<td class="tdfront" colspan="2"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="spousefirstname" value="" size="30"/></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" name="spousesurname" value="" size="30"/></td>
			
		</tr>
			<tr>	
			<td class="tdback"><br/>Birth</br>Details</td>
			<td valign="bottom" class="tdfront" colspan="2"><span style="color:#777777">Date Born(dd mmm yyyy)<br/></span><input type="text" name="spousebirthdate" value="" size="30"/></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Place Born<br/></span><input type="text" name="spousebirthplace" value="" size="30"/></td>
		</tr>	
		<tr>	
			<td class="tdback"><br/>Death</br>Details</td>
			<td valign="bottom" class="tdfront" colspan="2"><span style="color:#777777">Date Died(dd mmm yyyy)<br/></span><input type="text" name="spousedeathdate" value="" size="30"/></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Place Died<br/></span><input type="text" name="spousedeathplace" value="" size="30"/></td>
		</tr>
		
		<tr>
		<td class="tdback"><br/>Gender<br/>Gotra</br>Living</td>
		<td valign="bottom" class="tdfront"><span style="color:#777777">Gender<br/></span><select name="spousesex">
		<option value="M">Male</option>
		<option value="F">Female</option>
		</select>
		<td valign="bottom" class="tdfront"><span style="color:#777777">Gotra<br/></span><input type="text" name="spousegotra" value="" size="20"/></td>
			
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
	</div>
	<div id="step-2">
      <h2 class="StepTitle">Children for <?php echo $person_name.$person_surname;?></h2> 
       <!-- step content -->
	</br>
	<p><span style="color:#D77600; font-size:10pt"><?php echo "Enter Details of the First Child of ". $person_name.$person_surname;?>, below. Click on <b>Add Child</b> to add children. Click on NEXT below when done.</span></p>
	 <p><span style="color:#D77600; font-size:10pt">At present, I do not have the facility to ADD Spouse & Family details for the Children. I request that you visit again, once the data for the Children has been entered. You would, then be able to enter details about Family of each child.</P>
	
	  
	
	<button class="js-addChild">Add Child</button>	   
	<table id="children">	
	<thead>
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
	</thead>
	<tbody>
		<tr class="child">
		<td><input type="text" name="child[0][firstname]" value="" size="12"/></td>
		<td><input type="text" name="child[0][surname]" value="" size="12"/></td>	
		<td> <select name="child[0][sex]" size"3">
		<option value="M">M</option>
		<option value="F">F</option>
		</select>
		</td>
		<td><input type="text" name="child[0][dateborn]" value="" size="10"/></td>
		<td><input type="text" name="child[0][placeborn]" value="" size="10"/></td>
		<td><input type="text" name="child[0][datedied]" value="" size="10"/></td>
		<td><input type="text" name="child[0][placedied]" value="" size="10"/></td>
		<td><input type="checkbox" name="child[0][living]" value="1" checked /></td>
		</tr>
	</tbody>
	</table>
  </div>                      
  <div id="step-3">
    
       <!-- step content -->
		<h2 class="StepTitle">Notes for <?php echo "Spouse ". ($order+1); ?></h2>
	</br><p><span style="color:#D77600; font-size:10pt"><?php echo "Enter Notes about Spouse no: ". ($order+1). " of ". $person_name.$person_surname. ". When ready Click on <b>FINISH</b> below to send the details to me. I shall let you know by email, once the details are entered in the Family Tree.";?><br/><b>Thank you for your help.</b> </span></p>
	 
	
	<body>
	
	<?php 			
		//get All notes
		
		$allnotes = $tngcontent->getnotes($personId);
		
		//var_dump ($allnotes);
		$note_generalID = "Personal Note";
		$note_nameID = "About Person's Name";
		$note_birthID = "About Person's Birth";
		$note_deathID = "About Person's Death";
		$note_funeralID = "About Funeral, Cremation / Burial";
											
		foreach($allnotes as $PersonNote):
		if ($PersonNote['eventID'] == null) {
					$note_general = $PersonNote['note'];
					}
		if ($PersonNote['eventID'] == "NAME") {
					$note_name = $PersonNote['note'];
					}
		if ($PersonNote['eventID'] == "BIRT") {
					$note_birth = $PersonNote['note'];
					}
		
		if ($PersonNote['eventID'] == "DEAT") {
					$note_death = $PersonNote['note'];
					}
		if ($PersonNote['eventID'] == "BURI") {
					$note_funeral = $PersonNote['note'];
					}			
	?>

	<?php endforeach; ?>
		
		<p>
			<span style="font-size:14pt"><b>
			<?php echo $note_generalID;?></b></span></a></br>
			<textarea name="note_general" rows="3" cols="100">
			</textarea>
		</p>
		<p>
			<span style="font-size:14pt"><b>
			<?php echo $note_nameID;?></b></span></a></br>
			<textarea name="note_name" rows="3" cols="100">
			</textarea>
		</p>
		<p>
			<span style="font-size:14pt"><b>
			<?php echo $note_birthID;?></b></span></a></br>
			<textarea name="note_birth" rows="3" cols="100">
			</textarea>
		</p>
		<p>
							
		<span style="font-size:14pt"><b>
			<?php echo $note_deathID;?></b></span></a></br>
			<textarea name="note_death" rows="3" cols="100">
			</textarea>
		</p>
		<p>
			<span style="font-size:14pt"><b>
			<?php echo $note_funeralID;?></b></span></a></br>
			<textarea name="note_funeral" rows="3" cols="100">
			</textarea>
		</p>
		
	  			
  </div>
  
</div>
</form>
 <script>
var clone;
function cloneRow()  { // create clone of empty child line for use during session
    var rows=$('#children').find('tr.child');
    var idx=rows.length;
    clone=rows[idx-1].cloneNode(true);    
}
cloneRow();
$('.js-addChild').click(addRow);
function addRow(evt) {
	evt.stopPropagation();
	evt.preventDefault();
    var newclone = clone.cloneNode(true);
    var rows=$('#children').find('tr.child');
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
    while(inp=inputs[i++]) {
        inp.name=inp.name.replace(/\d/g, idx );
		inp.value = null;
    }
    var selects=newclone.getElementsByTagName('select'), sel, i=0;
    while(sel=selects[i++]) {
        sel.name=sel.name.replace(/\d/g, idx );
		sel.selectedItem = 0;
    }
var tbo=document.getElementById('children').getElementsByTagName('tbody')[0];
tbo.appendChild(newclone);
}
function deleteLastRow() {
    var tbo=document.getElementById('children').getElementsByTagName('tbody')[0];
    var rows = tbo.getElementsByTagName('tr');
    tbo.removeChild(rows[rows.length-1] );    
    if(rows.length < 1) {
        addRow();
    }
}
</script> 
<div style="clear: both;"></div>