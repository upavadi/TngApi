<!-- FAMILY PAGE -->


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">



    </head>
    <a name="Family"></a>
    <body>

    </body>




    <?php
    $tngcontent = Upavadi_TngContent::instance();


    //get and hold current user
    $currentperson = $tngcontent->getCurrentPersonId($person['personID']);
    $currentperson = $tngcontent->getPerson($currentperson);
    $currentuser = ($currentperson['firstname'] . $currentperson['lastname']);
    // user for upload photo
    $current_user = wp_get_current_user();
    $User = $current_user->user_firstname;
    $UserID = $User->ID;

    //get person details
    $person = $tngcontent->getPerson($personId);
    $birthdate = $person['birthdate'];
    $birthdatetr = ($person['birthdatetr']);
    $birthplace = $person['birthplace'];
    $deathdate = $person['deathdate'];
    $deathdatetr = ($person['deathdatetr']);
    $deathplace = $person['deathplace'];
    $name = $person['firstname'] . $person['lastname'];

    //get default media
    $defaultmedia = $tngcontent->getDefaultMedia($personId);
    //$mediaID = "../tng/photos/". $defaultmedia['thumbpath'];

    if ($defaultmedia['thumbpath'] == null AND $person['sex'] == "M") {
        $mediaID = "../tng/img/male.jpg";
    }
    if ($defaultmedia['thumbpath'] == null AND $person['sex'] == "F") {
        $mediaID = "../tng/img/female.jpg";
    }
    if ($defaultmedia['thumbpath'] !== null) {
        $mediaID = "../tng/photos/" . $defaultmedia['thumbpath'];
    }
    ?>

    <a href="?personId=<?php echo $currentperson['personID']; ?>">
        <span style="color:#D77600; font-size:14pt">			
            <?php echo "Welcome " . $currentuser; ?>
        </span>
    </a>
    <table>
        <tr>
            <td><img src="<?php echo "/$mediaID"; ?>" class='profile-image' /></td>
            <td>
                <table border="0">
                    <tr>
                        <td colspan="2">
                            <span style="font-size:14pt"><a href="?personId=<?php echo $person['personID']; ?>">			
                                <?php echo "Family of " . $name; ?></span></a>
                        </td>
                    </tr>
                    <tr>	
                        <td><input type="button" style="width:150px" value="submit-profile-photo" onclick="location.href = '#submit-profile-photo'"/>
                        </td>
                        <td>
                            <input type="button" style="width:150px" value="Genealogy Page" onclick="window.location.href = '../genealogy/getperson.php?personID=<?php echo $person['personID']; ?>&tree=upavadi_1'" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="button" style="width:150px" value="Ancestors" onclick="window.location.href = '../genealogy/pedigree.php?personID=<?php echo $person['personID']; ?>&tree=upavadi_1'" />
                        </td>
                        <td>
                            <input type="button" style="width:150px" value="Descendants" onclick="window.location.href = '../genealogy/descend.php?personID=<?php echo $person['personID']; ?>&tree=upavadi_1'" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>	

    <?php
