<!-- FAMILY PAGE -->
			
	
<!doctype html>
<html lang="en">

<meta charset="utf-8">

		<script type="text/javascript" src="<?php echo plugins_url('js/jquery-1.10.2.min.js', dirname(__FILE__)); ?>"></script>
		<script type="text/javascript" src="<?php echo plugins_url('js/jquery.form.min.js', dirname(__FILE__)); ?>"></script>

	<?php 	$current_user = wp_get_current_user();
				$User = $current_user->user_firstname;
				$UserID = $User->ID;
		
		?>
<?php 
//echo "Temp prompt= ". content_url('/uploads/temp/', dirname(__FILE__));
//echo plugins_url('/uploads/temp/', dirname(__FILE__));  
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
			$("#output").html("Please Select an Image to upload");
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
		if(fsize>(5 * 1024 * 1024) 
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
<div id="upload-wrapper" style="width: 90%">
<div align="left">
<p>
<b><?php echo $User; ?>,</b> you may upload photos of you and your family.</br> If you wish to upload a profile image for a person, it is easier for you ( and me ) if you submit from <a href="/family"> Family page.</a>
</p>
Select image to upload by clicking on Browse Button. There is a limit of 5Mb for the picture size. If size is greater, there will be an error message. 

<!-- Input Form -->
<form class="upload-wrapper upload-wrapper-aligned"action="<?php echo plugins_url('templates/processupload.php', dirname(__FILE__)); ?>" method="post" enctype="multipart/form-data" id="MyUploadForm"><fieldset>
<div class="upload-control-group">
            <label for="Image">Select Image</label>
            <input name="ImageFile" id="imageInput" type="file" placeholder="no file selected">  Maximum size 5Mb
</div>
<div class="upload-control-group">
            <label for="title">Title or Full Name</label>
            <input name="title" id="title" type="text" placeholder="Title / Person Name"> Enter a title or Name of the person
</div>
<div class="upload-control-group">
            <label for="Description">Description</label>
            <input name="Desc" id="Description" type="text" placeholder="Description"> Short description about the image
</div>
<div class="upload-control-group">
            <label for="Notes" class="textarea">Additional Notes</label> 
               <textarea rows="4" cols="50" name="Notes" placeholder="Tell Me More..."></textarea> Notes about the event - If group photograph, people in the photograh
</div>
<p>
<input type="submit"  id="submit-btn" value="Upload Photo" style="position: absolute; left: 228px;"/><br />
<img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>" id="loading-img" style="display:none;" alt="Please Wait"/>
</p>
</div>
<fieldset></form>
<!-- Input Form -->
<div id="output"></div>
</div>
</div>
