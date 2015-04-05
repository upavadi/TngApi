<!-- Submit changes via emmail -->			

<?php
				
				$tngcontent = Upavadi_tngcontent::instance()->init();
				
				 //get and hold current user
				$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
				//$person = $tngcontent->getPerson($currentperson);
				$currentuser = ($person['firstname']. $person['lastname']);
				$currentuserLogin = wp_get_current_user();
				$UserLogin = $currentuserLogin->user_login;
				?>
			
				<a href="?personId=<?php echo $person['personID']; ?>"><span style="color:#D77600; font-size:14pt">			
				<?php echo "Welcome ". $currentuser; ?></span>
				</a>
	
				<?php
				
//get person details
				$person = $tngcontent->getPerson($personId, $tree);
				$person_birthdate = $person['birthdate'];
				$person_birthdatetr = ($person['birthdatetr']);
				$person_birthplace = $person['birthplace'];
				$person_deathdate = $person['deathdate'];
				$person_deathdatetr = ($person['deathdatetr']);
				$person_deathplace = $person['deathplace'];
				$person_name = $person['firstname'];
				$person_surname = $person['lastname'];
				$person_gedcom = $person['gedcom'];	
	
//get Person SpecialEvent;
				$personRow = $tngcontent->getSpEvent($person['personID'], $tree);
				$person_SpEvent = $personRow['info'];
				$EventDisplay = $personRow['display'];
//get Description of Event type
				$EventRow = $tngcontent->getEventDisplay($event['display']);	
				$EventDisplay = $EventRow['display'];
				$EventID = $EventRow['eventtypeID'];
				
// title for page	
				?>
				<br/><span float="left" style= "font-type:bold; font-size:12pt">			
				<?php echo "ADD Details for the Family of ". $person_name; ?></span>
				
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
				
// get familyuser

if ($person['sex'] == 'M') {
	$sortBy = 'husborder';
} else if ($person['sex'] == 'F') {
	$sortBy = 'wifeorder';
} else {
	$sortBy = null;
}
if ($person['sex'] == 'M') {
$spousesex = "F";
$spousehusband = $person['personID'];
$spousewife = "NewWife";
$husbandlastname = $person['lastname'];
} else {
$spousesex = "M";
$spousewife = $person['personID'];
$spousehusband = "NewHusband";
}

echo $husbandlastname;
//get husband special event
			if ($spousehusband != "NewHusband") {
			$husbandRow = $tngcontent->getSpEvent($person['personID'], $tree);
			$husbandSpEvent = $husbandRow['info'];
			
			}
				
		?>		




<!--------Jquery smart wizard ---------
<script type="text/javascript" src="<?php echo plugins_url('js/jquery-2.0.0.min.js', dirname(__FILE__)); ?>"></script>
--------->

<script type="text/javascript" src="<?php echo plugins_url('js/jquery.smartWizard.js', dirname(__FILE__)); ?>"></script>

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

</style> 

<form id="add-family-form" action = "<?php echo plugins_url('templates/processfamily-add.php', dirname(__FILE__)); ?>" method = "POST">
<input type="hidden" name="User" value="<?php echo $UserLogin; ?>" />
<input type="hidden" name="personID" value="<?php echo $person['personID']; ?>" />
<input type="hidden" name="gedcom" value="<?php echo $person_gedcom; ?>" />
<input type="hidden" name="person[famc]" value="<?php echo $person['famc']; ?>" />
<input type="hidden" name="person[firstname]" value="<?php echo $person['firstname']; ?>" />
<input type="hidden" name="person[surname]" value="<?php echo $person['lastname']; ?>" />
<input type="hidden" name="person[gedcom]" value="<?php echo $person['gedcom']; ?>" />
<input type="hidden" name="EventID" value="<?php echo $EventRow['eventtypeID']; ?>" />
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
			$families = $tngcontent->getfamilyuser($person['personID'], $tree,  $sortBy);
			foreach ($families as $family):
				
				$order = null;
				if ($sortBy && count($families) > 0) {
					$order = $family[$sortBy];
						
				}
			
			endforeach;
	
	
	$spouseorder = ($order+1);
	if ($spousehusband == "NewHusband") {
		$spousehusbandorder = "1";
		$spousewifeorder = $spouseorder;
		} else {
		$spousehusbandorder = $spouseorder;
		$spousewifeorder = "1";
	}
	$spouseID = "NewSpouse". $spouseorder;
	if ($person['sex'] == 'M') {
	$sortBy = 'husborder';
} else if ($person['sex'] == 'F') {
	$sortBy = 'wifeorder';
} else {
	$sortBy = null;
}

