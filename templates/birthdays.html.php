<!-- Birthdays Modified for BootStrap March 2016-->
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Upavadi Birth dates</title>
</head>
<!---- Jquery date picker strat -->
<link rel="stylesheet" href="<?php echo plugins_url('css/jquery-ui-1.10.4.custom.css', dirname(__FILE__)); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo plugins_url('js/jquery-1.10.2.js', dirname(__FILE__)); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('js/jquery-datepicker.min.js', dirname(__FILE__)); ?>" type="text/javascript"></script>
<!-- <script src="<?php echo plugins_url('js/jquery-datepicker.custom.js', dirname(__FILE__)); ?>" type="text/javascript"></script>
--> 
<script type="text/javascript">
    $(function() {
        $('.date-picker').datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: '01/mm/yy',
            onClose: function(dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));

            }
        });
    });
</script>
<style>
    .ui-datepicker-calendar { display: none; }
    .ui-datepicker .ui-datepicker-buttonpane  { display: none; }
</style>
</head>
<!---- Jquery date picker end -->
<!-- below is to get month selector -->
<form style="display: inline-block;" method="get">
    <label for="search-month">Click to select Month and Year: <input type="text" value="<?php echo $monthyear; ?>" name="monthyear" id="search-monthyear" class="date-picker" /></label> 
<!-- <label for="search-year">Enter Year: <input type="text" value="<?php echo $year; ?>" name="year" id="search-year" size="4"></label>
    -->
<input type="submit" value="Update" style="width:85px;" />
</form>
<!-- above is to get month selector -->
<p><br/></P>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</html>	
