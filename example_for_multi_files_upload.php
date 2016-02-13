<form action="example_for_multi_files_upload.php" method="post"
	enctype="multipart/form-data">
	<input type="file" name="images[]" multiple /> <input type="submit"
		name="submit" />
</form>
<hr>
<?php
require_once 'classes/MultiFileUploader.class.php';

if (isset ( $_POST ['submit'] )) {
	echo "<pre>";
	// var_dump($_FILES['images']);
	if (isset ( $_FILES ['images'] )) {
		$files_data = $_FILES ['images'];
		$uploading_directory = 'uploads/multi'; // uploading directory
		
		/**
		 * for example if your upload images
		 *
		 * @var $files_types
		 */
		$files_types = array (
				'image/jpg',
				'image/jpeg',
				'image/png',
				'image/gif' 
		);
		
		/**
		 * for example exts types of the images
		 *
		 * @var $files_exts
		 */
		$files_exts = array (
				'jpg',
				'jpeg',
				'gif',
				'png' 
		);
		
		// new object of the MultiFileUploader class
		$up = new MultiFileUploader ( $files_data, $uploading_directory, $files_types, $files_exts );
		
		$up->start_upload (); // init files data
		
		/**
		 * MultiFileUploader::finish_upload ()
		 * upload the files with check every validation
		 * then @return an array with to index
		 * 1st index "uploaded": the names of the uploaded files
		 * 2nd index "errors" : array of errors of Invalid files validation
		 */
		var_dump ( $up->finish_upload () );
	}
}

?>

