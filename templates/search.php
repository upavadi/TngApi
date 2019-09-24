	<?php
	/*
	Search TNG database for names
	*/
		$result = array();
		$firstName = "";
		$lastName = "";
		$tree ="";
		$tngcontent = Upavadi_TngContent::instance()->init();
		$usertree = $user['gedcom'];

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
<div class="container col-md-6 col-sm-4 table-responsive">
<table class="table table-bordered">

	<tr>
			<td class="tdback col-md-4 col-sm-2">Name</th>
			<td class="tdback col-md-1 col-sm-1">Birth Date</th>
			<?php 
			if ($usertree == '') { ?>
			<td class="tdback col-md-1 col-sm-1">Tree</th>
			
			<?php } ?>
			
	</tr>

<tbody>
	<?php
	foreach($results as $result): 
	if (isset($result)){
		$personId = $result['personID'];
		$parentId = $result['famc'];
		$tree = $result['gedcom'];
		$firstname = $result['firstname'];
		$lastname = $result['lastname'];

		$families = $tngcontent->getFamilyUser($personId, $tree, null);
		$parents = $tngcontent->getFamilyById($parentId, $tree = null); 
	
		$personPrivacy = $result['private'];
		$familyPrivacy = $families[0]['private'];
		$parentPrivacy = $parents['private'];
		
		if (($personPrivacy || $familyPrivacy || $parentPrivacy)) {
			$firstname = 'Private:';
			$lastname = ' Details withheld';
			$result['birthdate'] = "?";
		}
	}

	?>
	<tr>
		<td class="col-md-4 col-sm-2">
			<a href="/family/?personId=<?php echo $result['personID']?>&amp;tree=<?php echo $tree; ?> "><?php echo $firstname . ' ' . $lastname; ?></a>
		</td>
		<td  class="col-md-1 col-sm-1">
			<?php echo $result['birthdate']; ?>
		</td>
		
	<?php 
		if ($usertree == '') { ?>
			<td class="col-md-1 col-sm-1"><?php echo $result['gedcom']; ?></td>
        </tr>
    <?php
	}

	endforeach; ?> 
</tbody>
</table>
</div>
<?php endif; 
?>