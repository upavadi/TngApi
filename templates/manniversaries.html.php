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
			<th class="theader">Husband</th>
			<th class="theader">Wife</th>
			<th class="theader">Date</th>
			<th class="theader">Place</th>
			<th class="theader">Years</th>
	
<?php foreach ($manniversaries as $manniversary):
		$manniversarydate = strtotime($manniversary['marrdate']);
		$Years = $year - date('Y', $manniversarydate);
		?>
		<tr>
			<td><a href="/family/?personId=<?php echo $manniversary['personid1'];?>">
			<?php echo $manniversary['firstname1']; ?><?php echo $manniversary['lastname1']; ?></a></td>
			<td><a href="/family/?personId=<?php echo $manniversary['personid2'];?>">
			<?php echo $manniversary['firstname2']; ?><?php echo $manniversary['lastname2']; ?></a></td>
				
			<td><?php echo $manniversary['marrdate']; ?></a></td>
			
			
			
			<td><?php echo $manniversary['marrplace']; ?></td>
			<td><?php echo $Years; ?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
</html>
