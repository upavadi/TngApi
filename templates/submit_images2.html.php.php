<!doctype html>
<html lang="en">

<meta charset="utf-8">
		<script type="text/javascript" src="<?php echo plugins_url('js/jquery-1.10.2.min.js', dirname(__FILE__)); ?>"></script>
		<script type="text/javascript" src="<?php echo plugins_url('js/jquery.form.min.js', dirname(__FILE__)); ?>"></script>
		<link rel="stylesheet" href="<?php echo plugins_url('css/upload-image.css', dirname(__FILE__)); ?>" rel="stylesheet" type="text/css">
<?php echo plugins_url('subdirectory/file', dirname(__FILE__)); ?>
<a name="Family"></a>
	
			
			
			<?php
				
				$tngcontent = Upavadi_TngContent::instance()->init();
				
								
				 //get and hold current user
				$currentperson = $tngcontent->getCurrentPersonId($person['personID']);
				$currentperson = $tngcontent->getPerson($currentperson);
				$currentuser = ($currentperson['firstname']. $currentperson['lastname']);
					
			?>

<head>	
		
<script type="text/javascript">
$(document).ready(function() { 
	var options = { 
			target:   '#output',   // target element(s) to be updated with server response 
			beforeSubmit:  beforeSubmit,  // pre-submit callback 
			success:       afterSuccess,  // post-submit callback 
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
function beforeSubmit(){
    //check whether browser fully supports all File API
   if (window.File && window.FileReader && window.FileList && window.Blob)
	{
		
		if( !$('#imageInput').val()) //check empty input filed
		{
			$("#output").html("Are you kidding me?");
			return false
		}
		
		var fsize = $('#imageInput')[0].files[0].size; //get file size
		var ftype = $('#imageInput')[0].files[0].type; // get file type
		

		//allow only valid image file types 
		switch(ftype)
        {
            case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
                break;
            default:
                $("#output").html("<b>"+ftype+"</b> Unsupported file type!");
				return false
        }
		
		//Allowed file size is less than 1 MB (1048576)
		if(fsize>1048576) 
		{
			$("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
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
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}


</script>

</head>
<body>
<div id="upload-wrapper">
<div align="center">

<h3>Submit Profile Image for </br><?php echo $name; ?></h3>

<b>Profile image submitted by <?php echo $currentuser; ?><b>
<form action="../wordpress/wp-content/plugins/tng-api/templates/processupload_profile.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
<input name="ImageFile" id="imageInput" type="file" />

<input type="hidden" name="User" value="<?php echo $currentuser; ?>" />
<input type="hidden" name="personId" value="<?php echo $person['personID']; ?>" />

<input type="submit"  id="submit-btn" value="Upload" />
<img src="../wordpress/wp-content/plugins/tng-api/images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
</form>

Select image to upload by clicking on Browse Button. There is a limit of 5Mb for the picture size. If size is greater, there will be an error message. 
</br>Select
Please enter the Name of the Person</br> 
Image Title: 

Please give me as much details as you can about this picture. If is a group photo, identifying each person would help in tagging the photo.
Description: 

</div>
</body>

				$current_user = wp_get_current_user();
				$currentuser = $current_user->user_firstname;


</head>
<body>
<div id="upload-wrapper">
<div align="center">

<h3>Submit Images</h3>

<b><?php echo $currentuser; ?>, you may upload photos of you and your family. If you wish to upload a profile image, it is easier if you submit from <a href="/family"> Family page.</a>
</b>
<form action="../wordpress/wp-content/plugins/tng-api/templates/processupload_profile.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
<input name="ImageFile" id="imageInput" type="file" />

<input type="hidden" name="User" value="<?php echo $currentuser; ?>" />
<input type="hidden" name="personId" value="<?php echo $person['personID']; ?>" />

<input type="submit"  id="submit-btn" value="Upload" />
<img src="../wordpress/wp-content/plugins/tng-api/images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
</form>

<div id="output"></div>
</div>
</div>
</div>
Select image to upload by clicking on Browse Button. There is a limit of 5Mb for the picture size. If size is greater, there will be an error message. 
</br>Select
Please enter the Name of the Person</br> 
Image Title: 

Please give me as much details as you can about this picture. If is a group photo, identifying each person would help in tagging the photo.
Description: 

Please upload photos of you and your family .
Use the catagory selector to let me know category in which you would like me to place your photos.
Other group photos are also most welcome.
Detailed description will help me to TAG the photos and move them to the correct place in the family tree.
Let me know if you experience any problems.