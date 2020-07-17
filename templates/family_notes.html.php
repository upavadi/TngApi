<!-- FAMILY Notes -->
		
			<?php
				
				$tngcontent = Upavadi_tngcontent::instance()->init();
				
								
				 //get and hold current user
				$currentperson = $tngcontent->getCurrentPersonId();
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
				$name = $person['firstname']." ". $person['lastname'];
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
        /* @var $tngcontent Upavadi_TngContent  */
		$allnotes = $tngcontent->getNotes($personId);
		
				$notes = array();
				$note = array();
		foreach($allnotes as $PersonNote):
                    $key = $PersonNote['eventID'];
                    if ($PersonNote['eventID'] == "") {
                        $key = 'GEN';
                    }
                    $notes[$key] = $PersonNote;
                endforeach;
	
		$noteOrder = array(
                    'GEN' => "About Person",
                    'NAME' => "About Person's Name",
                    'BIRT' => "About Person's Birth",
                    'DEAT' => "About Person's Death",
                    'BURI' => "About Funeral, Cremation / Burial"
                );
	
	
	     foreach ($noteOrder as $type => $header):
                    $note = $notes[$type];
                    if (!$note) {
                        $note['xnoteID'] = 'NewNote' . $type;
                        $note['eventID'] = $type;
                        if ($note['eventID'] == 'GEN') {
                            $note['eventID'] = "";
                        }
                    }
                    if ($note['secret']) {
                        $note['note'] = null;
                    }

	?>
            <p>
                <input type="hidden" name="person_note[<?php echo $type; ?>][xnoteID]" value="<?php echo $note['xnoteID'] ?>" />
                <input type="hidden" name="person_note[<?php echo $type; ?>][notelinkID]" value="<?php echo $note['notelinkID'] ?>" />
                <span style="font-size:14pt">
                    <b>
                        <?php echo $header; ?>
                    </b>
                </span><br />
                <textarea style="width:98%" name="person_note[<?php echo $type; ?>][note]" rows="3" cols="100"><?php echo $note['note']; ?></textarea>
                <input type="hidden" name="person_note[<?php echo $type; ?>][xeventID]" value="<?php echo $note['eventID']; ?>" />
                <input type="hidden" name="person_note[<?php echo $type; ?>][secret]" value="<?php echo $note['secret']; ?>" />
                <input type="hidden" name="person_note[<?php echo $type; ?>][ordernum]" value="<?php echo $note['ordernum']; ?>" />
            </p>
	<?php
            endforeach;
        ?>
	<input type="submit" value="Submit Notes" />
	</body>
	</form>