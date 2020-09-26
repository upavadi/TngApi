<!-- Upload images Modified for BootStrap March 2016-->
<?php
//Get max file size
$upload_content = file_get_contents( __DIR__ ."/.htaccess");
$file_size = (int)(substr($upload_content, 29));

//Enter User Messages below
$msg_welcome = "you may upload photo of you and your family.";
$msg_para_1 = "If you wish to upload a profile image for a person, it is easier for you ( and me ) if you submit from Family page.";
$msg_para_2 = "Select image to upload by clicking on Browse Button.";
$msg_para_3 = "There is a limit of ".$file_size. "Mb for the picture size. ";

?>

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

function beforeSubmit(){
		$('#submit-btn').hide(); //hide submit button
		$('#loading-img').show(); //hide submit button
		$("#output").html("");  
}
</script>
</head>
<body>
<div class="container-fluid">
  <div id="upload-wrapper" style="max-width: 700px; margin: auto" >  
     
    <p>
    <?php echo $User. ", ". $msg_welcome.  "<br/>".
    $msg_para_1. 
    "<br />". 
    $msg_para_2.
    "<br />".
    $msg_para_3     
    ?>
    </p>

  </div>
  <div style="margin-bottom: 1em"> </div>
  <!-- Input Form -->
  <div id="upload-wrapper" style="max-width: 700px; margin: auto" >

  <form class="form-horizontal" action="<?php echo plugins_url('templates/processupload.php', dirname(__FILE__)); ?>" method="post" enctype="multipart/form-data" id="MyUploadForm">
  <fieldset>
    <div class="form-group upload-control-group">

        <label for="Image" class="upload-width control-label col-sm-3">Select Image</label>
        <div class="control-label col-sm-6">
      <!-- accept="image/x-png,image/jpeg is filter for image files -->
        <input name="ImageFile" id="chooseFile" class="form-control" 
        type="file" placeholder="no file selected" accept="image/x-png,image/jpeg, image/pjpeg" required>
        </div>
      <div class="col-sm-3 col-md-3"><?php echo "Maximum Image size = ". $file_size. "Mb" ?></div>
  </div>

  <div class="user-image mb-3 text-center"><b>Preview</b>
      <div style="width: 100px; height: 100px; overflow: hidden; background: #cccc; margin: 0 auto">
        <img src="..." class="figure-img img-fluid rounded" id="imgPlaceholder" alt="">
      </div>
  </div>

  <div class="form-group upload-control-group" style="margin-top: 1em">
              <label for="title"class="upload-width control-label col-sm-3">Title or Full Name</label>
              <div class="col-sm-6">
                  <input name="title" id="title" class="form-control" type="text" placeholder="Title / Person Name" required>
    </div>
	<div class="col-sm-3 col-md-3"> Enter a title or Name of the person</div>
	</div>
	<div class="form-group upload-control-group">
            <label for="Description" class="upload-width control-label col-sm-3">Description</label>
            <div class="col-sm-6">
              <input name="Desc" id="Description" class="form-control" type="text" placeholder="Description">
            </div>
			<div class="col-sm-3 col-md-3"> Short description about the image</div>
	</div>

	<div class="form-group upload-control-group">
      <label for="Notes" class="upload-width textarea control-label col-sm-3">Additional Notes</label> 
			<div class="col-sm-6">
         <textarea rows="4" cols="50" name="Notes" class="form-control" placeholder="Tell Me More..."></textarea>
			</div>
    	<div class="col-sm-3 col-md-3" style="wi dth: 250px">Notes about the event - If group photograph, people in the photograh
      </div>
	</div>
	<div align='center'>
	<input type="submit"  id="submit-btn" value="Upload Photo" style="text-align: center;"/><br />
	<img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>" id="loading-img" style="display:none;" alt="Please Wait"/>
	</div>
</div>
<fieldset>
</form>
</div>
<div class="row">
<div id="output" style="max-width: 700px; margin: auto"></div>
</div>
</div>
</div>
</body>
<script>
function readURL(input) {

      var uploadSize = "<?php Print($file_size ); ?>";
      var FileSize = input.files[0].size / 1024 / 1024; // in MB
      if (FileSize > uploadSize) {
      var  msg = 'File size exceeds ' + uploadSize + 'Mb';
         alert(msg); return; 
      }
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#imgPlaceholder').attr('src', e.target.result);
      }

        // base64 string conversion
        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#chooseFile").change(function () {
      readURL(this);
    });
</script>
<style>
.upload-width { width: 155px; padding-left: 15px;}
</style>
</html> 
