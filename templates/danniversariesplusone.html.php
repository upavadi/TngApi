<h2>Death Anniversaries for <?php echo date('F Y', strtotime('+1 month')); ?></h2>
Clicking on a name takes you to the Individual's Family Page</br> Clicking on VIEW will show your relationship to the individual (Blood relationships only)
<table cellspacing="1" cellpadding="1" border="1">
	<tbody>
		
			<th style="background-color: #EDEDED;">Name</th>
			<th style="background-color: #EDEDED;">Date</th>
			<th style="background-color: #EDEDED;">Death Place</th>
			<th style="background-color: #EDEDED;">Years  </th>
			<th style="background-color: #EDEDED;">Relationship</th>
			
	<?php
			//get and hold current user
			$tngcontent = Upavadi_TngContent::instance()->init();
			$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
	?>		
		
	
<?php foreach ($danniversariesplusone as $danniversary): ?>
		<tr>
			<td><a href="/family/?personId=<?php echo $danniversary['personid'];?>">
			<?php echo $danniversary['firstname']; ?><?php echo $danniversary['lastname']; ?></a></td>
			<td><?php echo $danniversary['deathdate']; ?></td>
			<td><?php echo $danniversary['deathplace']; ?></td>
			<td><?php echo $danniversary['Years']; ?></td>
			
			<td><a href="../genealogy/relationship.php?altprimarypersonID=&savedpersonID=&secondpersonID=<?php echo $danniversary['personid'];?>&maxrels=2&disallowspouses=0&generations=15&tree=upavadi_1&primarypersonID=<?php echo $currentperson; ?>"><?php echo "View"?></td>
			
		</tr>
<?php endforeach; ?>
	</tbody>
</table>



