
<h2>Birthdays for <?php echo date('F Y', strtotime('first day of +2 months')); ?></h2>
Clicking on a name takes you to the Individual's Family Page
</br> Clicking on VIEW will show your relationship to the individual (Blood relationships only)								
<table cellspacing="1" cellpadding="1" border="1">
	<tbody>
			<th style="background-color: #EDEDED;">Name</th>
			<th style="background-color: #EDEDED;">Date</th>
			<th style="background-color: #EDEDED;">Birth Place</th>
			<th style="background-color: #EDEDED;">Age</th>
			<th style="background-color: #EDEDED;">Relationship</th>
	
			<?php
			//get and hold current user
			$tngcontent = Upavadi_TngContent::instance()->init();
			$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
			?>
	<?php foreach ($birthdaysplustwo as $birthday): ?>
		<tr>
			<td><a href="/family/?personId=<?php echo $birthday['personid'];?>">
			<?php echo $birthday['firstname']." "; ?><?php echo $birthday['lastname']; ?></a></td>
			<td><?php echo $birthday['birthdate']; ?></td>
			<td><?php echo $birthday['birthplace']; ?></td>
			<td><?php echo $birthday['Age']; ?></td>
			<td><a href="../genealogy/relationship.php?altprimarypersonID=&savedpersonID=&secondpersonID=<?php echo $birthday['personid'];?>&maxrels=2&disallowspouses=0&generations=15&tree=upavadi_1&primarypersonID=<?php echo $currentperson; ?>"><?php echo "View"?></td>
			
		</tr>
<?php endforeach; ?>
	</tbody>
</table>

