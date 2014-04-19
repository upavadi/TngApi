<!-- FAMILY ANCESTORS-->
			<?php
				
				$tngcontent = Upavadi_tngcontent::instance()->init();
				
								
				 //get and hold current user
				$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
				$currentperson = $tngcontent->getPerson($currentperson);
				$currentuser = ($currentperson['firstname']. $currentperson['lastname']);
				
					
				//get person details
				$person = $tngcontent->getPerson($personId);
				$birthdate = $person['birthdate'];
				$birthdatetr = ($person['birthdatetr']);
				$birthplace = $person['birthplace'];
				$deathdate = $person['deathdate'];
				$deathdatetr = ($person['deathdatetr']);
				$deathplace = $person['deathplace'];
				$name = $person['firstname']. $person['lastname'];
				
				//get default media
				$defaultmedia = $tngcontent->getdefaultmedia($personId);
				//$mediaID = "../tng/photos/". $defaultmedia['thumbpath'];
				
				if ($defaultmedia['thumbpath'] == null AND $person['sex'] == "M") {
					$mediaID ="../tng/img/male.jpg";
					}
				if ($defaultmedia['thumbpath'] == null AND $person['sex'] == "F") {
					$mediaID ="../tng/img/female.jpg";
					}
				if ($defaultmedia['thumbpath'] !== null) {
				$mediaID = "../tng/photos/". $defaultmedia['thumbpath'];
					}
				
								
				?>
			
			<a href="?personId=<?php echo $currentperson['personID']; ?>">
			<span style="color:#D77600; font-size:14pt">			
				<?php echo "Welcome ". $currentuser; ?>
			</span>
			</a>
					</td>
				</tr>
			</table>	
				
				
				
				
				
				
				<?php
				
		
			
		

		
						
				//get gotra
				$personRow = $tngcontent->getgotra($person['personID']);
				$gotra = $personRow['info'];
												
				
				//get familyuser
				if ($person['sex'] == 'M') {
					$sortBy = 'husborder';
				} else if ($person['sex'] == 'F') {
					$sortBy = 'wifeorder';
				} else {
					$sortBy = null;
				}
				if ($person['living'] == '0' AND $person['deathdatetr'] !== '0000-00-00') 
					{
					$deathdate = " died: " . $person['deathdate'];
					} else {
					$deathdate = " died: date unknown";
					}
					if ($person['living'] == '1') {
					$deathdate = "  (Living)";
				}
				if ($person['living'] == '0' AND $person['deathplace'] == "") 
					{
					$deathplace = "unknown";
					}
				$families = $tngcontent->getfamilyuser($person['personID'], $sortBy);
				
				?>		

<table class="form-table">
	<tbody>
		<tr>
			<td class="tdback"><?php echo "Name"; ?></td>
			<td class="tdfront"><?php echo $name;?></td>
			
			<td class="tdback"><?php echo "Gotra"; ?></td>
			<td class="tdfront"><?php echo $gotra;?></td></tr>
		<tr>	
			<td class="tdback"><?php echo "Born"; ?></td>
			
			<td class="tdfront <?php echo $bornClass; ?>"><?php echo $birthdate;?></td>
			
			<td class="tdback"><?php echo "Place"; ?></td>
			<td class="tdfront"><?php echo $birthplace;?></td>
		</tr>
		<tr>
		<?php If ($currentmonth == $deathmonth) { $bornClass = 'born-highlight';
				} else { $bornClass="";
				}
		?>
				<td class="tdback"><?php echo "Died"; ?></td>
			
			<td class="tdfront <?php echo $bornClass; ?>"><?php echo $deathdate;?></td>
			
			<td class="tdback"><?php echo "Place"; ?></td>
			<td class="tdfront"><?php echo $deathplace;?></td>
		</tr>
	</tbody>
<?php
			$parents = '';
			$parents = $tngcontent->getFamilyById($person['famc']);

			if ($person['famc'] !== '' and $parents['wife'] !== '') {
			$mother = $tngcontent->getPerson($parents['wife']);
			}
			if ($person['famc'] !== ''and $parents['husband'] !== '') {
			$father = $tngcontent->getPerson($parents['husband']);
			}
			
			
			
