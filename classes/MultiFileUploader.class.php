<?php
require_once ("upload.class.php");

/*
 * Uploader
 * Manage file uplading
 * Under GNU/GPL license
 */

/**
 *
 * @author : Moustafa Mohammed Elgammal
 *        
 * @copyright elgammal
 *           
 * @version 1.0
 *         
 * @link https://eg.linkedin.com/in/moustafa-mohammed-elgammal-7823a393
 */
class MultiFileUploader {
	private $data; // for the data of the form
	private $files; // to handle the form data array
	private $files_counter; // to know the number of the files of the operation
	/**
	 * this data get from user in the constructor.
	 */
	private $directory; // the directory
	private $types_array; // file type
	private $exts_array; // extintions of the files
	/**
	 * responsing of the uploading operation
	 */
	private $response;
	private $errors;
	
	/**
	 *
	 * @method constructor
	 *         whith initiate data
	 *         of validation
	 *        
	 * @param $_FILES[] $data        	
	 * @param string $directory        	
	 * @param array $types        	
	 * @param array $exts        	
	 */
	public function __construct($data, $directory, $types, $exts) {
		// init
		$this->files = array ();
		$this->response = array ();
		// set initdata
		$this->data = $data; // the form FILE data
		$this->directory = $directory; // dir to move files there
		$this->exts_array = $exts;
		$this->types_array = $types;
		// help to deal with the loops
		$this->files_counter = count ( $data ["name"] );
	}
	
	/**
	 *
	 * @method start_upload
	 *        
	 *         handle the form data array into array of many files
	 *        
	 *         to upload each file as a single object
	 *        
	 * @return void
	 */
	public function start_upload() {
		for($j = 0; $j < $this->files_counter; $j ++) {
			foreach ( $this->data as $key => $value ) {
				$this->files [$j] ["$key"] = $this->data [$key] [$j];
			}
		}
	}
	
	/**
	 * $this function use for each to loop uploading file
	 *
	 * @return array ($uploaded,$errors)
	 */
	public function finish_upload() {
		// echo "<pre>";
		// var_dump ( $this->files );
		foreach ( $this->files as $key => $file ) {
			$one_file = new uploader ();
			// give it FILES array with input name
			$one_file->Upload_Init ( $file );
			
			// Set Upload directory >>if not found it will be created
			$one_file->Upload_Set_dir ( $this->directory );
			
			// set allowed mime type
			$one_file->Upload_Set_Type ( $this->types_array );
			
			// set allowed extensions
			$one_file->Upload_Set_Ext ( $this->exts_array );
			
			// Process uploading
			if ($one_file->Upload ())
				$this->response [] = $file ["name"] . ' :uploaded.<br>';
			else
				$this->errors [$file ["name"]] = $one_file->Errors ();
			unset ( $one_file );
		}
		
		return array ( // response array
				"uploaded" => $this->response,
				"errors" => $this->errors 
		);
	}
}
