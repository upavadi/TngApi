<html>
<head>
<!---- Jquery date picker strat -->
<link type="text/css" href="/wordpress/wp-content/plugins/tng-api/css/jquery-datepicker.css" rel="stylesheet" />

<script src="/wordpress/wp-content/plugins/tng-api/js/jquery-1.10.2.js" type="text/javascript"></script>
<script src="/wordpress/wp-content/plugins/tng-api/js/jquery-datepicker.min.js" type="text/javascript"></script>
<script src="/wordpress/wp-content/plugins/tng-api/js/jquery-datepicker.custom.js" type="text/javascript"></script>
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
Clicking on a name takes you to the Individual's FamilyAMILY Page</br> Clicking on VIEW will show your relationship to the individual (Blood relationships only)
<table class="form-table">
	<tbody>
		
			<th class="theader">Name</th>
			<th class="theader">Date</th>
			<th class="theader">Death Place</th>
			<th class="theader">Years</th>
			<th class="theader">Relationship</th>
			
	<?php
			//get and hold current user
			$tngcontent = Upavadi_TngContent::instance()->init();
			$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
	?>		
		
	
<?php foreach ($danniversaries as $danniversary): 
		$danniversarydate = strtotime($danniversary['deathdate']);
		$Years = $year - date('Y', $danniversarydate);
		?>
		<tr>
			<td class="tdfront"><a href="/family/?personId=<?php echo $danniversary['personid'];?>">
			<?php echo $danniversary['firstname']; ?><?php echo $danniversary['lastname']; ?></a></td>
			<td class="tdfront"><?php echo $danniversary['deathdate']; ?></td>
			<td class="tdfront"><?php echo $danniversary['deathplace']; ?></td>
			<td class="tdfront"><?php echo $Years ?></td>
			
			<td class="tdfront"><a href="../genealogy/relationship.php?altprimarypersonID=&savedpersonID=&secondpersonID=<?php echo $danniversary['personid'];?>&maxrels=2&disallowspouses=0&generations=15&tree=upavadi_1&primarypersonID=<?php echo $currentperson; ?>"><?php echo "View"?></td>
			
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
</html>


