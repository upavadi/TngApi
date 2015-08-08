<!-- FAMILY PAGE -->

    <a name="Family"></a>
    
    <?php
    $tngcontent = Upavadi_TngContent::instance();


	
	$genealogy = $tngcontent->getTngIntegrationPath();
	$url = $tngcontent->getTngUrl();
	$tngDirectory = basename($url );
	$IntegratedPath = dirname($url). "/". $genealogy;
	$displayButtons = $tngcontent->getTngShowButtons();
	$photos = $tngcontent->getTngPhotoFolder();
	$photosPath = $url. $photos;
	echo "tngDirectory=". $tngDirectory;
	echo "<br />Integration name=". $genealogy;
	echo "<br/>IntegratedPath=". $IntegratedPath;
	echo "<br/>TNG URL=". $url;
	echo "<br/>Photos path=". $photosPath. "<br />";
/********************************************/	

	
    //get and hold current user
    $currentperson = $tngcontent->getCurrentPersonId($person['personID']);
    $currentperson = $tngcontent->getPerson($currentperson);
    $currentuser = ($currentperson['firstname'] ." ". $currentperson['lastname']);
    // user for upload photo
    $current_user = wp_get_current_user();
    $User = $current_user->user_firstname;
    $UserID = $User->ID;

    //get person details
    $person = $tngcontent->getPerson($personId, $tree);
    $birthdate = $person['birthdate'];
    $birthdatetr = ($person['birthdatetr']);
    $birthplace = $person['birthplace'];
    $deathdate = $person['deathdate'];
    $deathdatetr = ($person['deathdatetr']);
    $deathplace = $person['deathplace'];
    $name = $person['firstname'] ." ".  $person['lastname'];
    //get default media
    $defaultmedia = $tngcontent->getDefaultMedia($personId, $tree);
    	
    if ($defaultmedia['thumbpath'] == null AND $person['sex'] == "M") {
        $mediaID = "./". $tngDirectory. "/img/male.jpg";
    }
    if ($defaultmedia['thumbpath'] == null AND $person['sex'] == "F") {
        $mediaID = "./". $tngDirectory. "/img/female.jpg"; 
    }
	
    if ($defaultmedia['thumbpath'] !== null) {
        $mediaID = $photosPath. "/" . $defaultmedia['thumbpath'];
	}
	?>
    <a href="?personId=<?php echo $currentperson['personID']; ?>">
        <span style="color:#D77600; font-size:14pt">			
            <?php echo "Welcome " . $currentuser; ?>
        </span>
    </a>
    <table>
        <tr>
            <td><img src="<?php echo "$mediaID"; ?>" class='profile-image' /></td>
            <td>
                <table border="0">
                    <tr>
                        <td colspan="2">
                            <span style="font-size:14pt"><a href="?personId=<?php echo $person['personID']; ?>&amp;tree=<?php echo $person['gedcom']; ?>">			
                                <?php echo "Family of " . $name; ?></span></a>
                        </td>
                    </tr>
                    <tr>	
                        <td><input type="button" style="width:150px" value="submit-profile-photo" onclick="location.href = '#submit-profile-photo'"/>
                        </td>
                        <td>
		<?php
                    $linkPerson = $person['personID'];
                    $tree = $person['gedcom'];
                    if ($displayButtons) {
                        echo "<input type=\"button\" style=\"width:150px\" value=\"Genealogy Page\" onclick=\"window.location.href = '$IntegratedPath/getperson.php?personID=$linkPerson&tree=$tree' \" />";
                        echo '</td>';
                        echo '</tr><tr><td>';
                        echo "<input type=\"button\" style=\"width:150px\" value=\"Ancestors\" onclick=\"window.location.href = '$IntegratedPath/pedigree.php?personID=$linkPerson&tree=$tree'\" />";
                        echo '</td><td>';
                        echo "<input type=\"button\" style=\"width:150px\" value=\"Descendants\" onclick=\"window.location.href = '$IntegratedPath/descend.php?personID=$linkPerson&tree=$tree'\" />";
                    }
                ?>	                      

					   </td>
                    </tr>

					</table>
            </td>
        </tr>
    </table>	

    <?php

