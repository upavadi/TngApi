<!-- try FAMILY Section  -->
			
<?php
			 
				$now = new \DateTime();
				$tngcontent = Upavadi_TngContent::instance()->init();
				
				//get person details
				$person = $tngcontent->getPerson($personId);
				$birthdate = $person['birthdate'];
				$birthplace = $person['birthplace'];
				$deathdate = $person['deathdate'];
				$deathplace = $person['deathplace'];
				$name = $person['firstname']. $person['lastname'];
				$month2 = $person['birthdatetr'];
				
				$bornOn = new \DateTime($birthdate);
				$bornClass = null;
				
				
				if ($bornOn->format('m') == $now->format('m')) {
					$bornClass = 'born-highlight';
				}
										
				//get gotra
				$personRow = $tngcontent->getGotra($person['personID']);
				$gotra = $personRow['info'];
									
				
				//get familyuser
				if ($person['sex'] == 'M') {
					$sortBy = 'husborder';
				} else if ($person['sex'] == 'F') {
					$sortBy = 'wifeorder';
				} else {
					$sortBy = null;
				}
				if ($person['living'] == '0' AND $person['deathdate'] !== '') 
					{
					$deathdate = " died: " . $person['deathdate'];
					} else {
					$deathdate = " died: date unknown";
					}
					if ($person['living'] == '1') {
					$deathdate = "  (Living)";
				}
				$families = $tngcontent->getFamilyUser($person['personID'], $sortBy);
				
				?>		

<table class="form-table">
	<tbody>
		<tr>
			<td class="tdback"><?php echo "Name"; ?></td>
			<td class="tdfront">(Name - 2nd name or Father's Name - Surname )<input type="text" name="name" value="<?php echo $name;?>" /></td>
			
			<td class="tdback"><?php echo "Gotra"; ?></td>
			<td class="tdfront"><input type="text" gotra="Gotra" value="<?php echo $gotra;?>" /></td></tr>
		<tr>	<td class="tdback"><?php echo "Born"; ?></td>
			<td class="tdfront">(dd mmm yyyy)<input type="date" bday="B.day" value="<?php echo $bornClass; ?><?php echo $birthdate;?>" /></td>
			<td class="tdback"><?php echo "Place"; ?></td>
			<td class="tdfront"><input type="text" bplace="B.Place" value="<?php echo $birthplace;?>" /></td>
		<tr>	
			<td class="tdback"><?php echo "Died"; ?></td>
			<td class="tdfront">(dd mmm yyyy)<input type="date" Dday="D.day" value="<?php echo $deathdate;?>" /></td>
			<td class="tdback"><?php echo "Place"; ?></td>
			<td class="tdfront"><input type="text" dplace="D.Place" value="<?php echo $deathplace;?>" /></td</tr>
			
			
		</tr>
	</tbody>
<?php
			$parents = $tngcontent->getFamilyById($person['famc']);
			$mother = $tngcontent->getPerson($parents['wife']);
			$father = $tngcontent->getPerson($parents['husband']);
			
			
?>
	<tbody>
		<tr>
		<?php 	if ($father['living'] == '0' AND $father['deathdate'] !== '') 
					{
					$deathdate = " died: " . $father['deathdate'];
					} else {
					$deathdate = " died: date unknown";
					}
					if ($father['living'] == '1') {
					$deathdate = "  (Living)";
				}
				?>
			<td class="tdback">Father</td>
			<td class="tdfront" colspan="0">
				<a href="?personId=<?php echo $father['personID']; ?>">
				
					<?php echo $father['firstname'] . $father['lastname']. $deathdate; ?>
				</a>
			</td>
			<td class="tdback">Born</td>
			<td class="tdfront <?php echo $bornClass; ?>"><?php echo $father['birthdate'];?></td>
		</tr>
		<tr>
		<?php 	if ($mother['living'] == '0' AND $mother['deathdate'] !== '') 
					{
					$deathdate = " died: " . $mother['deathdate'];
					} else {
					$deathdate = " died: date unknown";
					}
					if ($mother['living'] == '1') {
					$deathdate = "  (Living)";

				}
				
				
				?>
				
			
			<td class="tdback">Mother</td>
			<td class="tdfront" colspan="0">
				<a href="?personId=<?php echo $mother['personID']; ?>">
					<?php echo $mother['firstname'] . $mother['lastname']. $deathdate; ?>
				</a>
			</td>
			<td class="tdback">Born</td>
			<td class="tdfront <?php echo $bornClass; ?>"><?php echo $mother['birthdate'];?></td>
		</tr>
	</tbody>
<?php
			foreach ($families as $family):
				
				$marrdate = $family['marrdate'];
				$marrplace = $family['marrplace'];
				$order = null;
				if ($sortBy && count($families) > 1) {
					$order = $family[$sortBy];
				}

				if ($person['personID'] == $family['wife']) {
				
				$spouse = $tngcontent->getPerson($family['husband']);
				} else {
					$spouse = $tngcontent->getPerson($family['wife']);
				}
				
				if ($spouse['living'] == '0' AND $spouse['deathdate'] !== '') 
					{
					$deathdate = " died: " . $spouse['deathdate'];
					} else {
					$deathdate = " died: date unknown";
					}
					if ($spouse['living'] == '1') {
					$deathdate = "  (Living)";
				}
			
				
				$spouseName = $spouse['firstname'] . $spouse['lastname'];
								
				$children = $tngcontent->getChildren($family['familyID']);
			?>
		<tr>
		<td colspan="0">&nbsp;</td>
		</tr>			
		<tr>
			<td class="tdback"><?php echo "Family ",$order; ?></td>
			<td class="tdfront" colspan="0"> 
				<a href="?personId=<?php echo $spouse['personID']; ?>">
					<?php echo $spouseName. $deathdate; ?>
				</a>
			</td>
			<td class="tdback">Born</td>
			<td class="tdfront <?php echo $bornClass; ?>"><?php echo $spouse['birthdate'];?></td>

		</tr>
		<tr>
		<td class="tdback"><?php echo "Married" ?></td>
			<td class="tdfront" colspan="0"><?php echo $marrdate ?>
			</td>
			<td class="tdback"><?php echo "Place"; ?></td>
			<td class="tdfront"><?php echo $marrplace;?></td>
		
		</tr>
		<tr>
			<td class="tdback">Children</td>
			<td class="tdfront" colspan="0">
			<ul>
			<?php
				foreach ($children as $child):
					$classes = array('child');
					$childPerson = $tngcontent->getPerson($child['personID']);
					$childName = $childPerson['firstname'] . $childPerson['lastname'];
					$deathdate = $childPerson['deathdate'];
					
					if ($child['haskids']) {
						$classes[] = 'haskids';
					}
					$class = join(' ', $classes);
			?>
			<?php 
					if ($childPerson['living'] == '0' AND $childPerson['deathdate'] !== '') 
					{
					$deathdate = " died: " . $childPerson['deathdate'];
					} else {
					$deathdate = " died: date unknown";
					}
					if ($childPerson['living'] == '1') {
					$deathdate = "  (Living)";
				}?>
					
				<li colspan="0", class="<?php echo $class ?>">
					<a href="?personId=<?php echo $childPerson['personID']; ?>">
					<?php echo $childName; ?></a>, born: <?php echo $childPerson['birthdate']; ?>, <?php echo $childPerson['birthplace']; ?><?php echo $deathdate; ?>
				</li>
<?php
				endforeach;
			?>
			</ul>
			</td>
		</tr>
<?php
		endforeach;
?>				
	</tbody>
</table>				
				 
				