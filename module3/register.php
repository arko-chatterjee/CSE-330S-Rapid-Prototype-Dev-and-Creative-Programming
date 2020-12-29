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
    <title>Register</title>
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
        <h1>Registration</h1>
        <form method='POST'>
            Username: <input type='text' name='username'><br>
            Password:&nbsp; <input type='text' name='password'><br><br>
            <button type=submit>Register</button>
        </form>
        <?php
            if(isset($_POST['username']) && isset($_POST['password'])){
                $user = (string)$_POST['username'];
                $pass = (string)$_POST['password'];
                if(strlen($user) < 2){
                    echo 'Invalid Username: Must be longer than 1 character';
                } elseif(!preg_match('/^[A-Za-z0-9_\@\.\/\#\&\+\-]*$/', $user)){ //using this regex for certain characters, don't want others
                    echo 'Invalid username: Cannot contain certain special characters';
                } elseif(strlen($pass) < 2){
                    echo 'Invalid Password: Must be longer than 1 character';
                } else {
                    if(preg_match('/^[A-Za-z0-9_\@\.\/\#\&\+\-]*$/', $pass)){
                        //Check if username is already in database
                        $stmt = $mysqli->prepare("select username from users WHERE username=?");
                        if(!$stmt){
                            printf("Query Prep Failed: %s\n", $mysqli->error);
                            exit;
                        }
                        $stmt->bind_param('s',$user);
                        $stmt->execute();
                        $stmt->store_result();
                        //does the username exist in the database
                        if(($stmt->num_rows === 0)){ //if it doesn't, user is put into the database
                            $stmt->close();
                            $password_hashed=password_hash($pass,PASSWORD_DEFAULT);
                            $stmtTwo= $mysqli->prepare("insert into users (username, password) values (?,?)");
                            if(!$stmt){
                                printf("Query Prep Failed: %s\n", $mysqli->error);
                                exit;
                            }
                            $stmtTwo->bind_param('ss',$user,$password_hashed);
                            $stmtTwo->execute();
                            $stmtTwo->close();
                            echo 'Success! Go to the <a href="login.php">login page</a> now to log in!';                    
                        } else {
                            $stmt->close();
                            echo 'That username already exists!';
                        }
                    } else {
                        echo 'Invalid Password: Cannot contain contain certain special characters';
                    }
                }

            }
        ?>
        <br><br>
        <p>Here by mistake? <a href='login.php'>Back to Login</a></p><br><br>
        <p><em><a class="big" href='main.php'>Back Home</a></em></p>
    </div>
</body>
</html>