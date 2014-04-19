<html>

<p><h2><span style="color:#D77600; font-size:25px">Birthdays for <?php echo $date->format('F Y'); ?></span></h2></p>
Clicking on a name takes you to the Individual's Family Page
</br> Clicking on VIEW will show your relationship to the individual (Blood relationships only)								
<table class="form-table">
	<tbody>
			<th class="theader">Name</th>
			<th class="theader">Date</th>
			<th class="theader">Birth Place</th>
			<th class="theader">Age</th>
			<th class="theader">Relationship</th>
			<th class="theader">Branch</th>
			
			<?php
			//get and hold current user
			$tngcontent = Upavadi_tngcontent::instance()->init();
			$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
			$currentuser = $tngcontent->getCurrentuser($person['username']);
			$username = $person['username'];
			echo $currentuser;
			?>
	<?php foreach ($birthdaysbranch as $birthday): 
	if ($birthday['branch'] = $currentuser)
		{
	
	?>
		<tr>
			<td class="tdfront"><a href="/family/?personId=<?php echo $birthday['personid'];?>">
			<?php echo $birthday['firstname']." "; ?><?php echo $birthday['lastname']; ?></a></td>
			<td class="tdfront"><?php echo $birthday['birthdate']; ?></td>
			<td class="tdfront"><?php echo $birthday['birthplace']; ?></td>
			<td class="tdfront"><?php echo $birthday['Age']; ?></td>
			<td class="tdfront"><a href="../genealogy/relationship.php?altprimarypersonID=&savedpersonID=&secondpersonID=<?php echo $birthday['personid'];?>&maxrels=2&disallowspouses=0&generations=15&tree=upavadi_1&primarypersonID=<?php echo $currentperson; ?>"><?php echo "View"?></td>
			<td class="tdfront"><?php echo $birthday['branch']; ?></td>
			
		</tr>

	<?php 
		}
endforeach; 

?>
	</tbody>
</table>

</html>