?>
	<tbody>
		<tr>
		<?php 	if ($father['living'] == '0' AND $father['deathdatetr'] !== '0000-00-00') 
					{
					$fatherdeathdate = " died: " . $father['deathdate'];
					} else {
					$fatherdeathdate = " died: date unknown";
				}
				if ($father['living'] == '1') {
					$fatherdeathdate = "  (Living)";
				}
				
				if ($father['personID'] == '') {
				$fathername = "Unknown";
				} else {
				$fathername = $father['firstname'] . $father['lastname'];
				}
			
				if ($father['birthdatetr'] == "0000-00-00") {
				$fatherbirthmonth = null;
				$fatherbirthdate = "date unknown";
				} else {
				$fatherbirthmonth = substr($father['birthdatetr'], -5, 2);
				$fatherbirthdate = $father['birthdate'];
				}
				
				if ($father['birthplace'] = " ") {
					$fatherbirthplace = "unknown";
				} else {
				$fatherbirthplace = $father['birthplace'];
				}
				
				If ($currentmonth == $fatherbirthmonth) { $bornClass = 'born-highlight';
				} else { $bornClass="";
				}
				
				if ($father['deathdatetr'] == "0000-00-00") {
				$fatherdeathmonth = null;
				} else {
				$fatherdeathmonth = substr($father['deathdatetr'], -5, 2);
				}
			
				if ($father['living'] == '1') {
					$fatherdeathdate = "  (Living)";
					}
				if ($father['living'] == '0' AND $father['deathplace'] == " ") {
					$fatherdeathplace = "unknown";
					}
			?>
			<td class="tdback">Father</td>
			<td class="tdfront" colspan="0">
			
			<?php 
				
				If ($currentmonth == $fatherdeathmonth and $father['personID'] !== null) {
			?>
				<a href="?personId=<?php echo $father['personID']; ?>">
					<?php echo $fathername; ?></a>,<span style="background-color:#E0E0F7"><?php echo $fatherdeathdate; ?>, </span><?php echo $father['deathplace']; ?>
				</li> 
			<?php
				} elseif ($father['personID'] !== null) {
			?>
				<a href="?personId=<?php echo $father['personID']; ?>">
					<?php echo $fathername; ?></a>,<?php echo $fatherdeathdate; ?>, </span><?php echo $fatherdeathplace; 
				} else {
			
				echo $fathername; 
			 }
			
			?>
			</li>
			
			
			</td>
			
			
			<td class="tdback">Born</td>
			<td class="tdfront <?php echo $bornClass; ?>"><?php echo $fatherbirthdate;?></td>
		</tr>
		<tr>
		<?php 
							
				if ($mother['personID'] == '') {
				$mothername = "Unknown";
				} else {
				$mothername = $mother['firstname'] . $mother['lastname'];
				}
			
						
			
			if ($mother['birthdatetr'] == "0000-00-00") {
				$motherbirthmonth = null;
				$motherbirthdate = "date unknown";
				} else {
				$motherbirthmonth = substr($mother['birthdatetr'], -5, 2);
				$motherbirthdate = $mother['birthdate'];
				}
			if ($mother['birthplace'] = " ") {
					$motherbirthplace = "unknown";
				} else {
				$motherbirthplace = $mother['birthplace'];
				}
				
				If ($currentmonth == $motherbirthmonth) { $bornClass = 'born-highlight';
				} else { $bornClass="";
				}
				
			if ($mother['deathdatetr'] == "0000-00-00") {
				$motherdeathmonth = null;
				
				} else {
				$motherdeathmonth = substr($mother['deathdatetr'], -5, 2);
				
				}
			if ($mother['living'] == '0' AND $mother['deathdatetr'] !== '0000-00-00') 
					{
					$motherdeathdate = (" died: ". $mother['deathdate']);
					} else {
					$motherdeathdate = " died: date unknown";
					}
				if ($mother['living'] == '1') {
					$motherdeathdate = "  (Living)";
					}
				if ($mother['living'] == '0' AND $mother['deathplace'] == " ") {
					$motherdeathplace = "unknown";
					}
				?>	
			<td class="tdback">Mother</td>
			<td class="tdfront" colspan="0">
			
			<?php

			If ($currentmonth == $motherdeathmonth and $mother['personID'] !== null) {
			?>
				<a href="?personId=<?php echo $mother['personID']; ?>">
					<?php echo $mothername; ?></a>,<span style="background-color:#E0E0F7"><?php echo $motherdeathdate; ?>, </span><?php echo $father['deathplace']; ?>
				</li> 
			<?php
				} elseif ($mother['personID'] !== null) {
			?>
				<a href="?personId=<?php echo $mother['personID']; ?>">
					<?php echo $mothername; ?></a>,<?php echo $motherdeathdate; ?>, </span><?php echo $motherdeathplace; 
				} else {
			
				echo $mothername; 
			 }
			?>
			</li>
			
			
			</td>
			
			<td class="tdback">Born</td>
			<?php
			if ($mother['motherbirthdatetr'] == "0000-00-00") {
				$motherbirthmonth = null;
				} else {
				$motherbirthmonth = substr($mother['birthdatetr'], -5, 2);
				}
			
			if ($currentmonth == $motherbirthmonth) { $bornClass = 'born-highlight';
				} else { $bornClass="";
				}
				
			?>
			<td class="tdfront <?php echo $bornClass; ?>"><?php echo $motherbirthdate;?></td>
		</tr>
	</tbody>
