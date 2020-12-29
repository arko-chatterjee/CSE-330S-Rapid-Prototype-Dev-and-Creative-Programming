<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        header("Location: main.php");
        exit;
    }
    require '/home/arko/mod3Files/database.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <style>
        body{
            background-color:rgb(40,41,41);
            color: lightgray;
        }
        div{
            text-align: center;
        }
        a,a:link,a:visited{
            color:lightblue;
        }
        .big{
            font-size: 30px;
        }
    </style>
</head>

<body>
    <div>
        <h1>Login Here</h1>
        <form method='POST'>
            Username: <input type='text' name='username'><br>
            <input type="hidden" name="token" value="<?php echo $_SESSION["token"];?>"/>
            Password:&nbsp; <input type='password' name='password'><br><br>
            <button type=submit>Login</button>
        </form>
        <?php
            if(isset($_POST['username']) && isset($_POST['password'])){
                $user = (string)$_POST['username'];
                $pass = (string)$_POST['password'];
                //checks if either username or password is too short or contains some bad characters (like ;)
                if((strlen($user)<2) || (strlen($pass)<2) || !preg_match('/^[A-Za-z0-9_\@\.\/\#\&\+\-]*$/', $user) || !preg_match('/^[A-Za-z0-9_\@\.\/\#\&\+\-]*$/', $pass)){
                    echo 'Invalid username/password';
                } else {
                    $stmt = $mysqli->prepare("SELECT COUNT(*), username, password FROM users WHERE username=?");
                    $stmt->bind_param('s', $user);
                    $stmt->execute();
                    $stmt->bind_result($cnt, $user_id, $pwd_hash);
                    $stmt->fetch();
                    if($cnt == 1 && password_verify($pass, $pwd_hash)){
                        // Login succeeded!
                        $_SESSION['user_id'] = $user;
                        $_SESSION['token']=bin2hex(random_bytes(32)); //creates token for use in forms to prevent CSRF
                        // Redirect to your target page
                        $locationString="Location: main.php";
                        if(isset($_SESSION['pageFrom'])){
                            $pageFrom = (string)$_SESSION['pageFrom'];
                            $locationString = sprintf("Location: %s",htmlspecialchars($pageFrom));
                        }
                        $stmt->close();
                        header($locationString);
                    } else{
                        // Login failed; redirect back to the login screen
                        $stmt->close();
                        echo "Invalid Username or Password";
                    }
                }
            }
        ?>
        <br><br>
        <p>New to this site? <a href='register.php'>Register here</a></p><br><br>
        <p><em><a class="big" href='main.php'>Back Home</a></em></p>
    </div>
</body>
</html>