//get month of the events
    $currentmonth = date("m");

    if ($birthdatetr == '0000-00-00') {
        $birthmonth = null;
        $birthdate = "Date unknown";
    } else {
        $birthmonth = substr($birthdatetr, -5, 2);
    }
    if ($person['birthplace'] == " ") {
        $birthplace = "unknown";
    } else {
        $birthplace = $person['birthplace'];
    }

    If ($currentmonth == $birthmonth) {
        $bornClass = 'born-highlight';
    } else {
        $bornClass = "";
    }

    if ($deathdatetr == "0000-00-00") {
        $deathmonth = null;
    } else {
        $deathmonth = substr($deathdatetr, -5, 2);
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
    $families = $tngcontent->getFamilyUser($person['personID'], $sortBy);
    ?>		

    <table class="form-table">
        <tbody>
            <tr>
                <td class="tdback"><?php echo "Name"; ?></td>
                <td class="tdfront"><?php echo $name; ?></td>

                <td class="tdback"><?php echo "Gotra"; ?></td>
                <td class="tdfront"><?php echo $gotra; ?></td></tr>
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

                <td class="tdfront <?php echo $bornClass; ?>"><?php echo $deathdate; ?></td>

                <td class="tdback"><?php echo "Place"; ?></td>
                <td class="tdfront"><?php echo $deathplace; ?></td>
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
                <?php
                if ($father['living'] == '0' AND $father['deathdatetr'] !== '0000-00-00') {
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

                If ($currentmonth == $fatherbirthmonth) {
                    $bornClass = 'born-highlight';
                } else {
                    $bornClass = "";
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
                            <?php echo $fathername; ?></a>,<?php echo $fatherdeathdate; ?>, </span><?php
                        echo $fatherdeathplace;
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

                If ($currentmonth == $motherbirthmonth) {
                    $bornClass = 'born-highlight';
                } else {
                    $bornClass = "";
                }

                if ($mother['deathdatetr'] == "0000-00-00") {
                    $motherdeathmonth = null;
                } else {
                    $motherdeathmonth = substr($mother['deathdatetr'], -5, 2);
                }
                if ($mother['living'] == '0' AND $mother['deathdatetr'] !== '0000-00-00') {
                    $motherdeathdate = (" died: " . $mother['deathdate']);
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
                            <?php echo $mothername; ?></a>,<?php echo $motherdeathdate; ?>, </span><?php
                        echo $motherdeathplace;
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

            if ($person['personID'] == $family['wife']) {
                if ($family['husband'] !== '') {
                    $spouse = $tngcontent->getPerson($family['husband']);
                }
            }
            if ($person['personID'] == $family['husband']) {
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

            if ($spouse['living'] == '0' AND $spouse['deathdatetr'] !== '0000-00-00') {
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
                $spousename = $spouse['firstname'] . $spouse['lastname'] . $deathdate;
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
                        $spousename = $spouse['firstname'] . $spouse['lastname'] . $deathdate;
                        ?>
                        <a href="?personId=<?php echo $spouse['personID']; ?>">
                            <?php
                            echo $spousename;
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
                if ($family['marrdatetr'] == "0000-00-00") {
                    $marrdate = "date unknown";
                } else {
                    $marrdate = $family['marrdate'];
                }
                ?>
                <td class="tdfront <?php echo $bornClass; ?>"><?php echo $marrdate ?>
                </td>
                <td class="tdback"><?php echo "Place"; ?></td>
                <td class="tdfront"><?php echo $marrplace; ?></td>

            </tr>
            <tr>
                <td class="tdback">Children</td>
                <td class="tdfront" colspan="3">
                    <ul>
                        <?php
                        $children = $tngcontent->getChildren($family['familyID']);
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
                            if ($childPerson['living'] == '0' AND $childPerson['deathdatetr'] !== '0000-00-00') {
                                $childdeathdate = (" died: " . $childPerson['deathdate']);
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

$allpersonmedia = $tngcontent->getAllPersonMedia($personId);
if ($person['famc']) {
    $allpersonmedia = array_merge($allpersonmedia, $tngcontent->getAllPersonMedia($person['famc']));
}
foreach ($families as $family):
    $allpersonmedia = array_merge($allpersonmedia, $tngcontent->getAllPersonMedia($family['familyID']));
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
    $mediaID = "../tng/photos/" . $personmedia['thumbpath'];
    echo "<a href=\"/genealogy/showmedia.php?mediaID={$personmedia['mediaID']}&medialinkID={$personmedia['medialinkID']}\">";
    echo "<img src=\"/$mediaID\" class='person-images' border='1' height='50' border-color='#000000' alt=>\n";
    echo "</a>";
endforeach;
?>

<p><span style="font-size:14pt">
        <?php echo "Notes for " . $name; ?></span></a></br>
You may add or change notes about <?php echo $name; ?> by clicking on <b>Update Person Notes</b> tab above.</br>  
</p>
<?php
//get All notes
$allnotes = $tngcontent->getNotes($personId);

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