<?php
			foreach ($families as $family):
				$marrdatetr = $family['marrdatetr'];
				$marrdate = $family['marrdate'];
				$marrplace = $family['marrplace'];
				$order = null;
				if ($sortBy && count($families) > 1) {
					$order = $family[$sortBy];
				}
				
				$spouse['personID'] == '';
				
				if ($person['personID'] == $family['wife'])
				{
				if ($family['husband'] !== '') {
				$spouse = $tngcontent->getPerson($family['husband']);
				}
				} 
				if ($person['personID'] == $family['husband']) 
				{
				if ($family['wife'] !== '') {
				$spouse = $tngcontent->getPerson($family['wife']);
				}
				
				} 
				
				if ($family['marrplace'] == '') {
				$marrplace = "unknown";
				} else {
				$marrplace = $family['marrplace'];
				}
				
				if ($spouse['birthdatetr'] == '0000-00-00') {
				$spousebirthdate = "date unknown";
				} else {
				$spousebirthdate = $spouse['birthdate'];
				}
				
				if ($spouse['living'] == '0' AND $spouse['deathdatetr'] !== '0000-00-00') 
					{
					$deathdate = " died: " . $spouse['deathdate'];
					} else {
					$deathdate = " died: date unknown";
					}
					if ($spouse['living'] == '1') {
					$deathdate = "  (Living)";
				}
				
				if ($spouse['personID'] == '') {
				$spousename = "Unknown";
				} else {
				$spousename = $spouse['firstname'] . $spouse['lastname']. $deathdate;
				}
				
				
				
				
			?>
		<tr>
		<td colspan="0"></td>
		</tr>			
		<tr>
			<td class="tdback"><?php echo "Spouse ",$order; ?></td>
			<td class="tdfront" colspan="0">
				<?php
				if ($spouse['personID'] == '') {
				$spousename = "Unknown";
				?>
				<?php echo $spousename;
				} else {
				$spousename = $spouse['firstname'] . $spouse['lastname']. $deathdate;
				?>
				<a href="?personId=<?php echo $spouse['personID']; ?>">
					<?php echo $spousename; 
				}
				?>
				</a>
				
			</td>
			<td class="tdback">Born</td>
			<?php
			$spousebirthmonth = substr($spouse['birthdatetr'], -5, 2);
				If ($currentmonth == $spousebirthmonth) { $bornClass = 'born-highlight';
				} else { $bornClass="";
				}
				
			?>
			<td class="tdfront <?php echo $bornClass; ?>"><?php echo $spousebirthdate;?></td>

		</tr>
		<tr>
		<td class="tdback"><?php echo "Married" ?></td>
		<?php
			if (marrdatetr == "0000-00-00") {
				$marrmonth = null;
				
				} else {
				$marrmonth = substr($family['marrdatetr'], -5, 2);
				}
			
			If ($currentmonth == $marrmonth) { $bornClass = 'born-highlight';
				} else { $bornClass="";
				}
			if ($family['marrdatetr'] == "0000-00-00") {
				$marrdate = "date unknown";
				} else {
				$marrdate = $family['marrdate'];
				}
				
			?>
			<td class="tdfront <?php echo $bornClass; ?>"><?php echo $marrdate ?>
			</td>
			<td class="tdback"><?php echo "Place"; ?></td>
			<td class="tdfront"><?php echo $marrplace;?></td>
		
		</tr>
		<tr>
			<td class="tdback">Children</td>
			<td class="tdfront" colspan="3">
			<ul>
			<?php
			$children = $tngcontent->getchildren($family['familyID']);
				foreach ($children as $child):
					$classes = array('child');
					$childPerson = $tngcontent->getPerson($child['personID']);
					$childName = $childPerson['firstname'] . $childPerson['lastname'];
					$childdeathdate = $childPerson['deathdate'];
					
					if ($child['haskids']) {
						$classes[] = 'haskids';
					}
					$class = join(' ', $classes);
			?>
			<?php 
					if ($childPerson['living'] == '0' AND $childPerson['deathdatetr'] !== '0000-00-00') 
					{
					$childdeathdate = (" died: ". $childPerson['deathdate']);
					} else {
					$childdeathdate = " died: date unknown";
					}
					if ($childPerson['living'] == '1') {
					$childdeathdate = "  (Living)";
					}
				?>
					
						
								
				<li colspan="0", class="<?php echo $class ?>">
					<a href="?personId=<?php echo $childPerson['personID']; ?>">
				
				<?php 
				if ($childPerson['birthdatetr'] == "0000-00-00") {
				$childbirthmonth = null;
				} else {
				$childbirthmonth = substr($childPerson['birthdatetr'], -5, 2);
				}
				if ($childPerson['deathdatetr'] == "0000-00-00") {
				$childdeathmonth = null;
				} else {
				$childdeathmonth = substr($childPerson['deathdatetr'], -5, 2);
				}
				
				if ($childPerson['birthdatetr'] !== "0000-00-00") {
					$childbirthdate = $childPerson['birthdate'];
					
				}
				if ($childPerson['birthdatetr'] == "0000-00-00") {
					$childbirthdate = ("Date Unknown");
				}
				//var_dump ($childbirthdate);
		
				If ($currentmonth == $childbirthmonth) {
					
				echo $childName; ?></a>,<span style="background-color:#E0E0F7"> born: <?php echo $childbirthdate; ?>, </span><?php echo $childPerson['birthplace']; ?><?php echo $childdeathdate; ?>
					</li> 
				<?php
				} elseif ($currentmonth == $childdeathmonth) {
				echo $childName; ?></a>, born: <?php echo $childbirthdate; ?>,<?php echo $childPerson['birthplace'];?><span style="background-color:#E0E0F7"><?php echo $childdeathdate; ?>
				</span>
				</li> 
				<?php
				} elseif (($currentmonth == $childbirthmonth) AND ($currentmonth == $childdeathmonth)) {
				echo $childName; ?></a>,<span style="background-color:#E0E0F7"> born: <?php echo $childbirthdate; ?>,<?php echo $childPerson['birthplace']; ?><span style="background-color:#E0E0F7"><?php echo $childdeathdate; ?>
				</span>
				</li>
				<?php
				} else {
				echo $childName; ?></a>, born: <?php echo $childbirthdate; ?>, <?php echo $childPerson['birthplace']; ?><?php echo $childdeathdate;?>
				</li>

				<?php
				}
				endforeach;
				?>
			</ul>
			</td>
		</tr>
				<?php
				endforeach;
				?>				
			</ul>
			</td>
		</tr>
		
	</tbody>