//Check person details
    $currentmonth = date("m");
//person Birthdate & Place
		if (($birthdatetr == '0000-00-00') and ($birthdate == ''))  {
			$birthmonth = null;
			$birthdate = "Date unknown";
		} else {
			$birthmonth = substr($birthdatetr, -5, 2);
			$birthdate = $person['birthdate'];
		}
		if ($person['birthplace'] == null) {
			$birthplace = "Unknown";
		} else {
			$birthplace = $person['birthplace'];
		}

		If ($currentmonth == $birthmonth) {
			$bornClass = 'born-highlight';
		} else {
			$bornClass = "";
		}
//get Cause of Death for person
		$personRow = $tngcontent->getCause($person['personID'], $tree);
		if ($personRow['eventtypeID'] == "0") {
			$cause_of_death = ". Cause: ". $personRow['cause'];
		} else {
		$cause_of_death = "";
		}
		
//person Death date & Place
		if (($deathdatetr == "0000-00-00") and ($deathdate == '')) {
			$deathmonth = null;
		} else {
			$deathmonth = substr($deathdatetr, -5, 2);
		}
		if ($person['living'] == '0' AND $person['deathdate'] !== '') {
			$deathdate = " died: " . $person['deathdate'];
		} else {
			$deathdate = " died: Date unknown";
		}
		if ($person['living'] == '1') {
			$deathdate = "  (Living)";
			$deathplace = "not applicable";
		}
		if ($person['living'] == '0' AND $person['deathplace'] == "") {
			$deathplace = "Unknown";
		}

//get Special Event
    $personRow = $tngcontent->getSpEvent($person['personID'], $tree);
	$spevent = $personRow['info'];
//get Description of Event type
	$EventRow = $tngcontent->getEventDisplay($event['display']);	
	$EventDisplay = $EventRow['display'];

//get familyuser
    if ($person['sex'] == 'M') {
        $sortBy = 'husborder';
    } else if ($person['sex'] == 'F') {
        $sortBy = 'wifeorder';
    } else {
        $sortBy = null;
    }
    if ($person['living'] == '0' AND $person['deathdatetr'] !== '0000-00-00') {
        $deathdate = " died: " . $person['deathdate'];
    } else {
        $deathdate = " died: date unknown";
    }
    if ($person['living'] == '1') {
        $deathdate = "  (Living)";
    }
    if ($person['living'] == '0' AND $person['deathplace'] == "") {
        $deathplace = "unknown";
    }
    $families = $tngcontent->getFamilyUser($person['personID'], $tree, $sortBy);
    ?>		

    <table class="form-table">
        <tbody>
            <tr>
                <td class="tdback"><?php echo "Name"; ?></td>
                <td class="tdfront"><?php echo $name; ?></td>

                <td class="tdback"><?php echo $EventDisplay; ?></td>
                <td class="tdfront"><?php echo $spevent; ?></td></tr>
            <tr>	
                <td class="tdback"><?php echo "Born"; ?></td>

                <td class="tdfront <?php echo $bornClass; ?>"><?php echo $birthdate; ?></td>

                <td class="tdback"><?php echo "Place"; ?></td>
                <td class="tdfront"><?php echo $birthplace; ?></td>
            </tr>
            <tr>
                <?php
                If ($currentmonth == $deathmonth) {
                    $bornClass = 'born-highlight';
                } else {
                    $bornClass = "";
                }
                ?>
                <td class="tdback"><?php echo "Died"; ?></td>

                <td class="tdfront <?php echo $bornClass; ?>"><?php echo $deathdate. $cause_of_death; ?></td>

                <td class="tdback"><?php echo "Place"; ?></td>
                <td class="tdfront"><?php echo $deathplace; ?></td>
            </tr>
        </tbody>
        <?php
        $parents = '';
		$parents = $tngcontent->getFamilyById($person['famc'], $tree);

        if ($person['famc'] !== '' and $parents['wife'] !== '') {
            $mother = $tngcontent->getPerson($parents['wife'], $tree);
        }
        if ($person['famc'] !== ''and $parents['husband'] !== '') {
            $father = $tngcontent->getPerson($parents['husband'], $tree);
        }
        ?>
        <tbody>
            <tr>
                <?php
 //set Father details       
              
                if ($father['birthdatetr'] == "0000-00-00" OR $person['famc'] == null) {
                    $fatherbirthmonth = null;
                    $fatherbirthdate = "Date unknown";
                } else {
                    $fatherbirthmonth = substr($father['birthdatetr'], -5, 2);
                    $fatherbirthdate = $father['birthdate'];
                }

                if ($father['birthplace'] == "" OR $person['famc'] == null) {
                    $fatherbirthplace = "Place Unknown";
                } else {
                    $fatherbirthplace = $father['birthplace'];
                }
		
                If ($currentmonth == $fatherbirthmonth) {
                    $bornClass = 'born-highlight';
                } else {
                    $bornClass = "";
                }

				if ($father['living'] == '0' AND $father['deathdate'] !== '') {
                    $fatherdeathdate = " died: " . $father['deathdate'];
                } else { if ($person['famc'] == null) { 
					$fatherdeathdate = "Unknown";
					}
					else {
                    $fatherdeathdate = " died: Date unknown";
                }
				}
                
				if ($father['deathdatetr'] == "0000-00-00") {
                    $fatherdeathmonth = null;
                } else {
                    $fatherdeathmonth = substr($father['deathdatetr'], -5, 2);
                }

                if ($father['living'] == '0' AND $father['deathplace'] == '') {
                    $fatherdeathplace = "Place Unknown";
                } else {
				$fatherdeathplace = $father['deathplace'];
				}
				
				if ($father['living'] == '1') {
                    $fatherdeathdate = "  (Living)";
                }

                if ($father['personID'] !== null) {
					$fathername = $father['firstname']. " ". $father['lastname'];
					} else {
					$fathername = "Unknown";
					}
