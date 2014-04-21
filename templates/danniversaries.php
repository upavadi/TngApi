<p><h2><span style="color:#D77600; font-size:25px">Death Anniversaries for <?php echo $date->format('F Y'); ?></span></h2></p>
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
		
	
<?php foreach ($danniversaries as $danniversary): ?>
		<tr>
			<td class="tdfront"><a href="/family/?personId=<?php echo $danniversary['personid'];?>">
			<?php echo $danniversary['firstname']; ?><?php echo $danniversary['lastname']; ?></a></td>
			<td class="tdfront"><?php echo $danniversary['deathdate']; ?></td>
			<td class="tdfront"><?php echo $danniversary['deathplace']; ?></td>
			<td class="tdfront"><?php echo $danniversary['Years']; ?></td>
			
			<td class="tdfront"><a href="../genealogy/relationship.php?altprimarypersonID=&savedpersonID=&secondpersonID=<?php echo $danniversary['personid'];?>&maxrels=2&disallowspouses=0&generations=15&tree=upavadi_1&primarypersonID=<?php echo $currentperson; ?>"><?php echo "View"?></td>
			
		</tr>
<?php endforeach; ?>
	</tbody>
</table>