if ($person['sex'] == 'M') {
$spousesex = "F";
$spousewife = $spouseID;
$spousehusband = $person['personID'];
} else {
$spousesex = "M";
$spousewife = $person['personID'];
$spousehusband = $spouseID;
}
	?>
	<p><h2 class="StepTitle">Add Details of Spouse - <?php echo $spouseorder; ?> for <?php echo $person_name.$person_surname;?></h2> 
	<span style="color:#D77600; font-size:10pt"></br><?php echo "Add Details of Spouse for ". $person_name.$person_surname;?> below and then press NEXT to add Children. If there are no children, go ahead and press NEXT to enter Notes about the spouse.Do not Change or Refresh the page until you have submitted the changes by clicking on SAVE below.</p>
	<table class="form-table">
	<tbody>
	<input type="hidden" name="order" value="<?php echo $spouseorder; ?>" />
			
		<tr>	
			<td class="tdback">Name of </br><?php echo "Spouse ". ($order+1); ?></td>
			<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="spouse[firstname]" value="" size="30"/></td>
			<td valign="bottom" class="tdback"><?php echo $EventDisplay; ?></td>
			<?php if ($EventDisplay != "") {  ?>
			<td valign="bottom" class="tdfront"><input type="text" name="spouse[event]" value="" /></td>
			<?php } else { ?><td></td><?php }?> 
		</tr>
		<tr>
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" name="spouse[surname]" value="" size="30"/></td>
			<td class="tdback">Gender</td>
			<td valign="bottom" class="tdfront"><select name="spouse[sex]">
			<?php
			if ($person['sex'] == 'F') { 
			echo '<option value="M" selected>Male</option>'; 
			} else {
			echo '<option value="M">Male</option>';
			}
			
			if ($person['sex'] == 'M') { 
			echo '<option value="F" selected>Female</option>'; 
			} else {
			echo '<option value="F">Female</option>';
			}
			?>
			</select>
		</tr>	
		<tr>	
			<td class="tdback"><br/>Born</td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">Date Born(dd mmm yyyy)<br/></span><input type="text" name="spouse[birthdate]" value="" size="10"/></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" name="spouse[birthplace]" value="" size="30"/></td>
		</tr>	
		<tr>	
			<td valign="top" class="tdback"><br/>Living / Deceased<br/><br/>Died</td>
			<td valign="bottom" class="tdfront"><br/>
			<select name="spouse[living]" >
			<option value="1">Living</option>
			<option value="0">Deceased</option>
			<option value="U">unknown</option>
			</select>
		<br/><br/><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="spouse[deathdate]" value="" size="10"/>
			</td>
			
			
			<td valign="middle" class="tdback"><?php echo "Cause of Death". '<br><br/>'. "Place"; ?></td>
			</td>
			<td valign="middle" class="tdfront"><input type="text" name="spouse[cause]" value=""><br/><br/><input type="text" name="spouse[deathplace]" value="" /></td>
		</tr>
		
		<tr>
		<td class="tdback"><?php echo "Married" ?></td>
			<td valign="middle" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" name="spouse[marrdate]" value="" /></td>
			<td class="tdback"><?php echo "Place"; ?></td>
			<td valign="middle" class="tdfront"><input type="text" name="spouse[marrplace]" value="" /></td>
		</tr>
		<input type="hidden" name="spouse[personID]" value="<?php echo $spouseID ?>" />
		<input type="hidden" name="spouse[husband]" value="<?php echo $spousehusband ?>" />
		<input type="hidden" name="spouse[wife]" value="<?php echo $spousewife ?>" />
		<input type="hidden" name="spouse[husbandorder]" value="<?php echo $spousehusbandorder ?>" />
		<input type="hidden" name="spouse[wifeorder]" value="<?php echo $spousewifeorder ?>" />
		<input type="hidden" name="husband[event]" value="<?php echo $husbandSpEvent ?>" />
	
	</tbody>
	</table>
	</div>
	<div id="step-2">
      <h2 class="StepTitle">Children for <?php echo $person_name.$person_surname;?></h2> 
       <!-- step content -->
	</br>
	<p><span style="color:#D77600; font-size:10pt"><?php echo "Enter Details of the First Child of ". $person_name.$person_surname;?>, below. Click on <b>Add Child</b> to add children. Click on NEXT below when done.</span></p>
	<button class="js-addChild">Add Child</button>	   
	<table width="50%" id="children">	
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
		<td>Cause of Death</td>
		</tr>
	</thead>
	<?php
	$childorder = 0;
	//echo $childorder;
	?>
	<tbody>
		<tr class="child">
		<td><input type="text" name="child[0][firstname]" value="" size="10"/></td>
		<td><input type="text" name="child[0][surname]" value="<?php echo $husbandlastname; ?>" size="10"/></td>	
		<td> <select name="child[0][sex]" size"3">
		<option value="M">M</option>
		<option value="F">F</option>
		</select>
		</td>
		<td><input type="text" name="child[0][birthdate]" value="" size="08"/></td>
		<td><input type="text" name="child[0][birthplace]" value="" size="10"/></td>
		<td><input type="text" name="child[0][deathdate]" value="" size="08"/></td>
		<td><input type="text" name="child[0][deathplace]" value="" size="10"/></td>
		<td><input type="checkbox" name="child[0][living]" value="1" checked />
		<td><input type="text" name="child[0][cause]" value="" size="10" />
		</td>
		</tr>
		
	</tbody >
	</table>
  </div>                      
  <div id="step-3">
    
       <!-- step content -->
		<h2 class="StepTitle">Notes for <?php echo "Spouse ". ($order+1); ?></h2>
	</br><p><span style="color:#D77600; font-size:10pt"><?php echo "Enter Notes about Spouse no: ". ($order+1). " of ". $person_name.$person_surname. ". When ready Click on <b>FINISH</b> below to send the details to me. I shall let you know by email, once the details are entered in the Family Tree.";?><br/><b>Thank you for your help.</b> </span></p>
	 
	
	<body>
	
	<?php 			
		//get All notes
		
		$allnotes = $tngcontent->getnotes($personId, $tree);
		
		$note_general_secret = 0;
		$note_general_ordernum = 999;
		$note_name_secret = 0;
		$note_name_ordernum = 999;
		$note_birth_secret = 0;
		$note_birth_ordernum = 999;
		$note_death_secret = 0;
		$note_death_ordernum = 999;
		$note_funeral_secret = 0;
		$note_funeral_ordernum = 999;
		
		$note_generalID = "Personal Note";
		$note_nameID = "About Person's Name";
		$note_birthID = "About Person's Birth";
		$note_deathID = "About Person's Death";
		$note_funeralID = "About Funeral, Cremation / Burial";
		
		$note_header = array(
		$note_generalID,
		$note_nameID,
		$note_birthID,
		$note_deathID,
		$note_funeralID,);
		
		$xnote_ID = Array(
		"spouse_generalID",
		"spouse_nameID",
		"spouse_birthID",
		"spouse_deathID",
		"spouse_funeralID",);
		
		$xnotes = Array(
		$note_general,
		$note_name,
		$note_birth,
		$note_death,
		$note_funeral,);
		
		$xnote_eventID = Array (
		null,
		"NAME",
		"BIRT",
		"DEAT",
		"BURI",);
		 	
		$xnote_secret = Array(
		$note_general_secret,
		$note_name_secret,
		$note_birth_secret,
		$note_death_secret,
		$note_funeral_secret,);
		
		$xnote_ordernum = Array(
		$note_general_ordernum,
		$note_name_ordernum,
		$note_birth_ordernum,
		$note_death_ordernum,
		$note_funeral_ordernum,);
		
		
	
	
	 ?>
		
	<class = "spouse_note">
		<p>
			<input type="hidden" name="spouse_note[0][xnote_ID]" value="<?php echo $xnote_ID[0] ?>" />
			<span style="font-size:14pt"><b>
			<?php echo $note_header[0];?></b></span></a></br>
                        <textarea style="width:98%" name="spouse_note[0][note]" rows="3" cols="100"><?php echo $xnotes[0]; ?></textarea>
		<input type="hidden" name="spouse_note[0][xeventID]" value="<?php echo $xnote_eventID[0]; ?>" />
		<input type="hidden" name="spouse_note[0][secret]" value="<?php echo $xnote_secret[0]; ?>" />
		<input type="hidden" name="spouse_note[0][ordernum]" value="<?php echo $xnote_ordernum[0]; ?>" />
		</p>
		<p>
			<input type="hidden" name="spouse_note[1][xnote_ID]" value="<?php echo $xnote_ID[1] ?>" />
			<span style="font-size:14pt"><b>
			<?php echo $note_header[1];?></b></span></a></br>
                        <textarea style="width:98%" name="spouse_note[1][note]" rows="3" cols="100"><?php echo $xnotes[1]; ?></textarea>
		<input type="hidden" name="spouse_note[1][xeventID]" value="<?php echo $xnote_eventID[1]; ?>" />
		<input type="hidden" name="spouse_note[1][secret]" value="<?php echo $xnote_secret[1]; ?>" />
		<input type="hidden" name="spouse_note[1][ordernum]" value="<?php echo $xnote_ordernum[1]; ?>" />
		</p>
		<p>
			<input type="hidden" name="spouse_note[2][xnote_ID]" value="<?php echo $xnote_ID[2] ?>" />
			<span style="font-size:14pt"><b>
			<?php echo $note_header[2];?></b></span></a></br>
                        <textarea style="width:98%" name="spouse_note[2][note]" rows="3" cols="100"><?php echo $xnotes[2]; ?></textarea>
		<input type="hidden" name="spouse_note[2][xeventID]" value="<?php echo $xnote_eventID[2]; ?>" />
		<input type="hidden" name="spouse_note[2][secret]" value="<?php echo $xnote_secret[2]; ?>" />
		<input type="hidden" name="spouse_note[2][ordernum]" value="<?php echo $xnote_ordernum[2]; ?>" />
		</p>
		<p>
			<input type="hidden" name="spouse_note[3][xnote_ID]" value="<?php echo $xnote_ID[3] ?>" />
			<span style="font-size:14pt"><b>
			<?php echo $note_header[3];?></b></span></a></br>
                        <textarea style="width:98%" name="spouse_note[3][note]" rows="3" cols="100"><?php echo $xnotes[3]; ?></textarea>
		<input type="hidden" name="spouse_note[3][xeventID]" value="<?php echo $xnote_eventID[3]; ?>" />
		<input type="hidden" name="spouse_note[3][secret]" value="<?php echo $xnote_secret[3]; ?>" />
		<input type="hidden" name="spouse_note[3][ordernum]" value="<?php echo $xnote_ordernum[3]; ?>" />
		</p>
		<p>
			
		<input type="hidden" name="spouse_note[4][xnote_ID]" value="<?php echo $xnote_ID[4] ?>" />
			<span style="font-size:14pt"><b>
			<?php echo $note_header[4];?></b></span></a></br><textarea style="width:98%" name="spouse_note[4][note]" rows="3" cols="100"><?php echo $xnotes[4]; ?>
			</textarea>
		<input type="hidden" name="spouse_note[4][xeventID]" value="<?php echo $xnote_eventID[4]; ?>" />
		<input type="hidden" name="spouse_note[4][secret]" value="<?php echo $xnote_secret[4]; ?>" />
		<input type="hidden" name="spouse_note[4][ordernum]" value="<?php echo $xnote_ordernum[4]; ?>" />
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
		if (inp.type !== 'checkbox') {
			inp.value = null;
		}
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