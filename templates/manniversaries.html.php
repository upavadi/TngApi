		<?php
				//get and hold current user
				$tngcontent = Upavadi_tngcontent::instance()->init();
				$user = $tngcontent->getTngUser();
				$usertree = $user['gedcom'];
				
				
		?>
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
<p><h2><span style="color:#D77600; font-size:25px">Marriage Anniversaries for <?php echo $date->format('F Y'); ?></span></h2></p>
Clicking on a name takes you to the Individual's Family Page.</br>
<table class="form-table">
	<tbody>
			<th class="theader" style="text-align: center">Husband</th>
			<th class="theader" style="text-align: center">Wife</th>
			<th class="theader">Date</th>
			<th class="theader">Place</th>
			<th class="theader" style="text-align: center">Years</th>
		<?php 
		$url = $tngcontent->getTngUrl();
		if ($usertree == '') { ?>
		<th class="theader">Tree</th>
			
	<?php } ?>	
    
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
		
		
		<tr>
			<td class="tdfront" style="text-align: center"><div>
			<?php if ($defaultmedia1['thumbpath']) { ?>
			<img src="<?php 
			echo "$mediaID1";  ?>" border='1' height='50' border-color='#000000'/> <?php } ?>
			<br /><a href="/family/?personId=<?php echo $manniversary['personid1'];?>&amp;tree=<?php echo $tree; ?>">
			<?php echo $firstname1. $private1; ?><?php echo " ". $lastname1; ?></a></div></td>
			
			<td class="tdfront" style="text-align: center"><div><?php if ($defaultmedia2['thumbpath']) { ?>
			<img src="<?php 
			echo "$mediaID2";  ?>" border='1' height='50' border-color='#000000'/> <?php } ?>
			<br /><a href="/family/?personId=<?php echo $manniversary['personid2'];?>&amp;tree=<?php echo $tree; ?>">
			<?php echo $firstname2; ?><?php echo $lastname2; ?></a></div></td>
				
			<td class="tdfront"><?php echo $manniversary['marrdate']; ?></td>
			
			
			
			<td class="tdfront"><?php echo $manniversary['marrplace']; ?></td>
			<td class="tdfront" style="text-align: center"><?php echo $Years; ?></td>
		<?php 
		if ($usertree == '') { ?>
			<td class="tdfront"><?php echo $manniversary['gedcom']; ?></td>
        </tr>
    <?php 
			}
	endforeach; ?>
	</tbody>
</table>
</html>
