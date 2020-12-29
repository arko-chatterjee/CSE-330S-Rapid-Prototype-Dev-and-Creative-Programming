<?php
session_start();

$filename = $_GET['f'];
$username = $_SESSION['currentUser'];
$full_path = sprintf("/home/arko/module2_things/%s/%s", $username, $filename);

if(unlink($full_path)){
    header("Location: del_suc.html");
    exit;
} else {
    header("Location: del_fail.html");
    exit;
}
?>