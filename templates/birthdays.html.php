<!-- Birthdays Modified for BootStrap March 2016-->
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Family Birth dates</title>
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
<label style="text-align: right; width: 108px;" for="monthselect">Change Month:</label>
<select name="monthselect" style="width: 100px; margin: 5px;" id="monthSelect" onchange="runBirth()">
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
<label for="yearselect" style="text-align: right; width: 108px;">Change Year:</label>
<select name="yearselect" id="yearSelect" style="width: 100px; margin: 5px;" onchange="runBirth()">
<option value="">--Select Year--</option>
<option value="<?php echo $currentyear-3;?>"><?php echo $currentyear-3;?></option>
<option value="<?php echo $currentyear-2;?>"><?php echo $currentyear-2;?></option>
<option value="<?php echo $currentyear-1;?>"><?php echo $currentyear-1;?></option>
<option value="<?php echo $currentyear;?>" selected="selected"><?php echo $currentyear;?></option>
<option value="<?php echo $currentyear+1;?>"><?php echo $currentyear+1;?></option>
<option value="<?php echo $currentyear+2;?>"><?php echo $currentyear+2;?></option>
<option value="<?php echo $currentyear+3;?>"><?php echo $currentyear+3;?></option>
</select>
<input type="hidden" id="birthMonthYear" name="monthyear">
 <input type="submit" value="Update" style="width:85px; margin: 10px;" />   
</form> 
<script>
function runBirth() {
    document.getElementById("birthMonthYear").value = "01/" + document.getElementById("monthSelect").value + "/" + document.getElementById("yearSelect").value;
}


</script>
<h2><span style="color:#D77600; font-size:25px">Birthdays for <?php echo $date->format('F Y'); ?></span></h2>
Clicking on a name takes you to the Individual's Family Page
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
	<td class="tdback col-md-6" style="text-align: center">Name</td>
    <td class="tdback col-md-2"> Date</td>
    <td class="tdback col-md-2" >Birth Place</td>
    <td class="tdback col-md-1" style="text-align: center">Age</td>
    <?php 
	$url = $tngcontent->getTngUrl();	
	echo $usertree;if ($usertree == '') { ?>
	<td class="tdback col-md-1">Tree</td>
			
	<?php } ?>
	</tr>
    <?php foreach ($birthdays as $birthday):
	$tree = $birthday['gedcom'];
	$firstname = $birthday['firstname'];
	$lastname = $birthday['lastname'];
	//get default media
	$photos = $tngcontent->getTngPhotoFolder();
	$personId = $birthday['personid'];
	//var_dump($personId);
	$defaultmedia = $tngcontent->getDefaultMedia($personId, $tree);
	 
	$photosPath = $url. $photos;
	//$mediaID = "";
	if ($defaultmedia['thumbpath']) {
	$mediaID = $photosPath."/". $defaultmedia['thumbpath'];
	}
	?>
	<tbody>
	   <tr class="row">
            <td class="col-md-6" style="text-align: center">
			<?php if ($defaultmedia['thumbpath']) { ?>
			<img src="<?php 
			echo "$mediaID";  ?>" border='1' height='50' border-color='#000000'/> <?php } ?><br /> 
			<a href="/family/?personId=<?php echo $birthday['personid'];?>&amp;tree=<?php echo $tree; ?>">
                    <?php echo $firstname . " "; ?><?php echo $lastname; ?></a></td>
            <td class="col-md-2"><?php echo $birthday['birthdate']; ?></td>
            <td class="col-md-2"><?php echo $birthday['birthplace']; ?></td>
            <td class="col-md-1" style="text-align: center"><?php echo $birthday['age']; ?></td>
		<!--
	   <td class="tdfront"><a href="../genealogy/relationship.php?altprimarypersonID=&savedpersonID=&secondpersonID=<?php echo $birthday['personid']; ?>&maxrels=2&disallowspouses=0&generations=15&tree=upavadi_1&primarypersonID=<?php echo $currentperson; ?>"><?php echo "View" ?></td>
		-->
			<?php 
			if ($usertree == '') { ?>
				<td class="col-md-1"><?php echo $birthday['gedcom']; ?></td>
		</tr>
    <?php 
	}
	endforeach; ?>
	
	</tbody>
</table>
</div>
</div>
</html>