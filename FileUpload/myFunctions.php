<?php

function uploadFile($target_path, $fileFormName)
{
	$return_value = "error";
	
	initialiseDebugging();
	
	// Make the directory into which to store the file. Note if the directory already exists
	// then it won't be made.
	mkdir($target_path);
	
	// For debug purposes
	debug("Original File name: " . $_FILES[$fileFormName]['name'] . "<br/>");
	debug("basename: " . basename( $_FILES[$fileFormName]['name']) . "<br/>");
	
	// Build the target path so that it includes the directory and the name of the
	// file
	// e.g. uploads/test.doc
	
	$target_path = $target_path . basename( $_FILES[$fileFormName]['name']); 
	
	// For debug purposes
	debug("Temp File name: " . $_FILES[$fileFormName]['tmp_name'] . "<br/>");
	
	// Move the file from the temporary location to the target location
	if(move_uploaded_file($_FILES[$fileFormName]['tmp_name'], $target_path)) 
	{
		debug("The file ".  basename( $_FILES[$fileFormName]['name']) . " has been uploaded" . "<br/>");
		$return_value = $_FILES[$fileFormName]['name'];
	} 
	else
	{
		debug("There was an error uploading the file, please try again!");
	}
	
	return $return_value;
}

function initialiseDebugging()
{
	if (!isset($_SESSION['debugMsgs']))
	{
		$_SESSION['debugMsgs'] = array();
	}
}

function debug($msg)
{
	$_SESSION['debugMsgs'][] = $msg;
}
?>