// Father - Special Event
				if ($father['personID'] !== null)
				{
				$fatherRow = $tngcontent->getSpEvent($father['personID'], $tree);
				$father_spevent = $fatherRow['info'];
				} else {
				$father_spevent = "Unknown";
				}
				if ($father['living'] == '1') {
                    $fatherdeathdate = "  (Living)";
					
                }
//get Cause of Death for Father
				$fatherRow = $tngcontent->getCause($father['personID'], $tree);
				if ($fatherRow['eventtypeID'] == "0") {
					$father_cause_of_death = ". Cause: ". $fatherRow['cause'];
				} else {
				$father_cause_of_death = "";
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
                        <a href="?personId=<?php echo $father['personID']; ?>&amp;tree=<?php echo $father['gedcom']; ?>">
                            <?php echo $fathername; ?></a>,<?php echo $fatherdeathdate; ?>, </span><?php
                        echo $fatherdeathplace. $father_cause_of_death;
                    } else {

                        echo $fathername;
                    }
                    ?>
                    </li>


                </td>


                <td class="tdback">Born</td>
                <td class="tdfront <?php echo $bornClass; ?>"><?php echo $fatherbirthdate; ?></td>
            </tr>
            <tr>
                <?php
//set Mother details
 
                if ($mother['birthdatetr'] == "0000-00-00" OR $person['famc'] == null) {
                    $motherbirthmonth = null;
                    $motherbirthdate = "Date unknown";
                } else {
                    $motherbirthmonth = substr($mother['birthdatetr'], -5, 2);
                    $motherbirthdate = $mother['birthdate'];
                }

                if ($mother['birthplace'] == "" OR $person['famc'] == null) {
                    $motherbirthplace = "Place Unknown";
                } else {
                    $motherbirthplace = $mother['birthplace'];
                }
	
                If ($currentmonth == $motherbirthmonth) {
                    $bornClass = 'born-highlight';
                } else {
                    $bornClass = "";
                }
				
				if ($mother['living'] == '0' AND $mother['deathdate'] !== '') {
                    $motherdeathdate = " died: ". $mother['deathdate'];
                } else { if ($person['famc'] == null) { 
					$motherdeathdate = "Unknown";
					}
					else {
                    $motherdeathdate = " died: Date unknown";
                }
				}
                 if ($mother['deathdatetr'] == "0000-00-00") {
                    $motherdeathmonth = null;
                } else {
                    $motherdeathmonth = substr($mother['deathdatetr'], -5, 2);
                }

                if ($mother['living'] == '0' AND $mother['deathplace'] == '') {
                    $motherdeathplace = "Place Unknown";
                } else {
				$motherdeathplace = $mother['deathplace'];
				}
				
				if ($mother['living'] == '1') {
                    $motherdeathdate = "  (Living)";
					
                }
				

                if ($mother['firstname'] == null) {
                    $motherfirstname = "Unknown";
					} else {
                    $motherfirstname = $mother['firstname'];
				}
				if ($mother['lastname'] == '') {
                    $mothersurname = "Unknown";
					} else {				
					$mothersurname = $mother['lastname'];
					
                }
				if ($mother['personID'] !== null) {
					$mothername = $mother['firstname']. " ". $mother['lastname'];
					} else {
					$mothername = "Unknown";
					}
				
