<?php
//this code is all from https://classes.engineering.wustl.edu/cse330/index.php?title=PHP
session_start();
if(isset($_FILES['uploadedfile']['name'])){
    //Get filename and ensure it's valid
    $filename = (string)basename($_FILES['uploadedfile']['name']);
    if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
        echo "Invalid filename<br><br>";
        echo "<a href='active.php'><button>Back to home</button></a>";
        exit;
    }

    // Get the username and make sure it is valid
    $username = (string)$_SESSION['currentUser'];
    if( !preg_match('/^[\w_\-]+$/', $username) ){
        echo "Invalid username";
        exit;
    }

    $full_path = sprintf("/home/arko/module2_things/%s/%s", $username, $filename);

    //Moves the file and redirects to the respective page depending on how the upload went
    if( move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path) ){
        header("Location: up_suc.html");
        exit;
    }else{
        header("Location: up_fail.html");
        exit;
    }
} else {
    header("Location: active.php");
}
?>