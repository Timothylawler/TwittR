<?php

//var_dump($_REQUEST);
//var_dump($_FILES);

if(isset($_FILES['file'])){
	//echo("asd");
	$uploadOk = 1;
	$targetDirectory = "uploads/";

	//	Create placeholder for the file to save
	$target = $targetDirectory . basename($_FILES['file']['name']);
	//	Save file extension to limit to twitters requirements
	$imageFileType = pathinfo($target,PATHINFO_EXTENSION);
	// Check if file already exists
	if (file_exists($target)) {
		//	TODO fix better error handling
		echo json_encode(array('exists' => 1, 'path' => $target));
		$uploadOk = 0;
	}
	//	Twitter only accepts JPG, PNG, GIF and WEBP
	if($imageFileType != 'jpg' && $imageFileType != 'png' 
		&& $imageFileType != 'gif' && $imageFileType != 'webp'){
		echo http_response_code(400);
		echo "File extension of " . $imageFileType . " not allowed. Only accepts jpg, png, gif and webp"; //	bad request
		
	}
	//	Check so that an actual image was sent along
	$check = getimagesize($_FILES['file']['tmp_name']);
	if(!$check){
		echo "Not an image";
		$uploadOk = 0;
	}
	else if($uploadOk){
		//	Hold the file extension for later.
		
		//echo("OKEJ!");
		//	upload file
		if(move_uploaded_file($_FILES['file']['tmp_name'], $target)){
			//	Return full path to the file now on the server
			echo json_encode(array('path' => $target));
		}
		else{
			//	echo internal server error, cant store file
			echo http_response_code(500);
		}

	}

}
//echo ("Neee");




?>