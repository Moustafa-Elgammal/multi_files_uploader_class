<form action="example_for_single_file_upload.php" method="post" enctype="multipart/form-data">   
    <input type="file" name="file" />
    <input type="submit" name="submit" value="upload" />
    
</form>
<hr>

<?php
require_once 'classes/upload.class.php';

$up = new uploader ();

if (isset ( $_POST ['submit'] )) {
	// give it FILES array with input name
	$up->Upload_Init ( $_FILES ['file'] );
	
	// Set Upload directory >>if not found it will be created
	$up->Upload_Set_dir ( 'uploads/single' );
	
	// set allowed mime type
	$up->Upload_Set_Type ( array (
			'image/jpg',
			'image/jpeg',
			'image/png',
			'image/gif' 
	) );
	
	// set allowed extensions
	$up->Upload_Set_Ext ( array (
			'jpg',
			'jpeg',
			'gif',
			'png' 
	) );
	
	// Process uploading
	$upimg = $up->Upload ();
}
?>