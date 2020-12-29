<?php
    require '/home/arko/mod5/database.php';

    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

    //Because you are posting the data via fetch(), php has to retrieve it elsewhere.
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);

    //Variables can be accessed as such:
    $username = $json_obj['username'];
    $password = $json_obj['password'];
    //This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

    // Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
    if((strlen($username)<2) || (strlen($password)<2) || !preg_match('/^[A-Za-z0-9_\@\.\/\#\&\+\-]*$/', $username) || !preg_match('/^[A-Za-z0-9_\@\.\/\#\&\+\-]*$/', $password)){
        echo json_encode(array(
            "success" => false,
            "message" => "Incorrect Username or Password"
        ));
        exit;
    } else {
        $stmt = $mysqli->prepare("SELECT COUNT(*),user_id, username, hashed_password FROM users WHERE username=?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($cnt, $u_id, $usernameD, $pwd_hash);
        $stmt->fetch();
        if( $cnt == 1 && password_verify($password, $pwd_hash) ){
            ini_set("session.cookie_httponly", 1);
            session_start();
            $_SESSION['loggedIn'] = True;
            $_SESSION['username'] = $usernameD;
            $_SESSION['user_id'] = $u_id;
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 

            echo json_encode(array(
                "success" => true,
                "username" => $usernameD,
                "id" => $u_id,
                "token" => $_SESSION['token']
            ));
            exit;
        }else{
            echo json_encode(array(
                "success" => false,
                "message" => "Incorrect Username or Password"
            ));
            exit;
        }
    }
?>