</table>				
		
		<?php
		//get All media

		$allpersonmedia = $tngcontent->getallpersonmedia($personId);
		if ($person['famc']) {
			$allpersonmedia = array_merge($allpersonmedia, $tngcontent->getallpersonmedia($person['famc']));
		}
		foreach($families as $family):
			$allpersonmedia = array_merge($allpersonmedia, $tngcontent->getallpersonmedia($family['familyID']));
		endforeach;
		
		$images = array();
		foreach ($allpersonmedia AS $personmedia):
			$images[$personmedia['mediaID']] = $personmedia;
		endforeach;
		
		if (count($images)) {
		?>
				<p><span style="font-size:14pt">
				<?php echo "Photos and Media for ". $name; ?></span></p>
				<?php
		}
		foreach ($images AS $personmedia):
		$mediaID = "../tng/photos/". $personmedia['thumbpath'];
		echo "<a href=\"/genealogy/showmedia.php?mediaID={$personmedia['mediaID']}&medialinkID={$personmedia['medialinkID']}\">";
		echo "<img src=\"/$mediaID\" class='person-images' border='1' height='50' border-color='#000000' alt=>\n";
		echo "</a>";
		endforeach;
				
		?>
		
		<p><span style="font-size:14pt">
				<?php echo "Notes for ". $name; ?></span></a></br>
		You may add or change notes about <?php echo $name; ?> by clicking on <b>Update Person Notes</b> tab above.</br>  
		</p>
		<?php 			
		//get All notes
		$allnotes = $tngcontent->getnotes($personId);
		
		//var_dump ($allnotes);
		
		foreach($allnotes as $PersonNote):
		$individualnote = $PersonNote['note'];
		$individualevent = $PersonNote['eventID'];
		if ($PersonNote['eventID'] == null) {
					$individualevent = "Personal Note";
					}
		if ($PersonNote['eventID'] == "NAME") {
					$individualevent = "About Person's Name";
					}
		if ($PersonNote['eventID'] == "BIRT") {
					$individualevent = "About Person's Birth";
					}
		
		if ($PersonNote['eventID'] == "DEAT") {
					$individualevent = "About Person's Death";
					}
		if ($PersonNote['eventID'] == "BURI") {
					$individualevent = "About Funeral, Cremation / Burial";
					}			
	?>
	
		
		<p>
		<span style="font-size:14pt"><b>
				<?php echo $individualevent;?></b></span></a></br>
		
		<?php echo $individualnote; ?>
		</p>

	
	
	<?php endforeach; ?>
				