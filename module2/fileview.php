<?php
//this code is all from https://classes.engineering.wustl.edu/cse330/index.php?title=PHP
session_start();
if(isset($_SESSION['currentUser'])){
	$filename = (string)$_GET['f'];

	// We need to make sure that the filename is in a valid format; if it's not, display an error and leave the script.
	// To perform the check, we will use a regular expression.
	if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
		echo "Invalid filename";
		exit;
	}

	// Get the username and make sure that it is alphanumeric with limited other characters.
	// You shouldn't allow usernames with unusual characters anyway, but it's always best to perform a sanity check
	// since we will be concatenating the string to load files from the filesystem.
	$username = (string)$_SESSION['currentUser'];
	if( !preg_match('/^[\w_\-]+$/', $username) ){
		echo "Invalid username";
		exit;
	}

	$full_path = sprintf("/home/arko/module2_things/%s/%s", $username, $filename);

	// Now we need to get the MIME type (e.g., image/jpeg).  PHP provides a neat little interface to do this called finfo.
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	$mime = $finfo->file($full_path);

	// Finally, set the Content-Type header to the MIME type of the file, and display the file.
	header("Content-Type: ".$mime);
	header('content-disposition: inline; filename="'.$filename.'";');
	readfile($full_path);
} else {
	header("Location: login.php");
	exit;
}
?>