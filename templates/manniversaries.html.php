<!-- Marriage Anniversaries Modified for BootStrap March 2016-->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Family Anniversaries</title>
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
<label style="text-align: right; width: 108px;" for="monthselect2">Change Month:</label>
<select name="monthselect2" style="width: 100px; margin: 5px;" id="monthSelect2" onchange="runAnniversary()">
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
<label for="yearselect2" style="text-align: right; width: 108px;">Change Year:</label>
<select name="yearselect2" id="yearSelect2" style="width: 100px; margin: 5px;" onchange="runAnniversary()">
<option value="">--Select Year--</option>
<option value="<?php echo $currentyear-3;?>"><?php echo $currentyear-3;?></option>
<option value="<?php echo $currentyear-2;?>"><?php echo $currentyear-2;?></option>
<option value="<?php echo $currentyear-1;?>"><?php echo $currentyear-1;?></option>
<option value="<?php echo $currentyear;?>" selected="selected"><?php echo $currentyear;?></option>
<option value="<?php echo $curren0tyear+1;?>"><?php echo $currentyear+1;?></option>
<option value="<?php echo $currentyear+2;?>"><?php echo $currentyear+2;?></option>
<option value="<?php echo $currentyear+3;?>"><?php echo $currentyear+3;?></option>
</select>
<input type="hidden" id="anniversaryMonthYear" name="monthyear">
 <input type="submit" value="Update" style="width:85px; margin: 10px;" />   
 <input type="submit"  value="Today" onclick="goToToday()"/>
</form> 
<script>
function runAnniversary() {
    document.getElementById("anniversaryMonthYear").value = "01/" + document.getElementById("monthSelect2").value + "/" + document.getElementById("yearSelect2").value;
}
function goToToday() {
    document.getElementById("birthMonthYear").value = "01/" + document.getElementById("monthSelect").value + "/" + date("Y");
}

</script>
<h2><span style="color:#D77600; font-size:25px">Marriage Anniversaries for <?php echo $date->format('F Y'); ?></span></h2></p>
Clicking on a name takes you to the Individual's FAMILY Page.
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
			<td class="tdback col-md-3" style="text-align: center">Husband</td>
			<td class="tdback col-md-3" style="text-align: center">Wife</td>
			<td class="tdback col-md-2">Date</td>
			<td class="tdback col-md-2">Place</td>
			<td class="tdback col-md-1" style="text-align: center">Years</td>
			<?php 
			$url = $tngcontent->getTngUrl();
			if ($usertree == '') { ?>
			<td class="tdback col-md-1">Tree</td>
			<?php } ?>

	<tbody>	
	</tr>
	<?php foreach ($manniversaries as $manniversary):
		$manniversarydate = strtotime($manniversary['marrdate']);
		$Years = $year - date('Y', $manniversarydate);
		$tree = $manniversary['gedcom'];
		$firstname1 = $manniversary['firstname1'];
		$lastname1 = $manniversary['lastname1'];
		$firstname2 = $manniversary['firstname2'];
		$lastname2 = $manniversary['lastname2'];
		$div_date = $manniversary['divdate'];
	//get default media
		 $photos = $tngcontent->getTngPhotoFolder();
		$personId1 = $manniversary['personid1'];
		$personId2 = $manniversary['personid2'];
		$defaultmedia1 = $tngcontent->getDefaultMedia($personId1, $tree);
		$defaultmedia2 = $tngcontent->getDefaultMedia($personId2, $tree);
		$photosPath = $url. $photos;
		$mediaID1 = $photosPath."/". $defaultmedia1['thumbpath'];
		$mediaID2 = $photosPath."/". $defaultmedia2['thumbpath'];
	
		//Ignore if divorced.
		if ($div_date == '') {
	?>
	
		<tr class="row">
			<td class="col-md-3" style="text-align: center">
			<div>
			<?php if ($defaultmedia1['thumbpath']) { ?>
			<img src="<?php 
			echo "$mediaID1";  ?>" border='1' height='50' border-color='#000000'/> <?php } ?>
			<br /><a href="/family/?personId=<?php echo $manniversary['personid1'];?>&amp;tree=<?php echo $tree; ?>">
			<?php echo $firstname1. $private1; ?><?php echo " ". $lastname1; ?></a></div></td>
			<div>
			<td class="col-md-3" style="text-align: center"><?php if ($defaultmedia2['thumbpath']) { ?>
			<img src="<?php 
			echo "$mediaID2";  ?>" border='1' height='50' border-color='#000000'/> <?php } ?>
			<br /><a href="/family/?personId=<?php echo $manniversary['personid2'];?>&amp;tree=<?php echo $tree; ?>">
			<?php echo $firstname2; ?><?php echo " ". $lastname2; ?></a></div></td>
			<td class="col-md-2"><?php echo $manniversary['marrdate']; ?></td>
			<td class="col-md-2"><?php echo $manniversary['marrplace']; ?></td>
			<td class="col-md-1" style="text-align: center"><?php echo $Years; ?></td>
			<?php 
			if ($usertree == '') { ?>
				<td class="col-md-1"><?php echo $manniversary['gedcom']; ?></td>
        </tr>
	<?php 
		}
		}
	endforeach; ?>
</tbody>
</table>
</div>
</div>
</html>	