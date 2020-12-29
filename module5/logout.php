<?php
//starts the session, destroys the session, and redirects back to the main page
session_start();
session_destroy();
echo json_encode(array(
    "success" => true
));
session_start();
$_SESSION['loggedIn'] = false;
exit;
?>