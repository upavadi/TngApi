<!-- Death Anniversaries Modified for BootStrap March 2016-->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Family Death Anniversaries</title>
</head>
<?php
$monthList = array(
	'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December',
	);
?>
<form action="#" style="display: inline-block;" method="get">
<label style="text-align: right; width: 108px;" for="monthselect3">Change Month:</label>
<select name="monthselect3" style="width: 100px; margin: 5px;" id="monthSelect3" onchange="runDeath()">
<option value="">--Select Month--</option>
<?php
for ($i = 0; $i <= 11; $i++) {
$currentmonth = $i + 1;
if ($currentmonth < 10) {
$currentmonth = "0". $currentmonth;
$currentmonthyear = $currentmonth. $year;
}
if ($currentmonth == $month) {
?>
<option value="<?php echo $currentmonth;?>" selected="selected"><?php echo $monthList[$i] ?></option>
<?php
} else {
?>
<option value="<?php echo $currentmonth;?>"><?php echo $monthList[$i] ?></option>
<?php } }  ?>
</select>
<?php
$currentyear = $year;
?>
<label for="yearselect3" style="text-align: right; width: 108px;">Change Year:</label>
<select name="yearselect3" id="yearSelect3" style="width: 100px; margin: 5px;" onchange="runDeath()">
<option value="">--Select Year--</option>
<option value="<?php echo $currentyear-3;?>"><?php echo $currentyear-3;?></option>
<option value="<?php echo $currentyear-2;?>"><?php echo $currentyear-2;?></option>
<option value="<?php echo $currentyear-1;?>"><?php echo $currentyear-1;?></option>
<option value="<?php echo $currentyear;?>" selected="selected"><?php echo $currentyear;?></option>
<option value="<?php echo $currentyear+1;?>"><?php echo $currentyear+1;?></option>
<option value="<?php echo $currentyear+2;?>"><?php echo $currentyear+2;?></option>
<option value="<?php echo $currentyear+3;?>"><?php echo $currentyear+3;?></option>
</select>
<input type="hidden" id="deathMonthYear" name="monthyear">
 <input type="submit" value="Update" style="width:85px; margin: 10px;" />  
 <input type="submit"  value="Today" onclick="goToToday()"/> 
</form>

<script>
function runDeath() {
    document.getElementById("deathMonthYear").value = "01/" + document.getElementById("monthSelect3").value + "/" + document.getElementById("yearSelect3").value;
}
function goToToday() {
    document.getElementById("birthMonthYear").value = "01/" + document.getElementById("monthSelect").value + "/" + date("Y");
}

</script>
<h2><span style="color:#D77600; font-size:25px">Death Anniversaries for <?php echo $date->format('F Y'); ?></span></h2>
Clicking on a name takes you to the Individual's FAMILY Page.</br>
	<?php
	//get and hold current user
	$tngcontent = Upavadi_tngcontent::instance()->init();
	$user = $tngcontent->getTngUser();
	$usertree = $user['gedcom'];
	?>
<div class="container-fluid table-responsive">
<div class="col-md-12">
<table class="table table-bordered"> 
	<tr class="row">
		<td class="tdback col-md-3" style="text-align: center">Name</td>
		<td class="tdback col-md-2">Date</td>
		<td class="tdback col-md-2">Death Place</td>
		<td class="tdback col-md-2" style="text-align: center">Years</td>
		<td class="tdback col-md-1" style="text-align: center">Age at Death</td>
		<td class="tdback col-md-1" style="text-align: center">Birth Date</td>	
		<?php 
		$url = $tngcontent->getTngUrl();
			if ($usertree == '') { ?>
		<td class="tdback col-md-1">Tree</td>
				
		<?php } ?>
	</tr>
    
			
<tbody>
	<?php 
	foreach ($danniversaries as $danniversary): 
		$danniversarydate = strtotime($danniversary['deathdate']);
		$Years = $year - date('Y', $danniversarydate);
		$tree = $danniversary['gedcom'];
		//get age at death
		if ($danniversary['birthdatetr'] !== "0000-00-00") {
		$d_birtharray = explode("-", ($danniversary['birthdatetr']));
		$d_birthyear = $d_birtharray[0];
		$d_birthmonth = $d_birtharray[1];
		$d_birthday = $d_birtharray[2];
		$deatharray = explode("-", ($danniversary['deathdatetr']));
		$deathyear = $deatharray[0];
		$deathmonth = $deatharray[1];
		$deathday = $deatharray[2];
		$setBirthdate = new DateTime();
		$setBirthdate->setDate($d_birthyear, $d_birthmonth, $d_birthday);
		$setDeathdate = new DateTime();
		$setDeathdate->setDate($deathyear, $deathmonth, $deathday);
		$setBirthdate->format('c') . "<br / >\n";
		$setDeathdate->format('c') . "<br / >\n";
		$i = $setBirthdate->diff($setDeathdate);
		$i->format("%Y");
		$ageAtDeath = $i->format("%Y");
		}	else { 	$ageAtDeath = "";
		}	
		$photos = $tngcontent->getTngPhotoFolder();
		$personId = $danniversary['personid'];
		$defaultmedia = $tngcontent->getDefaultMedia($personId, $tree);
		$photosPath = $url. $photos;
		$mediaID = $photosPath."/". $defaultmedia['thumbpath'];
		$birthdate = $danniversary['birthdate'];
	?>
		<tr class="row">
			<td class="col-md-3" style="text-align: center">
			<div>
			<?php if ($defaultmedia['thumbpath']) { ?>
			<img src="<?php 
			echo "$mediaID";  ?>" border='1' height='50' border-color='#000000'/> <?php } ?>
			<br /><a href="/family/?personId=<?php echo $danniversary['personid']; ?>&amp;tree=<?php echo $tree; ?>">
			<?php echo $danniversary['firstname']. " "; echo $danniversary['lastname']; ?></a></div></td>
			<td class="col-md-2"><?php echo $danniversary['deathdate']; ?></td>
			<td class="col-md-2"><?php echo $danniversary['deathplace']; ?></td>
			<td class="col-md-2" style="text-align: center"><?php echo $Years ?></td>
			<td class="col-md-1" style="text-align: center"><?php echo $ageAtDeath; ?> </td>
			<td class="col-md-1" style="text-align: center"><?php echo $birthdate; ?> </td>
			<?php 
		if ($usertree == '') { ?>
			<td class="col-md-1"><?php echo $danniversary['gedcom']; ?></td>
        </tr>
		<?php 
			}
	endforeach; 
	?>
</tbody>
</table>
</div>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
   
</html>