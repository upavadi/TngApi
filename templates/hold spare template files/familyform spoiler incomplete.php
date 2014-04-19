<!-- I have this css in style.css but not working -->
			<style type="text/css">
/* animated spoiler CSS by Bloggersentral.com */
.spoilerbuttonparent {display:block;margin:5px 0;}
.spoilerbuttonfamily {display:block;margin:5px 0;}
.spoilerbuttonaddfamily {display:block;margin:5px 0;}
.spoilerbuttonaddchild {display:block;margin:5px 0;}
.spoiler {overflow:hidden;background: #f5f5f5;}
.spoiler > div {-webkit-transition: all 0.2s ease;-moz-transition: margin 0.2s ease;-o-transition: all 0.2s ease;transition: margin 0.2s ease;}
.spoilerbuttonparent[value="Show Parents"] + .spoiler > div {margin-top:-100%;}
.spoilerbuttonparent[value="Hide Parents"] + .spoiler {padding:5px;}
.spoiler > div {-webkit-transition: all 0.2s ease;-moz-transition: margin 0.2s ease;-o-transition: all 0.2s ease;transition: margin 0.2s ease;}
.spoilerbuttonfamily[value="Show Family"] + .spoiler > div {margin-top:-100%;}
.spoilerbuttonfamily[value="Hide Family"] + .spoiler {padding:5px;}
.spoilerbuttonaddfamily[value="Add Spouse"] + .spoiler > div {margin-top:-100%;}
.spoilerbuttonaddfamily[value="Hide Add-Spouse"] + .spoiler {padding:5px;} 
.spoilerbuttonaddchild[value="Add child"] + .spoiler > div {margin-top:-100%;}
.spoilerbuttonaddchild[value="Hide Add-child"] + .spoiler {padding:5px;} 
</style> 

<!-- try FAMILY Section -->			
			<?php
				
				$tngcontent = Upavadi_TngContent::instance()->init();
				
				 //get and hold current user
				$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
				$person = $tngcontent->getPerson($currentperson);
				$currentuser = ($person['firstname']. $person['lastname']);
				
				?>
				
				<a href="?personId=<?php echo $person['personID']; ?>"><span style="color:#D77600; font-size:14pt">			
				<?php echo "Welcome ". $currentuser; ?></span>
				</a>
	
				<?php
				//get person details
				$person = $tngcontent->getPerson($personId);
				$birthdate = $person['birthdate'];
				$birthdatetr = ($person['birthdatetr']);
				$birthplace = $person['birthplace'];
				$deathdate = $person['deathdate'];
				$deathdatetr = ($person['deathdatetr']);
				$deathplace = $person['deathplace'];
				$name = $person['firstname'];
				$surname = $person['lastname'];
				?>
				<br/><a href="?personId=<?php echo $person['personID']; ?>"><span float="left" style= "font-type:bold; font-size:12pt">			
				<?php echo "Submit changes to the Family of ". $name. $surname; ?></span>
				</a>
				<?php
				//get month of the events
				$currentmonth = date("m");
								
				if ($birthdatetr == '0000-00-00') {
				$birthmonth = null;
				} else {
				$birthmonth = substr($birthdatetr, -5, 2);
				}
				
	//echo "birth month=". $birthmonth. "birth date=". $birthdatetr;
				If ($currentmonth == $birthmonth) { $bornClass = 'born-highlight'; 
				} else { $bornClass="";
				}
				
				if ($deathdatetr == "0000-00-00") {
				$deathmonth = null;
				} else {
				$deathmonth = substr($deathdatetr, -5, 2);
				}
				
		
				If ($currentmonth == $birthmonth) { $bornClass = 'born-highlight';
				} else { $bornClass="";
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
				if ($person['birthdate'] == '') {
			$birthdate = "Unknown";
			}
			else {
			$birthdate == $person['birthdate'];
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
			if ($person['birthplace'] == '') {
			$birthplace = "Unknown";
			}
			else {
			$birthplace == $person['birthplace'];
			}
			if ($person['deathplace'] == '') {
			$deathplace = "Unknown";
			}
			else {
			$deathplace == $person['deathplace'];
			}	
			
			$families = $tngcontent->getFamilyUser($person['personID'], $sortBy);
				
				?>		

<table class="form-table">
	<tbody>
		<tr>
			<td valign="bottom" class="tdback"><?php echo "Name"; ?></td>
			<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" name="name" value="<?php echo $name;?>" size="30"/></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" gotra="Gotra" value="<?php echo $gotra;?>" /></td></tr>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" surname="surname" value="<?php echo $surname;?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
					
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" bday="B.day" value="<?php echo $bornClass; ?><?php echo $birthdate;?>"</td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<?php 
			
			?>
			<td valign="bottom" class="tdfront"><input type="text" bplace="B.Place" value="<?php echo $birthplace;?>"</td>
		<tr>	
			<td valign="bottom"class="tdback"><?php echo "Died"; ?></td>
			<td  valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" Dday="D.day" value="<?php echo $deathdate;?>"></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			
			<td valign="bottom" class="tdfront"><input type="text" dplace="D.Place" value="<?php echo $deathplace;?>" /></td></tr>
			
			
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
			
			$parentmarrdate = $parents['marrdate'];
			$parentsmarrplace = $parents['marrplace'];
			
?>
	
		<?php 	
				//Father - get Birth date and place
				$fathername = $father['firstname']. $father['lastname'];
				
				if ($fathername !== '')
				{	
										
					if ($father['birthdate'] !== '') 
								{
								$fatherbirthdate = $father['birthdate'];
								} else {
								$fatherbirthdate = "Unknown";
								}
					if ($father['birthplace'] !== '') 
								{
								$fatherbirthplace = $father['birthplace'];
								} else {
								$fatherbirthplace = "Unknown";
								}			
					
				}
				
								
				//Father - get death date and place
				$fatherRow = $tngcontent->getGotra($father['personID']);
				$fathergotra = $fatherRow['info'];
				$fathername = $father['firstname']. $father['lastname'];
				if ($fathername !== '')
				{	
					if ($father['living'] == '0') 
							{
								if ($father['deathdate'] !== '') 
								{
								$fatherdeathdate = " died: ". $father['deathdate'];
								}
								if ($father['deathdate'] == '')  
								{
								$fatherdeathdate = " died: date unknown";
								}
								if ($father['deathplace'] == '')
								{
								$fatherdeathplace = "Unknown";
								} else {
								$fatherdeathplace = $father['deathplace'];
								}
							}
					if ($father['living'] == '1')
							{
							$fatherdeathdate = "  (Living)";
							}
				}
				// this bit does not work
					if ($fathername = "") {
					$father['firstname'] == 'Unknown';
					}
					
						
				//Mother - get Birth date and place
				$mothername = $mother['firstname']. $mother['lastname'];
				
				if ($mothername !== '')
				{	
										
					if ($mother['birthdate'] !== '') 
								{
								$motherbirthdate = $mother['birthdate'];
								} else {
								$motherbirthdate = "Unknown";
								}
					if ($mother['birthplace'] !== '') 
								{
								$motherbirthplace = $mother['birthplace'];
								} else {
								$motherbirthplace = "Unknown";
								}			
					
				}
				
								
				//Mother - get death date and place
				$motherRow = $tngcontent->getGotra($mother['personID']);
				$mothergotra = $motherRow['info'];
				$mothername = $mother['firstname']. $mother['lastname'];
				if ($mothername !== '')
				{	
					if ($mother['living'] == '0') 
							{
								if ($mother['deathdate'] !== '') 
								{
								$motherdeathdate = " died: ". $mother['deathdate'];
								}
								if ($mother['deathdate'] == '')  
								{
								$motherdeathdate = " died: date unknown";
								}
								if ($mother['deathplace'] == '')
								{
								$motherdeathplace = "Unknown";
								} else {
								$motherdeathplace = $mother['deathplace'];
								}
							}
					if ($mother['living'] == '1')
							{
							$motherdeathdate = "  (Living)";
							}
				}
					
			
			
			
			
			
			
			
			if ($mother['birthplace'] == '') {
				$mother['birthplace'] = "Unknown";
			}
			
			if ($mother['deathplace'] == '') {
			$mother['deathplace'] = "Unknown";
			}
		
		?>
				
		</table>
		
		<!-- Parents Spoiler -->
		
		<input class="spoilerbuttonparent" type="button" value="Show Parents" onclick="this.value=this.value=='Show Parents'?'Hide Parents':'Show Parents';">
		<div class="spoiler"><div>
		<span style="color:#D77600; font-size:12pt">
		<?php echo "Parents"; ?></span>
				
		<table class="form-table">
		
	<tbody>
		<tr>
		
			<td valign="bottom" class="tdback">Father</td>
			<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" fathername="fathername" value="<?php echo $father['firstname'];?>"></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" fathergotra="fatherGotra" value="<?php echo $fathergotra;?>" /></td></tr>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" fathersurname="fathersurname" value="<?php echo $father['lastname'];?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" fatherbday="fatherB.day" value="<?php echo $bornClass; ?><?php echo $fatherbirthdate;?>" size="10"/></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			
			<td valign="bottom"class="tdfront"><input type="text" fatherbplace="fatherB.Place" value="<?php echo $fatherbirthplace;?>" /></td>
		<tr>	 
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" fatherDday="fatherD.day" value="<?php echo $fatherdeathdate;?>" /></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" fatherdplace="fatherD.Place" value="<?php echo $fatherdeathplace;?>" /></td</tr>
			
			
		</tr>
		
		<td valign="bottom" class="tdback">Mother</td>
			<td class="tdfront"><span style="color:#777777">(Name - 2nd name or Father's Name)<br/></span><input type="text" mothername="mothername" value="<?php echo $mother['firstname'];?>" size="30"/></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" mothergotra="motherGotra" value="<?php echo $mothergotra;?>" /></td></tr>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" mothersurname="mothersurname" value="<?php echo $mother['lastname'];?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" motherbday="motherB.day" value="<?php echo $bornClass; ?><?php echo $mother['birthdate'];?>" size="10"/></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			
			<td valign="bottom" class="tdfront"><input type="text" motherbplace="motherB.Place" value="<?php echo $mother['birthplace'];?>" /></td>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" motherDday="motherD.day" value="<?php echo $mother['deathdate'];?>" /></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" motherdplace="motherD.Place" value="<?php echo $mother['deathplace'];?>" /></td></tr>
		</tr>
		<tr>
		<td class="tdback"><?php echo "Married" ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" parentmarr.day="parentmarr.day" value="<?php echo $parentmarrdate;?>" /></td>
			
			<td class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" parentmarr.place="parentmarr.Place" value="<?php echo $parentsmarrplace;?>" /></td>
		
		</tr>
		</tbody>
		</table>
		</div></div>
		
		<!-- Family Spoiler -->
		<input class="spoilerbuttonfamily" type="button" value="Show Family" onclick="this.value=this.value=='Show Family'?'Hide Family':'Show Family';">
		<div class="spoiler"><div>
		<span style="color:#D77600; font-size:12pt">
		<?php echo "Family ". $order; ?></span>
		
		<?php
		// Family
		foreach ($families as $family):
				
				$spousemarrdate = $family['marrdate'];
				$spousemarrplace = $family['marrplace'];
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
			
				$spouseRow = $tngcontent->getGotra($spouse['personID']);
				$spousegotra = $spouseRow['info'];
				$spouseName = $spouse['firstname'] . $spouse['lastname'];
								
				$children = $tngcontent->getChildren($family['familyID']);
			?>
		
		
				
	<table class="form-table">
		<tbody>

				
		<tr>
			<td class="tdback"><?php echo "Spouse ". $order; ?></td>
			<td class="tdfront"><span style="color:#777777">(Spouse Name-2nd name or Father's Name)<br/></span><input type="text" spousename="spousename" value="<?php echo $spouse['firstname'];?>"></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" spousegotra="spouseGotra" value="<?php echo $spousegotra;?>" /></td>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" spousesurname="spousesurname" value="<?php echo $spouse['lastname'];?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
		</tr>
		<tr>		
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" spousebday="spouseB.day" value="<?php echo $bornClass; ?><?php echo $spouse['birthdate'];?>"</td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" spousebplace="spouseB.place" value="<?php echo $bornClass; ?><?php echo $spouse['birthplace'];?>" size="10"/></td>
			
		</tr>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" spouseDday="spouseD.day" value="<?php echo $spouse['deathdate'];?>" /></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" spousedplace="spouseD.Place" value="<?php echo $spouse['deathplace'];?>" /></td</tr>
			
			
		<tr>
			
		</tr>
		<tr>
		<td class="tdback"><?php echo "Married" ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" spousemarr.day="spousemarr.day" value="<?php echo $spousemarrdate;?>" /></td>
			
			<td class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" spousemarr.place="spousemarr.place" value="<?php echo $spousemarrplace;?>" /></td>
			
					
		</tr>
		
		
		<tr>
			<td class="tdback">Children</td>
			<td class="tdfront" colspan="3">
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
			<br/<br/><b><?php echo "To modify child data, please click on child name to go to Child page."; ?></b>
			</ul>
			</td>
		</tr>
		</tbody>
		</table>
		</div></div>
		
	
		<?php
		// Add Children to existing family
		?>
		<!-- Add-Child Spoiler -->
		<input class="spoilerbuttonaddchild" type="button" value="Add child" onclick="this.value=this.value=='Add child'?'Hide Add-Child':'Add child';">
		<div class="spoiler"><div>
		<span style="color:#D77600; font-size:12pt">			
		<?php echo "ADD Children for Family ". ($order); ?></span><?php echo " If more than 2 children to enter, please SUBMIT again"?></td>
			
		
	<table class="form-table">	
	<tbody>
		<tr>
		<td </tr>
		</tr><tr>	
			<td class="tdback"><?php echo "Child 1"?></td>
			<td class="tdfront"><span style="color:#777777">(Child Name-2nd name or Father's Name)<br/></span><input type="text" child1name="spousename" value="<?php echo $child1firstname;?>"></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" child1gotra="child1Gotra" value="<?php echo $fathergotra;?>" /></td>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" child1surname="child1surname" value="<?php echo $child1lastname;?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
		</tr>
		<tr>		
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" child1bday="child1B.day" value="<?php echo $bornClass; ?><?php echo $child1birthdate;?>"</td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" child1bplace="child1B.place" value="<?php echo $bornClass; ?><?php echo $child1birthplace;?>" size="10"/></td>
			
		</tr>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" child1Dday="spouseD.day" value="<?php echo $child1deathdate;?>" /></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" child1dplace="spouseD.Place" value="<?php echo $child1deathplace;?>" /></td</tr>
				
		</tr>
			<tr>	
			<td class="tdback"><?php echo "Child 2"?></td>
			<td class="tdfront"><span style="color:#777777">(Child Name-2nd name or Father's Name)<br/></span><input type="text" child1name="spousename" value="<?php echo $child1firstname;?>"></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" child1gotra="child1Gotra" value="<?php echo $fathergotra;?>" /></td>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" child1surname="child1surname" value="<?php echo $child1lastname;?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
		</tr>
		<tr>		
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" child1bday="child1B.day" value="<?php echo $bornClass; ?><?php echo $child1birthdate;?>"</td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" child1bplace="child1B.place" value="<?php echo $bornClass; ?><?php echo $child1birthplace;?>" size="10"/></td>
			
		</tr>
		
		
		
	<?php
	endforeach;
	?>
	</tbody>
	</table>
		</div></div>
<?php
	
		//Add Family
		$spousegotra = "";
?>

		<!-- Add Family Spoiler -->
		<input class="spoilerbuttonaddfamily" type="button" value="Add Spouse" onclick="this.value=this.value=='Add Spouse'?'Hide Add-Spouse':'Add Spouse';">
		<div class="spoiler"><div>
		<span style="color:#D77600; font-size:12pt">
		<?php echo "Add Spouse ". $order; ?></span>	
		
	<table class="form-table">	
	<tbody>	
		<tr>	
			<td class="tdback"><?php echo "Family ". $order; ?></td>
			<td class="tdfront"><span style="color:#777777">(Spouse Name-2nd name or Father's Name)<br/></span><input type="text" spousename="spousename" value="<?php echo $spousefirstname;?>"></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" spousegotra="spouseGotra" value="<?php echo $spousegotra;?>" /></td>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" spousesurname="spousesurname" value="<?php echo $spouselastname;?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
		</tr>
		<tr>		
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" spousebday="spouseB.day" value="<?php echo $bornClass; ?><?php echo $spousebirthdate;?>"</td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" spousebplace="spouseB.place" value="<?php echo $bornClass; ?><?php echo $spousebirthplace;?>" size="10"/></td>
			
		</tr>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" spouseDday="spouseD.day" value="<?php echo $spousedeathdate;?>" /></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" spousedplace="spouseD.Place" value="<?php echo $spousedeathplace;?>" /></td</tr>
		</tbody>
		</table>	
		
		</tbody>
		</table>
		
		<tr>
		<td colspan="4"><span style="color:#D77600; font-size:12pt">			
			<?php echo "ADD Children for Family ". ($order+1); ?></span><?php echo " If more than 2 children to enter, please SUBMIT again"?></td>
		</tr>
		</tr><tr>	
			<td class="tdback"><?php echo "Child 1"?></td>
			<td class="tdfront"><span style="color:#777777">(Child Name-2nd name or Father's Name)<br/></span><input type="text" child1name="spousename" value="<?php echo $child1firstname;?>"></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" child1gotra="child1Gotra" value="<?php echo $fathergotra;?>" /></td>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" child1surname="child1surname" value="<?php echo $child1lastname;?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
		</tr>
		<tr>		
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" child1bday="child1B.day" value="<?php echo $bornClass; ?><?php echo $child1birthdate;?>"</td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" child1bplace="child1B.place" value="<?php echo $bornClass; ?><?php echo $child1birthplace;?>" size="10"/></td>
			
		</tr>
		<tr>	
			<td valign="bottom" class="tdback"><?php echo "Died"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/><s/pan><input type="text" child1Dday="spouseD.day" value="<?php echo $child1deathdate;?>" /></td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" child1dplace="spouseD.Place" value="<?php echo $child1deathplace;?>" /></td</tr>
				
		</tr>
			<tr>	
			<td class="tdback"><?php echo "Child 2"?></td>
			<td class="tdfront"><span style="color:#777777">(Child Name-2nd name or Father's Name)<br/></span><input type="text" child1name="spousename" value="<?php echo $child1firstname;?>"></td>
			<td valign="bottom" class="tdback"><?php echo "Gotra"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" child1gotra="child1Gotra" value="<?php echo $fathergotra;?>" /></td>
		<tr>	
			<td class="tdback"></td>
			<td class="tdfront"><span style="color:#777777">(Surname)<br/></span><input type="text" child1surname="child1surname" value="<?php echo $child1lastname;?>" size="30"/></td>
			<td class="tdback"></td><td class="tdfront"></td>
		</tr>
		<tr>		
			<td valign="bottom" class="tdback"><?php echo "Born"; ?></td>
			<td valign="bottom" class="tdfront"><span style="color:#777777">(dd mmm yyyy)<br/></span><input type="text" child1bday="child1B.day" value="<?php echo $bornClass; ?><?php echo $child1birthdate;?>"</td>
			<td valign="bottom" class="tdback"><?php echo "Place"; ?></td>
			<td valign="bottom" class="tdfront"><input type="text" child1bplace="child1B.place" value="<?php echo $bornClass; ?><?php echo $child1birthplace;?>" size="10"/></td>
			
		</tr>
		</tr>		
	
	</tbody>
</table>
</div></div>

		<br/><span style="color:#D77600; font-size:12pt"><?php echo "Notes: Please use this space for additional details, if any. Thanks  "; ?></span>
			<p><textarea name="notes" cols="1" rows="1" style="width: 100%; height: 149px;"></textarea></p>
				 
				