// Mother - Special Event
				if ($mother['personID'] !== null)
				{
				$motherRow = $tngcontent->getSpEvent($mother['personID'], $tree);
				$mother_spevent = $motherRow['info'];
				} else {
				$mother_spevent = "Unknown";
				}
 //get Cause of Death for Mother
				$motherRow = $tngcontent->getCause($mother['personID'], $tree);
				if ($motherRow['eventtypeID'] == "0") {
					$mother_cause_of_death = ". Cause: ". $motherRow['cause'];
				} else {
				$mother_cause_of_death = "";
				}
				
// Parents Marriage Data
		         if ($parents['marrdatetr'] == "0000-00-00" OR $person['famc'] == null) {
                    $parentsmarrmonth = null;
                    $parentsmarrdate = "Marriage Date unknown";
                } else {
                    $parentsmarrmonth = substr($parents['marrdatetr'], -5, 2);
                    $parentsmarrdate = "Married on ". $parents['marrdate'];
                }

                if ($parents['marrplace'] == "" OR $person['famc'] == null) {
                    $parentsmarrplace = "Unknown";
                } else {
                    $parentsmarrplace = $parents['marrplace'];
                }				
                ?>	
                <td class="tdback">Mother</td>
                <td class="tdfront" colspan="0">

                    <?php
                    If ($currentmonth == $motherdeathmonth and $mother['personID'] !== null) {
                        ?>
                        <a href="?personId=<?php echo $mother['personID']; ?>&amp;tree=<?php echo $mother['gedcom']; ?>">
                            <?php echo $mothername; ?></a>,<span style="background-color:#E0E0F7"><?php echo $motherdeathdate; ?>, </span><?php echo $father['deathplace']; ?>
                        </li> 
                        <?php
                    } elseif ($mother['personID'] !== null) {
                        ?>
                        <a href="?personId=<?php echo $mother['personID']; ?>&amp;tree=<?php echo $mother['gedcom']; ?>">
                            <?php echo $mothername; ?></a>,<?php echo $motherdeathdate; ?>, </span><?php
                        echo $motherdeathplace. $mother_cause_of_death;
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

                if ($currentmonth == $motherbirthmonth) {
                    $bornClass = 'born-highlight';
                } else {
                    $bornClass = "";
                }
                ?>
                <td class="tdfront <?php echo $bornClass; ?>"><?php echo $motherbirthdate; ?></td>
            </tr><tr>
			<td class="tdback">Parents</td>
			<?php
                

                if ($currentmonth == $parentsmarrmonth) {
                    $bornClass = 'born-highlight';
                } else {
                    $bornClass = "";
                }
                ?>
               <td class="tdfront <?php echo $bornClass; ?>"><?php echo $parentsmarrdate; ?></td>
			<td class="tdback">Place</td><td class="tdfront"><?php echo $parentsmarrplace;?></td>
			</tr>
        </tbody>
        <?php
//Spouse(s)		
        foreach ($families as $family):
            $marrdatetr = $family['marrdatetr'];
            $marrdate = $family['marrdate'];
            $marrplace = $family['marrplace'];
            $order = null;
            if ($sortBy && count($families) > 1) {
                $order = $family[$sortBy];
            }
            $spouse['personID'] == '';

            if ($person['personID'] == $family['wife']) {
                if ($family['husband'] !== '') {
                    $spouse = $tngcontent->getPerson($family['husband'], $tree);
                }
            }
            if ($person['personID'] == $family['husband']) {
                if ($family['wife'] !== '') {
                    $spouse = $tngcontent->getPerson($family['wife'], $tree);
                }
            }
			 
            if ($family['marrplace'] == '') {
                $marrplace = "unknown";
            } else {
                $marrplace = $family['marrplace'];
            }

            if (($spouse['birthdatetr'] == '0000-00-00') and ($spouse['birthdate'] == ''))  {
                $spousebirthdate = "date unknown";
            } else {
                $spousebirthdate = $spouse['birthdate'];
            }
			if ($spouse['birthplace'] == "") {
                    $spousebirthplace = "Unknown";
                } else {
                    $spousebirthplace = $spouse['birthplace'];
                }

            if ($spouse['living'] == '0' AND $spouse['deathdate'] !== '') {
                $spousedeathdate = " died: " . $spouse['deathdate'];
            } else {
                $spousedeathdate = " died: date unknown";
            }
            if ($spouse['living'] == '0' AND $spouse['deathplace'] == '') {
                    $spousedeathplace = "Unknown";
                } else {
				$spousedeathplace = $spouse['deathplace'];
				}
							
			if ($spouse['living'] == '1') {
                $spousedeathdate = "  (Living)";
				$spousedeathplace = " not applicable";
            }

            if ($spouse['personID'] == '') {
                $spousename = "Unknown";
            } else {
                $spousename = $spouse['firstname']. " ". $spouse['lastname'] . $deathdate;
            }
// Spouse - Special Event
				if ($EventDisplay !== null)
				{
					if ($spouse['personID'] !== null)
					{
					$spouseRow = $tngcontent->getSpEvent($spouse['personID'], $tree);
						if ($spouseRow !== null)  {
						$spouse_spevent = " (". $EventDisplay. ":". $spouseRow['info']. " )";
						} else {
					$spouse_spevent = " (". $EventDisplay. ":Unknown)";
						}
					}	
				}
				
//get Cause of Death for Spouse
				$spouseRow = $tngcontent->getCause($spouse['personID'], $tree);
				if ($spouseRow['eventtypeID'] == "0") {
					$spouse_cause_of_death = ". Cause: ". $spouseRow['cause'];
				} else {
				$spouse_cause_of_death = "";
				}

            ?>
            <tr>
                <td colspan="0"></td>
            </tr>			
            <tr>
                <td class="tdback"><?php echo "Spouse ", $order; ?></td>
                <td class="tdfront" colspan="0">
                    <?php
                    if ($spouse['personID'] == '') {
                        $spousename = "Unknown";
                        ?>
                        <?php
                        echo $spousename;
                    } else {
                        $spousename = $spouse['firstname']. " ". $spouse['lastname'];
                        ?>
                        <a href="?personId=<?php echo $spouse['personID']; ?>&amp;tree=<?php echo $spouse['gedcom']; ?>">
                            <?php
                            echo $spousename. $spouse_spevent;
                        }
                        ?>
                    </a>

                </td>
                <td class="tdback">Born</td>
                <?php
                $spousebirthmonth = substr($spouse['birthdatetr'], -5, 2);
                If ($currentmonth == $spousebirthmonth) {
                    $bornClass = 'born-highlight';
                } else {
                    $bornClass = "";
                }
                ?>
                <td class="tdfront <?php echo $bornClass; ?>"><?php echo $spousebirthdate; ?></td>

            </tr>
            <tr>
                <td class="tdback"><?php echo "Married" ?></td>
                <?php
                if (marrdatetr == "0000-00-00") {
                    $marrmonth = null;
                } else {
                    $marrmonth = substr($family['marrdatetr'], -5, 2);
                }

                If ($currentmonth == $marrmonth) {
                    $bornClass = 'born-highlight';
                } else {
                    $bornClass = "";
                }
                if (($family['marrdatetr'] == "0000-00-00") and ($family['marrdate'] == '')){
                    $marrdate = "date unknown";
                } else {
                    $marrdate = $family['marrdate'];
                }
                ?>
                <td class="tdfront <?php echo $bornClass; ?>"><?php echo $marrdate ?>
                </td>
                <td class="tdback"><?php echo "Place"; ?></td>
                <td class="tdfront"><?php echo $marrplace; ?></td>

            </tr><tr>
                <?php
				$spousedeathmonth = substr($spouse['deathdatetr'], -5, 2);
                If ($currentmonth == $spousedeathmonth) {
                    $bornClass = 'born-highlight';
                } else {
                    $bornClass = "";
                }
                
                ?>
                <td class="tdback"><?php echo "Died"; ?></td>

                <td class="tdfront <?php echo $bornClass; ?>"><?php echo $spousedeathdate. $spouse_cause_of_death; ?></td>

                <td class="tdback"><?php echo "Place"; ?></td>
                <td class="tdfront"><?php echo $spousedeathplace; ?></td>
            </tr>
            <tr>
<!-- Children -->				
                <td class="tdback">Children</td>
                <td class="tdfront" colspan="3">
                    <ul>

				
            <?php
            $children = $tngcontent->getChildren($family['familyID'], $tree);
				foreach ($children as $child):
					$classes = array('child');
					$childPerson = $tngcontent->getPerson($child['personID'], $tree);
					$childName = $childPerson['firstname']. " ". $childPerson['lastname'];
					$childdeathdate = $childPerson['deathdate'];

					if ($child['haskids']) {
						$classes[] = 'haskids';
					}
					$class = join(' ', $classes);
					if ($childPerson['living'] == '0' AND $childPerson['deathdate'] !== '') {
						$childdeathdate = (" died: " . $childPerson['deathdate']);
					} else {
						$childdeathdate = " died: date unknown";
					}
					if ($childPerson['living'] == '1') {
						$childdeathdate = "  (Living)";
					}
					?>



			<li colspan="0", class="<?php echo $class ?>">
				<a href="?personId=<?php echo $childPerson['personID']; ?>&amp;tree=<?php echo $childPerson['gedcom']; ?>">

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

						echo $childName;
						?></a>,<span style="background-color:#E0E0F7"> born: <?php echo $childbirthdate; ?>, </span><?php echo $childPerson['birthplace']; ?><?php echo $childdeathdate; ?>
				</li> 
				<?php
			} elseif ($currentmonth == $childdeathmonth) {
				echo $childName;
				?></a>, born: <?php echo $childbirthdate; ?>,<?php echo $childPerson['birthplace']; ?><span style="background-color:#E0E0F7"><?php echo $childdeathdate; ?>
				</span>
				</li> 
				<?php
			} elseif (($currentmonth == $childbirthmonth) AND ( $currentmonth == $childdeathmonth)) {
				echo $childName;
				?></a>,<span style="background-color:#E0E0F7"> born: <?php echo $childbirthdate; ?>,<?php echo $childPerson['birthplace']; ?><span style="background-color:#E0E0F7"><?php echo $childdeathdate; ?>
					</span>
					</li>
					<?php
				} else {
					echo $childName;
					?></a>, born: <?php echo $childbirthdate; ?>, <?php echo $childPerson['birthplace']; ?><?php echo $childdeathdate; ?>
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

$allpersonmedia = $tngcontent->getAllPersonMedia($personId, $tree);
if ($person['famc']) {
    $allpersonmedia = array_merge($allpersonmedia, $tngcontent->getAllPersonMedia($person['famc'], $tree));
}
foreach ($families as $family):
    $allpersonmedia = array_merge($allpersonmedia, $tngcontent->getAllPersonMedia($family['familyID'], $tree));
endforeach;

$images = array();
foreach ($allpersonmedia AS $personmedia):
    $images[$personmedia['mediaID']] = $personmedia;
endforeach;

if (count($images)) {
    ?>
    <p><span style="font-size:14pt">
            <?php echo "Photos and Media for " . $name; ?></span></p>
    <?php
}
foreach ($images AS $personmedia):
    $mediaID = $photosPath. "/". $personmedia['thumbpath'];
    echo "<a href=\"$IntegratedPath/showmedia.php?mediaID={$personmedia['mediaID']}&medialinkID={$personmedia['medialinkID']}\">";
    echo "<img src=\"$mediaID\" class='person-images' border='1' height='50' border-color='#000000' alt=>\n";
    echo "</a>";
endforeach;
?>

<p><span style="font-size:14pt">
        <?php echo "Notes for " . $name; ?></span></a></br>
You may add or change notes about <?php echo $name; ?> by clicking on <b>Update Person Notes</b> tab above.</br>  
</p>
<?php
//get All notes
$allnotes = $tngcontent->getNotes($personId, $tree);

//var_dump ($allnotes);

foreach ($allnotes as $PersonNote):
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
                <?php echo $individualevent; ?></b></span></a></br>

    <?php echo $individualnote; ?>
    </p>



<?php endforeach; ?>


<div id="submit-profile-photo"></div>
<!-- DO NOT MOVE the 2 files to top!! -->	
<script type="text/javascript" src="<?php echo plugins_url('js/jquery-1.10.2.min.js', dirname(__FILE__)); ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url('js/jquery.form.min.js', dirname(__FILE__)); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    var options = {
        target: '#output', // target element(s) to be updated with server response 
        beforeSubmit: beforeSubmit, // pre-submit callback 
        success: afterSuccess, // post-submit callback 
        resetForm: true        // reset the form after successful submit 
    };

    $('#MyUploadForm').submit(function() {
        $(this).ajaxSubmit(options);
        // always return false to prevent standard browser submit and page navigation 
        return false;
    });
});

