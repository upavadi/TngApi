<form style="display: inline-block;" method="get">
	<label for="search-lasrtname">Last Name: <input type="text" value="<?php echo $lastName; ?>" name="lastName" id="search-lastname"></label> 
	<label for="search-firstname">First Name: <input type="text" value="<?php echo $firstName; ?>" name="firstName" id="search-firstname"></label>
	<input type="submit" style="margin: -3px 0 0 0px;" value="Search Tree">
</form>
<?php if (!count($results)): ?>
<h2>No results found, please search again</h2>
<?php else: ?>
<table>
<thead>
	<tr>
		<th>
			Name
		</th>
	</tr>
</thead>
<tbody>
	<?php foreach($results as $result): ?>
	<tr>
		<td>
			<?php echo $result['firstname'] . ' ' . $result['lastname']; ?>
		</td>
	</tr>
	<?php endforeach; ?> 
</tbody>
</table>
<?php endif; ?>