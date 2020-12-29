<?php
//starts the session, destroys the session, and redirects back to the login page
session_start();
session_destroy();
header("Location: login.php")
?>