function afterSuccess()
{
    $('#submit-btn').show(); //hide submit button
    $('#loading-img').hide(); //hide submit button

}

//function to check file size before uploading.
function beforeSubmit() {
    //check whether browser fully supports all File API
    if (window.File && window.FileReader && window.FileList && window.Blob)
    {

        if (!$('#imageInput').val()) //check empty input filed
        {
            $("#output").html("Please Select an Image to upload");
            return false
        }

        var fsize = $('#imageInput')[0].files[0].size; //get file size
        var ftype = $('#imageInput')[0].files[0].type; // get file type


        //allow only valid image file types 
        switch (ftype)
        {
            case 'image/png':
            case 'image/gif':
            case 'image/jpeg':
            case 'image/pjpeg':
                break;
            default:
                $("#output").html("<b>" + ftype + "</b> Unsupported file type!");
                return false
        }

        //Allowed file size is less than 1 MB (1048576)
        if (fsize > (5 * 1024 * 1024))
        {
            $("#output").html("<b>" + bytesToSize(fsize) + "</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
            return false
        }

        $('#submit-btn').hide(); //hide submit button
        $('#loading-img').show(); //hide submit button
        $("#output").html("");
    }
    else
    {
        //Output error to older unsupported browsers that doesn't support HTML5 File API
        $("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
        return false;
    }
}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0)
        return '0 Bytes';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}


