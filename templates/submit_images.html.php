<!-- Upload images Modified for BootStrap March 2016-->
<!doctype html>
<html lang="en">
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload photos</title>
	<script type="text/javascript" src="<?php echo plugins_url('js/jquery-1.10.2.min.js', dirname(__FILE__)); ?>"></script>
	<script type="text/javascript" src="<?php echo plugins_url('js/jquery.form.min.js', dirname(__FILE__)); ?>"></script>

	<?php 	$current_user = wp_get_current_user();
				$User = $current_user->user_firstname;
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
		if(fsize>(5 * 1024 * 1024)) 
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
<div class="container">
    <div class="row">   
        <div class="col-md-8 col-md-offset-1 col-sm-8 col-sm-offset-1">
         <h4><?php echo $User; ?>, <br />you may upload photos of you and your family.</h4> 
        <p>If you wish to upload a profile image for a person, it is easier for you ( and me ) if you submit from Family page.
		</div>
        <div class="col-md-8 col-md-offset-1 col-sm-8 col-sm-offset-1">
         Select image to upload by clicking on Browse Button. There is a limit of 5Mb for the picture size. If size is greater, there will be an error message.
        </p>
		</div>
	</div>
</div>
<!-- Input Form -->
<div id="upload-wrapper" style="width: 90%">

<form class="form-horizontal" action="<?php echo plugins_url('templates/processupload.php', dirname(__FILE__)); ?>" method="post" enctype="multipart/form-data" id="MyUploadForm">
<fieldset>
	<div class="form-group upload-control-group">
            <label for="Image" class="control-label col-sm-3">Select Image</label>
            <div class="control-label col-sm-6">
            <input name="ImageFile" id="imageInput" class="form-control" type="file" placeholder="no file selected">
            </div>
			<div class="col-sm-3 col-md-3">Maximum size 5Mb</div>
	</div>
	<div class="form-group upload-control-group">
            <label for="title"class="control-label col-sm-3">Title or Full Name</label>
            <div class="col-sm-6">
                <input name="title" id="title" class="form-control" type="text" placeholder="Title / Person Name">
            </div>
			<div class="col-sm-3 col-md-3"> Enter a title or Name of the person</div>
	</div>
	<div class="form-group upload-control-group">
            <label for="Description" class="control-label col-sm-3">Description</label>
            <div class="col-sm-6">
              <input name="Desc" id="Description" class="form-control" type="text" placeholder="Description">
            </div>
			<div class="col-sm-3 col-md-3"> Short description about the image</div>
	</div>
	<div class="form-group upload-control-group">
            <label for="Notes" class="textarea control-label col-sm-3">Additional Notes</label> 
			<div class="col-sm-6">
                <textarea rows="4" cols="50" name="Notes" class="form-control" placeholder="Tell Me More..."></textarea>
			</div>
			<div class="col-sm-3 col-md-3">Notes about the event - If group photograph, people in the photograh</div>
	</div>
	<div class="col-sm-3 col-sm-offset-3">
	<input type="submit"  id="submit-btn" value="Upload Photo" style="position: center;"/><br />
	<img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>" id="loading-img" style="display:none;" alt="Please Wait"/>
	</div>
</div>
<fieldset>
</form>
</div>
<div class="row">
<div id="output"></div>
</div>
</div>
</div>
</body>
</html> 