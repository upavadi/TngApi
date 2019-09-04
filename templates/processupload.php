<?php

require_once '../../../../wp-load.php';
require_once '../autoload.php';
$content = Upavadi_TngContent::instance();
$content->init();
$email = esc_attr(get_option('tng-api-email'));
$collection = esc_attr(get_option('tng-api-tng-photo-upload'));

$tables = $content->getTngTables();
$res = $content->query('select path from ' . $tables['mediatypes_table'] . ' WHERE mediatypeID = "' . $collection . '"');
$row = $res->fetch_assoc();
//$uploadPath = $row['path'];
$photos = esc_attr(get_option('tng-api-tng-photo-folder'));
$uploadPath = $photos;
$tngPath = $content->getTngPath();
if (!preg_match("|" . DIRECTORY_SEPARATOR . "$|", $tngPath)) {
    $tngPath .= DIRECTORY_SEPARATOR;
}
if (!preg_match("|" . DIRECTORY_SEPARATOR . "$|", $uploadPath)) {
    $uploadPath .= DIRECTORY_SEPARATOR;
}
if (isset($_POST)) {
    ############ Edit settings ##############
    $ThumbSquareSize = 100; //Thumbnail will be 200x200
    $BigImageMaxSize = 500; //Image Maximum height or width
    $ThumbPrefix = "thumb_"; //Normal thumb Prefix
    $DestinationDirectory = $tngPath . $uploadPath; //specify upload directory ends with / (slash)

	$Quality = 90; //jpeg quality
    ##########################################
    //check if this is an ajax request
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {

        die();
    }

    // check $_FILES['ImageFile'] not empty
    if (!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name'])) {
        die('Something wrong with uploaded file, something missing!'); // output error when above checks fail.
    }

    // Random number will be added after image name
    $RandomNumber = rand(0, 9999999999);

    $ImageName = str_replace(' ', '-', strtolower($_FILES['ImageFile']['name'])); //get image name
    $ImageSize = $_FILES['ImageFile']['size']; // get original image size
    $TempSrc = $_FILES['ImageFile']['tmp_name']; // Temp name of image file stored in PHP tmp folder
    $ImageType = $_FILES['ImageFile']['type']; //get file type, returns "image/png", image/jpeg, text/plain etc.
    //Let's check allowed $ImageType, we use PHP SWITCH statement here
    switch (strtolower($ImageType)) {
        case 'image/png':
            //Create a new image from file 
            $CreatedImage = imagecreatefrompng($_FILES['ImageFile']['tmp_name']);
            break;
        case 'image/gif':
            $CreatedImage = imagecreatefromgif($_FILES['ImageFile']['tmp_name']);
            break;
        case 'image/jpeg':
        case 'image/pjpeg':
            $CreatedImage = imagecreatefromjpeg($_FILES['ImageFile']['tmp_name']);
            break;
        default:
            die('Unsupported File!'); //output error and exit
    }

    //PHP getimagesize() function returns height/width from image file stored in PHP tmp folder.
    //Get first two values from image, width and height. 
    //list assign svalues to $CurWidth,$CurHeight
    list($CurWidth, $CurHeight) = getimagesize($TempSrc);

    //Get file extension from Image name, this will be added after random name
    $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
    $ImageExt = str_replace('.', '', $ImageExt);

    //remove extension from filename
    $ImageName = preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName);

    //Construct a new name with random number and extension.
    $NewImageName = $ImageName . '-' . $RandomNumber . '.' . $ImageExt;

    //set the Destination Image
    $thumb_DestRandImageName = $DestinationDirectory . $ThumbPrefix . $NewImageName; //Thumbnail name with destination directory
    $DestRandImageName = $DestinationDirectory . $NewImageName; // Image with destination directory
    //Resize image to Specified Size by calling resizeImage function.
    if (resizeImage($CurWidth, $CurHeight, $BigImageMaxSize, $DestRandImageName, $CreatedImage, $Quality, $ImageType)) {
        //Create a square Thumbnail right after, this time we are using cropImage() function
        if (!resizeImage($CurWidth, $CurHeight, $ThumbSquareSize, $thumb_DestRandImageName, $CreatedImage, $Quality, $ImageType)) {
            echo 'Error Creating thumbnail';
        }
        /*
          We have succesfully resized and created thumbnail image
          We can now output image to user's browser or store information in the database
         */
        $domain = preg_replace('|/$|', '', $content->getDomain());
        $thumbPath = '/' . $uploadPath . '/' . basename($thumb_DestRandImageName);
        $thumbPath = str_replace('\\', '/', $thumbPath);
        $thumbPath = $domain . str_replace('//', '/', $thumbPath);
		
        echo '<table width="80%" border="0" cellpadding="4" cellspacing="0">';
        echo '<tr><p> Thanks for submitting. Once the Image is processed, it will be available in the Family Tree. I will let you know by email, once I have done this.</p> </td></tr>';


        echo '<tr><td align="center"><img src="' . $thumbPath . '" alt="Thumbnail"></td>';
        echo '</tr><tr>';


        echo '</tr>';
        echo '</table>';
                
        $basename = basename($DestRandImageName);
        $fileLocation =  $photos. '/' . $basename;
        $thumbLocation = "/". basename($thumb_DestRandImageName);
        $title = $_POST['title'];
        $desc = $_POST['Desc'] . "\n" . $_POST['Notes'];
        $date = date("Y-m-d H:i:s");
        $username = $content->getTngUserName();
        $filetype = strtoupper($ImageExt);
        $sql = <<<SQL
INSERT INTO `{$tables['media_table']}`(`mediaID`, `mediatypeID`, `mediakey`, `gedcom`, `form`, `path`, `description`, `datetaken`, `placetaken`, `notes`, `owner`, `thumbpath`, `alwayson`, `map`, `abspath`, `status`, `showmap`, `cemeteryID`, `plot`, `linktocem`, `longitude`, `latitude`, `zoom`, `width`, `height`, `bodytext`, `usenl`, `newwindow`, `usecollfolder`, `changedate`, `changedby`)
VALUES
(null, ?, ?, '', ?, ?, ?, '', '', ?, '', ?, 0, '', 0, '', 0, 0, '', 0, '', '', 0, 0, 0, '<br>', 0, 0, 1, ?, ?)
SQL;
        $link = $content->getDbLink();
        $stmnt = mysqli_prepare($link, $sql);
        $stmnt->bind_param('sssssssss', $collection, $fileLocation, $filetype, $basename, $title, $desc, $thumbLocation, $date, $username);
        if (!$stmnt->execute()) {
            echo "<p>{$stmnt->error}</p>";
            error_log($stmnt->error);
        }
        $mediaID = $stmnt->insert_id;
        $personId = $content->getCurrentPersonId();
        $person = $content->getPerson($personId);
        $gedcom = $person['gedcom'];


        $sql ="INSERT INTO {$tables['medialinks_table']} (`medialinkID`, `gedcom`, `linktype`, `personID`, `eventID`, `mediaID`, `altdescription`, `altnotes`, `ordernum`, `dontshow`, `defphoto`) VALUES (NULL, '{$gedcom}', 'I', '{$personId}', '', '{$mediaID}', '', '', 0, 0, '')";
        $content->query($sql);
        
        $date = date('c');
$msg = <<<MSG
New Image uploaded on {$date}:

{$username}
{$title}

MSG;
       //echo "<pre>{$msg}</pre>";
        mail($email, 'New photo', $msg);
    } else {
        die('Resize Error'); //output error
    }
}