</script>

<?php
$uploadPersonId = $personId;
if (!$uploadPersonId) {
    $uploadPersonId = $tngcontent->getCurrentPersonId();
}
?>
<div id="upload-wrapper" style="width: 60%">
    <div align="center">
        <input type="button" id="return-btn" value="Return" onclick="location.href = '#Family'"/>
        <h3>Submit Profile Image for </br><?php echo $name; ?></h3>

        <b>Profile image submitted by <?php echo $User; ?></b>
        <form class="upload-wrapper upload-wrapper-aligned" action="<?php echo plugins_url('templates/processupload.php', dirname(__FILE__)); ?>" method="post" enctype="multipart/form-data" id="MyUploadForm">
            <input type="hidden" name="title" value="<?php echo $uploadPersonId; ?>" />
            <input type="hidden" name="Desc" value="Submit Profile Image for </br><?php echo $name; ?>" />
            <fieldset>
                <div class="upload-control-group">
                    <label for="Image">Select Image</label>
                    <input name="ImageFile" id="imageInput" type="file" placeholder="no file selected">
                    <br/>Maximum size 5Mb
                </div>

                <p>
                    <input type="submit"  id="submit-btn" value="Upload Photo" />
                    <img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>" id="loading-img" style="display:none;" alt="Please Wait"/>
                </p>
            </fieldset>
        </form>

        <div id="output"></div>
    </div>
</div>
