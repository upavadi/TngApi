
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
</br> Clicking on VIEW will show your relationship to the individual (Blood relationships only)								
<table class="form-table">
    <tbody>
    <th class="theader">Name</th>
    <th class="theader">Date</th>
    <th class="theader">Birth Place</th>
    <th class="theader">Age</th>
    <th class="theader">Relationship</th>

    <?php foreach ($birthdays as $birthday): ?>
        <tr>
            <td class="tdfront"><a href="/family/?personId=<?php echo $birthday['personid']; ?>">
                    <?php echo $birthday['firstname'] . " "; ?><?php echo $birthday['lastname']; ?></a></td>
            <td class="tdfront"><?php echo $birthday['birthdate']; ?></td>
            <td class="tdfront"><?php echo $birthday['birthplace']; ?></td>
            <td class="tdfront"><?php echo $birthday['age']; ?></td>
            <td class="tdfront"><a href="../genealogy/relationship.php?altprimarypersonID=&savedpersonID=&secondpersonID=<?php echo $birthday['personid']; ?>&maxrels=2&disallowspouses=0&generations=15&tree=upavadi_1&primarypersonID=<?php echo $currentperson; ?>"><?php echo "View" ?></td>

        </tr>
    <?php endforeach; ?>
</tbody>
</table>