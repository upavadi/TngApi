<!-- FAMILY Notes -->
		
			<?php
				
				$tngcontent = Upavadi_tngcontent::instance()->init();
				
								
				 //get and hold current user
				$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
				$currentperson = $tngcontent->getPerson($currentperson);
				$currentuser = ($currentperson['firstname']. $currentperson['lastname']);
				$currentuserLogin = wp_get_current_user();
				$UserLogin = $currentuserLogin->user_login;
				
				
				//get person details
				$person = $tngcontent->getPerson($personId);
				$birthdate = $person['birthdate'];
				$birthdatetr = ($person['birthdatetr']);
				$birthplace = $person['birthplace'];
				$deathdate = $person['deathdate'];
				$deathdatetr = ($person['deathdatetr']);
				$deathplace = $person['deathplace'];
				$name = $person['firstname']. $person['lastname'];
				$firstname = $person['firstname'];
				$lastname = $person['lastname'];
				
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
	<input type="hidden" name="User" value="<?php echo $UserLogin; ?>" />
	<input type="hidden" name="personId" value="<?php echo $person['personID']; ?>" />
	<input type="hidden" name="personfirstname" value="<?php echo $firstname; ?>" />
	<input type="hidden" name="personsurname" value="<?php echo $lastname; ?>" />
	<input type="hidden" name="persongedcom" value="<?php echo $person['gedcom']; ?>" />
	
	<body>
	<?php 			
	 
		//get All notes
		$allnotes = $tngcontent->getnotes($personId);
			
		$note_general_secret = 0;
		$note_general_ordernum = 999;
		$note_name_secret = 0;
		$note_name_ordernum = 999;
		$note_birth_secret = 0;
		$note_birth_ordernum = 999;
		$note_death_secret = 0;
		$note_death_ordernum = 999;
		$note_funeral_secret = 0;
		$note_funeral_ordernum = 999;
		
		$note_generalID = "About Person";
		$note_nameID = "About Person's Name";
		$note_birthID = "About Person's Birth";
		$note_deathID = "About Person's Death";
		$note_funeralID = "About Funeral, Cremation / Burial";
		$note_header = array(
		$note_generalID,
		$note_nameID,
		$note_birthID,
		$note_deathID,
		$note_funeralID,);
		
		foreach($allnotes as $PersonNote):
		if ($PersonNote['eventID'] == null) {
					$xnote_generalID = $PersonNote['xnoteID'];
					$note_general = $PersonNote['note'];
					$note_general_secret = $PersonNote['secret'];
					$note_general_ordernum = $PersonNote['ordernum'];
					
					}
		
		if ($PersonNote['eventID'] == "NAME") {
					$xnote_nameID = $PersonNote['xnoteID'];
					$note_name = $PersonNote['note'];
					$note_name_secret = $PersonNote['secret'];
					$note_name_ordernum = $PersonNote['ordernum'];
					
					}
		if ($PersonNote['eventID'] == "BIRT") {
					$xnote_birthID = $PersonNote['xnoteID'];
					$note_birth = $PersonNote['note'];
					$note_birth_secret = $PersonNote['secret'];
					$note_birth_ordernum = $PersonNote['ordernum'];
					}
		
		if ($PersonNote['eventID'] == "DEAT") {
					$xnote_deathID = $PersonNote['xnoteID'];
					$note_death = $PersonNote['note'];
					$note_death_secret = $PersonNote['secret'];
					$note_death_ordernum = $PersonNote['ordernum'];
					}
		if ($PersonNote['eventID'] == "BURI") {
					$xnote_funeralID = $PersonNote['xnoteID'];
					$note_funeral = $PersonNote['note'];
					$note_funeral_secret = $PersonNote['secret'];
					$note_funeral_ordernum = $PersonNote['ordernum'];
		}
	endforeach; 
	
		$xnote_ID = Array(
		$xnote_generalID,
		$xnote_nameID,
		$xnote_birthID,
		$xnote_deathID,
		$xnote_funeralID,);
		
		$xnotes = Array(
		$note_general,
		$note_name,
		$note_birth,
		$note_death,
		$note_funeral,);
		
		$xnote_eventID = Array (
		null,
		"NAME",
		"BIRT",
		"DEAT",
		"BURI",);
		 	
		$xnote_secret = Array(
		$note_general_secret,
		$note_name_secret,
		$note_birth_secret,
		$note_death_secret,
		$note_funeral_secret,);
		
		$xnote_ordernum = Array(
		$note_general_ordernum,
		$note_name_ordernum,
		$note_birth_ordernum,
		$note_death_ordernum,
		$note_funeral_ordernum,);
		
		
	
	
	//var_dump($allnotes);
	?>
            <p>
                <input type="hidden" name="person_note[0][xnote_ID]" value="<?php echo $xnote_ID[0] ?>" />
                <span style="font-size:14pt">
                    <b>
                        <?php echo $note_header[0]; ?>
                    </b>
                </span><br />
                <textarea style="width:98%" name="person_note[0][note]" rows="3" cols="100"><?php echo $xnotes[0]; ?></textarea>
                <input type="hidden" name="person_note[0][xeventID]" value="<?php echo $xnote_eventID[0]; ?>" />
                <input type="hidden" name="person_note[0][secret]" value="<?php echo $xnote_secret[0]; ?>" />
                <input type="hidden" name="person_note[0][ordernum]" value="<?php echo $xnote_ordernum[0]; ?>" />
            </p>
            <p>
                <input type="hidden" name="person_note[1][xnote_ID]" value="<?php echo $xnote_ID[1] ?>" />
                <span style="font-size:14pt">
                    <b>
                        <?php echo $note_header[1]; ?>
                    </b>
                </span><br />
                <textarea style="width:98%" name="person_note[1][note]" rows="3" cols="100"><?php echo $xnotes[1]; ?></textarea>
                <input type="hidden" name="person_note[1][xeventID]" value="<?php echo $xnote_eventID[1]; ?>" />
                <input type="hidden" name="person_note[1][secret]" value="<?php echo $xnote_secret[1]; ?>" />
                <input type="hidden" name="person_note[1][ordernum]" value="<?php echo $xnote_ordernum[1]; ?>" />
            </p>
            <p>
                <input type="hidden" name="person_note[2][xnote_ID]" value="<?php echo $xnote_ID[2] ?>" />
                <span style="font-size:14pt"><b>
                        <?php echo $note_header[2]; ?>
                    </b>
                </span><br />
                <textarea style="width:98%" name="person_note[2][note]" rows="3" cols="100"><?php echo $xnotes[2]; ?></textarea>
                <input type="hidden" name="person_note[2][xeventID]" value="<?php echo $xnote_eventID[2]; ?>" />
                <input type="hidden" name="person_note[2][secret]" value="<?php echo $xnote_secret[2]; ?>" />
                <input type="hidden" name="person_note[2][ordernum]" value="<?php echo $xnote_ordernum[2]; ?>" />
            </p>
            <p>
                <input type="hidden" name="person_note[3][xnote_ID]" value="<?php echo $xnote_ID[3] ?>" />
                <span style="font-size:14pt"><b>
                        <?php echo $note_header[3]; ?>
                    </b>
                </span><br />
                <textarea style="width:98%" name="person_note[3][note]" rows="3" cols="100"><?php echo $xnotes[3]; ?></textarea>
                <input type="hidden" name="person_note[3][xeventID]" value="<?php echo $xnote_eventID[3]; ?>" />
                <input type="hidden" name="person_note[3][secret]" value="<?php echo $xnote_secret[3]; ?>" />
                <input type="hidden" name="person_note[3][ordernum]" value="<?php echo $xnote_ordernum[3]; ?>" />
            </p>
            <p>

                <input type="hidden" name="person_note[4][xnote_ID]" value="<?php echo $xnote_ID[4] ?>" />
                <span style="font-size:14pt">
                    <b>
                        <?php echo $note_header[4]; ?>
                    </b>
                </span><br />
                <textarea style="width:98%" name="person_note[4][note]" rows="3" cols="100"><?php echo $xnotes[4]; ?></textarea>
                <input type="hidden" name="person_note[4][xeventID]" value="<?php echo $xnote_eventID[4]; ?>" />
                <input type="hidden" name="person_note[4][secret]" value="<?php echo $xnote_secret[4]; ?>" />
                <input type="hidden" name="person_note[4][ordernum]" value="<?php echo $xnote_ordernum[4]; ?>" />
            </p>

	
	<input type="submit" value="Submit Notes" />
	</body>
	</form>
				