// This function will proportionally resize image 
function resizeImage($CurWidth, $CurHeight, $MaxSize, $DestFolder, $SrcImage, $Quality, $ImageType)
{
    //Check Image size is not 0
    if ($CurWidth <= 0 || $CurHeight <= 0) {
        return false;
    }

    //Construct a proportional size of new image
    $ImageScale = min($MaxSize / $CurWidth, $MaxSize / $CurHeight);
    if ($ImageScale > 1) {
        $ImageScale = 1;
    }
    $NewWidth = ceil($ImageScale * $CurWidth);
    $NewHeight = ceil($ImageScale * $CurHeight);
    $NewCanves = imagecreatetruecolor($NewWidth, $NewHeight);

    // Resize Image
    if (imagecopyresampled($NewCanves, $SrcImage, 0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight)) {
        switch (strtolower($ImageType)) {
            case 'image/png':
                imagepng($NewCanves, $DestFolder);
                break;
            case 'image/gif':
                imagegif($NewCanves, $DestFolder);
                break;
            case 'image/jpeg':
            case 'image/pjpeg':
                imagejpeg($NewCanves, $DestFolder, $Quality);

                break;
            default:
                return false;
        }
        //Destroy image, frees memory	
        if (is_resource($NewCanves)) {
            imagedestroy($NewCanves);
        }
        return true;
    }
}

//This function corps image to create exact square images, no matter what its original size!
function cropImage($CurWidth, $CurHeight, $iSize, $DestFolder, $SrcImage, $Quality, $ImageType)
{
    //Check Image size is not 0
    if ($CurWidth <= 0 || $CurHeight <= 0) {
        return false;
    }

    //abeautifulsite.net has excellent article about "Cropping an Image to Make Square bit.ly/1gTwXW9
    if ($CurWidth > $CurHeight) {
        $y_offset = 0;
        $x_offset = ($CurWidth - $CurHeight) / 2;
        $square_size = $CurWidth - ($x_offset * 2);
    } else {
        $x_offset = 0;
        $y_offset = ($CurHeight - $CurWidth) / 2;
        $square_size = $CurHeight - ($y_offset * 2);
    }

    $NewCanves = imagecreatetruecolor($iSize, $iSize);
    if (imagecopyresampled($NewCanves, $SrcImage, 0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size)) {
        switch (strtolower($ImageType)) {
            case 'image/png':
                imagepng($NewCanves, $DestFolder);
                break;
            case 'image/gif':
                imagegif($NewCanves, $DestFolder);
                break;
            case 'image/jpeg':
            case 'image/pjpeg':
                imagejpeg($NewCanves, $DestFolder, $Quality);
                echo "  line 181 image jpeg" . $NewCanves . $DestFolder . " Quality" . $Quality;
                break;
            default:
                return false;
        }
        //Destroy image, frees memory	
        if (is_resource($NewCanves)) {
            imagedestroy($NewCanves);
        }
        return true;
    }
}