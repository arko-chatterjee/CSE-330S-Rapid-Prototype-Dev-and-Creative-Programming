<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8"/>
    <title>File Sharing Site</title>
    <style>
        body{
            font-family: serif;
            background-color:rgb(40,41,41);
            color: lightgray;
        }
    </style>
</head>

<body>
    <div>
        <h1>Welcome to this file sharing website!</h1>
        <form method = 'POST' >
            <label>Username:</label> <input type='text' name='u'/><br>
            <button type='submit'>Login</button><br>
        </form>
    </div>
</body>

</html>
<?php
    session_start();
    // Checks if there is already a user logged in based on session variable
    if(!isset($_SESSION['currentUser'])){
        //Checks if a username was submitted in the form
        if(isset($_POST['u'])){
            $login_username = (string)$_POST['u'];
            //The following lines check if the username is in the usernames text file
            $names = fopen('/home/arko/module2_things/usernames.txt', 'r');
            $usernames_array = array();
            for ($i=0; $i<3; $i++){
                array_push($usernames_array, (string)trim(fgets($names)));
            }
            fclose($names);
            if(in_array($login_username, $usernames_array)){ //If the name is, then user is redirected to the active.php page
                $_SESSION['currentUser'] = $login_username;
                header("Location: active.php");
                exit;
            } else {    //If the user is not in the text file, a message is displayed that the user is invalid
                echo(htmlentities("Invalid User"));
            }
        }
    } else {                    //Redirects to the active page if a user is already logged in
        header("Location: active.php");
        exit;
    }
?>