<!-- FAMILY -->
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
				
				if ($defaultmedia['thumbpath'] == "") {
					if ($person['sex'] = "M") {
					$mediaID ="../tng/img/male.jpg";
					}
					if ($person['sex'] = "F") {
					$mediaID ="../tng/img/female.jpg";
					}
				} else { $mediaID = "../tng/photos/". $defaultmedia['thumbpath'];}
				?>
		
				<a href="?personId=<?php echo $person['personID']; ?>"><span style="color:#D77600; font-size:14pt">			
				<?php echo "Welcome ". $currentuser; ?></span>
				</a>
				<br/><span float="left" style= "font-type:bold; font-size:12pt">			
				<?php echo "Add / Update Notes for ". $name;?></br> and then Click <b>Submit Notes</b> below</span>
				
				
				
 				
	<form id="edit-family-form" action = "<?php echo plugins_url('templates/processfamily-notes.php', dirname(__FILE__)); ?>" method = "POST">
	<input type="hidden" name="User" value="<?php echo $currentuser; ?>" />
	<input type="hidden" name="personId" value="<?php echo $person['personID']; ?>" />
	<input type="hidden" name="person Name" value="<?php echo $name; ?>" />
	<body>
	<?php 			
		//get All notes
		$allnotes = $tngcontent->getnotes($personId);
		
		//var_dump ($allnotes);
		$note_generalID = "About Person";
		$note_nameID = "About Person's Name";
		$note_birthID = "About Person's Birth";
		$note_deathID = "About Person's Death";
		$note_funeralID = "About Funeral, Cremation / Burial";
											
		foreach($allnotes as $PersonNote):
		if ($PersonNote['eventID'] == null) {
					$note_general = $PersonNote['note'];
					}
		if ($PersonNote['eventID'] == "NAME") {
					$note_name = $PersonNote['note'];
					}
		if ($PersonNote['eventID'] == "BIRT") {
					$note_birth = $PersonNote['note'];
					}
		
		if ($PersonNote['eventID'] == "DEAT") {
					$note_death = $PersonNote['note'];
					}
		if ($PersonNote['eventID'] == "BURI") {
					$note_funeral = $PersonNote['note'];
					}			
	?>
	<?php endforeach; ?>
		
		<p>
			<span style="font-size:14pt"><b>
			<?php echo $note_generalID;?></b></span></a></br>
			<textarea name="note_general" rows="3" cols="100">
			<?php echo $note_general; ?>
			</textarea>
		</p>
		<p>
			<span style="font-size:14pt"><b>
			<?php echo $note_nameID;?></b></span></a></br>
			<textarea name="note_name" rows="3" cols="100">
			<?php echo $note_name; ?>
			</textarea>
		</p>
		<p>
			<span style="font-size:14pt"><b>
			<?php echo $note_birthID;?></b></span></a></br>
			<textarea name="note_birth" rows="3" cols="100">
			<?php echo $note_birth; ?>
			</textarea>
		</p>
		<p>
		<?php 
		if ($person['living'] == '0') {
		?>
					
		<span style="font-size:14pt"><b>
			<?php echo $note_deathID;?></b></span></a></br>
			<textarea name="note_death" rows="3" cols="100">
			<?php echo $note_death; ?>
			</textarea>
		</p>
		<p>
			<span style="font-size:14pt"><b>
			<?php echo $note_funeralID;?></b></span></a></br>
			<textarea name="note_funeral" rows="3" cols="100">
			<?php echo $note_funeral; ?>
			</textarea>
		<?php
		}
		?>
		</p>
		
		
	
	<input type="submit" value="Submit Notes">
	</body>
	</form>
				
