<?php

/*
 * Uploader
 * Manage file uplading
 * Under GNU/GPL license
 */

/**
 *
 * @author : Ahmed Syam
 * @copyright www.softmasr.com
 * @version 1.0
 *          @Contact Me : engsyam at gmail dot com
 */
class uploader {
	
	/* Inintialization */
	private $up_dir; // Directory to upload file
	private $up_file_name; // Uploaded file name
	private $up_tmp; // temporary directory
	private $up_ext; // Extension of uploaded file
	private $up_file_size; // Uploaded file size
	private $up_mime_type; // uploaded file mime-type
	private $up_error; // Uploaded file errors
	
	/* File specifications */
	private $up_file_new_name; // New generated name
	private $allowed_size = 1048576; // 1MB
	private $allowed_ext; // array of allowed extension
	private $allowed_types; // array of allowed mime-types
	private $errors;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->allowed_ext = array ();
		$this->allowed_types = array ();
	}
	
	/**
	 * Upload Init | initialize file before uploading
	 * 
	 * @param array $FilesArray        	
	 * @param string $directory        	
	 * @param array $ext        	
	 * @param array $type        	
	 */
	public function Upload_Init($FilesArray) {
		$this->up_file_name = $FilesArray ['name'];
		$this->up_error = $FilesArray ['error'];
		$this->up_file_size = $FilesArray ['size'];
		$this->up_tmp = $FilesArray ['tmp_name'];
		$this->up_mime_type = $FilesArray ['type'];
		$ext = explode ( '.', $FilesArray ['name'] );
		$this->up_ext = strtolower ( end ( $ext ) ); // Extension of uploaded file
	}
	
	/**
	 * Process-> File Uploading
	 */
	public function Upload() {
		// Check if directory is founded or not
		$this->Upload_Check_Up_Dir ();
		
		if (! isset ( $this->up_file_name ) || $this->up_error == 4) {
			$this->errors [] = 'No File Selected';
			return FALSE;
		}
		
		// Check extension or type
		if (! in_array ( $this->up_ext, $this->allowed_ext )) {
			
			$this->errors [] = 'File Type Not Allowed';
			return FALSE;
		}
		
		if (! in_array ( $this->Mime ( $this->up_tmp ), $this->allowed_types )) {
			$this->errors [] = 'File Mime-Type Not Allowed';
			return FALSE;
		}
		
		// Check File Size
		if ($this->up_file_size > $this->allowed_size) {
			$this->errors [] = 'File Size Bigger than allowed';
			return FALSE;
		}
		
		$this->Upload_File_Generate_Name ();
		
		// print $this->up_dir.'/'.$this->up_file_new_name.'.'.$this->up_ext;
		
		// move file
		$move = move_uploaded_file ( $this->up_tmp, $this->up_dir . '/' . $this->up_file_new_name . '.' . $this->up_ext );
		if ($move)
			return TRUE;
		
		$this->errors [] = 'can\'t copy file to upload directory';
		return FALSE;
	}
	
	/* ================================================================================================= */
	
	/**
	 * Generate New Name for Uploaded file
	 */
	public function Upload_File_Generate_Name() {
		$new_name = 'Softmasr_elgammal_' . sha1 ( rand ( '99999', '99999' ) . date ( 'y-m-d' ) . $this->up_file_name );
		$this->up_file_new_name = $new_name;
	}
	
	/**
	 * Set allowed Extensions
	 * 
	 * @param array $extensions        	
	 */
	public function Upload_Set_Ext($extensions) {
		if (is_array ( $extensions )) {
			$this->allowed_ext = $extensions;
		} else {
			$this->allowed_ext [] = $extensions;
		}
	}
	
	/**
	 * Set Allowed Types
	 * 
	 * @param array $types        	
	 */
	public function Upload_Set_Type($types) {
		if (is_array ( $types )) {
			$this->allowed_types = $types;
		} else {
			$this->allowed_types [] = $types;
		}
	}
	
	/**
	 * Set Uploaded File Directory
	 * 
	 * @param string $directory        	
	 */
	public function Upload_Set_dir($directory) {
		$this->up_dir = $directory;
	}
	
	/**
	 * Set Allowed Files with MB
	 * 
	 * @param int $size
	 *        	with MB
	 */
	public function Upload_Set_Max_Size($size) {
		$Max_size = (( int ) $size) * 1048576;
		$this->allowed_size = $Max_size;
	}
	
	/* ================================================================================================= */
	
	/**
	 * Check upload directory
	 * if not found : Create it
	 * copy .
	 * htaccess file for protection
	 */
	public function Upload_Check_Up_Dir() {
		if (! is_dir ( $this->up_dir ))
			mkdir ( $this->up_dir, 0777 );
		
		// Put htaccess file
	}
	
	/**
	 * Get Information of uploaded file as array to be used later
	 */
	public function Upload_Get_File_Info() {
		$info = array ();
		$info ['old'] = $this->up_file_name;
		$info ['name'] = $this->up_file_new_name;
		$info ['size'] = $this->up_file_size;
		$info ['ext'] = $this->up_ext;
		$info ['type'] = $this->up_mime_type;
		$info ['dir'] = $this->up_dir;
		// $info['path'] = $this->up_dir.$this->up_file_new_name;
		
		return $info;
	}
	function Mime($path) {
		$result = false;
		
		if (is_file ( $path ) === true) {
			if (function_exists ( 'finfo_open' ) === true) {
				$finfo = finfo_open ( FILEINFO_MIME_TYPE );
				
				if (is_resource ( $finfo ) === true) {
					$result = finfo_file ( $finfo, $path );
				}
				
				finfo_close ( $finfo );
			} 

			else if (function_exists ( 'mime_content_type' ) === true) {
				$result = preg_replace ( '~^(.+);.*$~', '$1', mime_content_type ( $path ) );
			} 

			else if (function_exists ( 'exif_imagetype' ) === true) {
				$result = image_type_to_mime_type ( exif_imagetype ( $path ) );
			}
		}
		
		return $result;
	}
	
	/**
	 * Print errors
	 * 
	 * @return string
	 */
	public function Errors() {
		$error = '';
		if (count ( $this->errors ) > 0) {
			foreach ( $this->errors as $e ) {
				$error .= '<p>' . $e . '</p><br />';
			}
		}
		
		return $error;
	}
}

?>
