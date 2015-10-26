			<?php
				//get and hold current user
				$tngcontent = Upavadi_tngcontent::instance()->init();
				$user = $tngcontent->getTngUser();
				$usertree = $user['gedcom'];
				
				
			?>
<html>
<head>
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
<body>
<form style="display: inline-block;" method="get">
	<label for="search-month">Click to select Month and Year: <input type="text" value="<?php echo $monthyear; ?>" name="monthyear" id="search-monthyear" class="date-picker" /></label> 
<!-- <label for="search-year">Enter Year: <input type="text" value="<?php echo $year; ?>" name="year" id="search-year" size="4"></label>
-->
	<input type="submit" value="Update" style="width:85px;" />
	</form>

<!-- above is to get month selector -->


<p><br/></P>

<h2><span style="color:#D77600; font-size:25px">Death Anniversaries for <?php echo $date->format('F Y'); ?></span></h2>
Clicking on a name takes you to the Individual's FAMILY Page</br>
<table class="form-table">
	<tbody>
		
			<th class="theader" style="text-align: center">Name</th>
			<th class="theader">Date</th>
			<th class="theader">Death Place</th>
			<th class="theader" style="text-align: center">Years</th>
			<!-- Age Calculation: To be reworked for birthdate before 1912
			<th class="theader" style="text-align: center">Age at Death</th>
			-->
			<!-- Removed Relationship
			<th class="theader">Relationship</th>
			-->
	<?php 
	$url = $tngcontent->getTngUrl();
		if ($usertree == '') { ?>
	<th class="theader">Tree</th>
			
	<?php } ?>	
    
			
	
	<?php foreach ($danniversaries as $danniversary): 
		$danniversarydate = strtotime($danniversary['deathdate']);
		$Years = $year - date('Y', $danniversarydate);
		$tree = $danniversary['gedcom'];
		/** Age Calculation: To be reworked for birthdate before 1912
		$birthdate = strtotime($danniversary['birthdate']);
		$ageAtDeath = date('Y', $danniversarydate) - date('Y', $birthdate);
		//var_dump($ageAtDeath);
		//get default media
		**/
		 $photos = $tngcontent->getTngPhotoFolder();
		$personId = $danniversary['personid'];
		$defaultmedia = $tngcontent->getDefaultMedia($personId, $tree);
		 
		$photosPath = $url. $photos;
		$mediaID = $photosPath."/". $defaultmedia['thumbpath'];
		?>
		<tr>
			<td class="tdfront" style="text-align: center">
			<div>
			<?php if ($defaultmedia['thumbpath']) { ?>
			<img src="<?php 
			echo "$mediaID";  ?>" border='1' height='50' border-color='#000000'/> <?php } ?><br /> 
			<a href="/family/?personId=<?php echo $danniversary['personid']; ?>&amp;tree=<?php echo $tree; ?>">
			<?php echo $danniversary['firstname']. " "; echo $danniversary['lastname']; ?></a></div></td>
			<td class="tdfront"><?php echo $danniversary['deathdate']; ?></td>
			<td class="tdfront"><?php echo $danniversary['deathplace']; ?></td>
			<td class="tdfront" style="text-align: center"><?php echo $Years ?></td>
			<!-- Age Calculation: To be reworked for birthdate before 1912
			<td class="tdfront" style="text-align: center"><?php echo $ageAtDeath; ?> </td>
			End of age at death display-->
			
			<!-- Removed Relationship column -------
			<td class="tdfront"><a href="../genealogy/relationship.php?altprimarypersonID=&savedpersonID=&secondpersonID=<?php echo $danniversary['personid'];?>&maxrels=2&disallowspouses=0&generations=15&tree=upavadi_1&primarypersonID=<?php echo $currentperson; ?>"><?php echo "View"?></td>
			-->
			<?php 
		if ($usertree == '') { ?>
			<td class="tdfront"><?php echo $danniversary['gedcom']; ?></td>
        </tr>
		<?php 
			}
	endforeach; 
		?>
	</tbody>
</table>
</html>


