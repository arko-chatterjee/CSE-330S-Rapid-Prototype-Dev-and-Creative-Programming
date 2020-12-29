<?php
    require '/home/arko/mod5/database.php';

    session_start();

    $previous_ua = @$_SESSION['useragent'];
	$current_ua = $_SERVER['HTTP_USER_AGENT'];

	if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
		die("Session hijack detected");
	}else{
		$_SESSION['useragent'] = $current_ua;
	}

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
            "message" => "Invalid Username or Password"
        ));
        exit;
    } else {
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");
                    $stmt->bind_param('s', $username);
                    $stmt->execute();
                    $stmt->bind_result($cnt);
                    $stmt->fetch();
        if( $cnt === 0){
            $stmt->close();
            $password_hashed=password_hash($password,PASSWORD_DEFAULT);
            $stmtTwo= $mysqli->prepare("insert into users (username, hashed_password) values (?,?)");
            
            $stmtTwo->bind_param('ss',$username,$password_hashed);
            $stmtTwo->execute();

            echo json_encode(array(
                "success" => true,
                "message" => "Successfully registered, login now"
            ));
            exit;
        }else{
            echo json_encode(array(
                "success" => false,
                "message" => "Username Already Exists"
            ));
            exit;
        }
    }
?>