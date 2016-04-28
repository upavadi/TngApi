<!-- Marriage Anniversaries Modified for BootStrap March 2016-->
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Family Anniversaries</title>

</head>
<!---- Jquery date picker strat -->
<link rel="stylesheet" href="<?php echo plugins_url('css/jquery-ui-1.10.4.custom.css', dirname(__FILE__)); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo plugins_url('js/jquery-1.10.2.js', dirname(__FILE__)); ?>" type="text/javascript"></script>
<script src="<?php echo plugins_url('js/jquery-datepicker.min.js', dirname(__FILE__)); ?>" type="text/javascript"></script>
<!-- <script src="<?php echo plugins_url('js/jquery-datepicker.custom.js', dirname(__FILE__)); ?>" type="text/javascript"></script>
-->
<script type="text/javascript">
$(function() {
    $('.date-picker').datepicker( {
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
<p><h2><span style="color:#D77600; font-size:25px">Marriage Anniversaries for <?php echo $date->format('F Y'); ?></span></h2></p>
Clicking on a name takes you to the Individual's FAMILY Page.
	<?php
	//get and hold current user
	$tngcontent = Upavadi_tngcontent::instance()->init();
	$user = $tngcontent->getTngUser();
	$usertree = $user['gedcom'];
	?>
<div class="container col-md-12 table-responsive">
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
		
	</tr>
	<?php foreach ($manniversaries as $manniversary):
		$manniversarydate = strtotime($manniversary['marrdate']);
		$Years = $year - date('Y', $manniversarydate);
		$tree = $manniversary['gedcom'];
		$firstname1 = $manniversary['firstname1'];
		$lastname1 = $manniversary['lastname1'];
		$firstname2 = $manniversary['firstname2'];
		$lastname2 = $manniversary['lastname2'];
	//get default media
		 $photos = $tngcontent->getTngPhotoFolder();
		$personId1 = $manniversary['personid1'];
		$personId2 = $manniversary['personid2'];
		$defaultmedia1 = $tngcontent->getDefaultMedia($personId1, $tree);
		$defaultmedia2 = $tngcontent->getDefaultMedia($personId2, $tree);
		$photosPath = $url. $photos;
		$mediaID1 = $photosPath."/". $defaultmedia1['thumbpath'];
		$mediaID2 = $photosPath."/". $defaultmedia2['thumbpath'];
	?>
	<tbody>
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
	endforeach; ?>
</tbody>
</table>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</html>	
