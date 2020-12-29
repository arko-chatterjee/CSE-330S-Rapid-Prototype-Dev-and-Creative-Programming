  <?php
//starts the session, destroys the session, and redirects back to the main page
session_start();
if(isset($_SESSION['pageFrom'])){
    $pageFrom = (string)$_SESSION['pageFrom'];
    $locationString = sprintf("Location: %s",htmlspecialchars($pageFrom));
}
session_destroy();
header($locationString);
?>