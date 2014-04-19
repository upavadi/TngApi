<?php
/*
Search TNG database for names
*/




$tngcontent = Upavadi_TngContent::instance()->init();
?>

<form style="display: inline-block;" method="get">
	<label for="search-lastname">Last Name: <input type="text" value="<?php echo $lastName; ?>" name="lastName" id="search-lastname"></label> 
	<label for="search-firstname">First Name: <input type="text" value="<?php echo $firstName; ?>" name="firstName" id="search-firstname"></label>
	<input type="submit" style="margin: -3px 0 0 0px;" value="Search Tree">
</form>
<p><h2><span style="color:#D77600; font-size:14pt">Search Results</span></h2></p>
<?php 

if (!count($results)): ?>
<h2>No results found, please search again</h2>
<?php else: ?>
<table class="form-table" border="1">
<thead>
	<tr>
			<th class="theader">Name</th>
			<th class="theader">Birth Date</th>
			
			
	</tr>
</thead>
<tbody>
	<?php foreach($results as $result):
	if ($result['birthdate'] == ''){
		$age = " ";
		} else {
		$age = $result['Age'];
		}
	?>
	<tr>
		<td class="tdfront">
			<a href="/family/?personId=<?php echo $result['personID']; ?>"><?php echo $result['firstname'] . ' ' . $result['lastname']; ?></a>
		</td>
		<td>
			<?php echo $result['birthdate']; ?>
		</td>
		
	</tr>
	<?php endforeach; ?> 
</tbody>
</table>
<?php endif; ?>