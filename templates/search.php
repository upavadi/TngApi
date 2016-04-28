	<?php
	/*
	Search TNG database for names
	*/
		$tngcontent = Upavadi_TngContent::instance()->init();
		$user = $tngcontent->getTngUser();
		$usertree = $user['gedcom'];
		$tree = $result['gedcom'];

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
	<?php foreach($results as $result):
	if ($result['birthdate'] == ''){
		$age = " ";
		} else {
		$age = $result['Age'];
		}
	$tree = $result['gedcom'];
	$firstname = $result['firstname'];
	$lastname = $result['lastname'];
	//if ($result['private'] == '1') {
	//	$firstname = "Private:";
	//	$lastname = " Details withheld";
	//}
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
<?php endif; ?>