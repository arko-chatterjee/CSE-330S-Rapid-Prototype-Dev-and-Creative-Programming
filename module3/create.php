<?php
    require '/home/arko/mod3Files/database.php';
    session_start();
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8"/>
    <title>Write a Story</title>
    <style>
        body{
            background-color:rgb(40,41,41);
            color: lightgray;
        }
        a{
            color:lightblue;
        }
    </style>
</head>

<body>
    <?php
    if(!isset($_SESSION['user_id'])){//Quick check to see if someone is logged in
        $_SESSION['pageFrom'] = "create.php";
        echo 'You must be logged in to write a story. <a href="login.php">Login here</a>';
        exit;
    }
    ?>
    <div>
        <h1>Make a Post</h1>
        <form method='POST' id='storyForm'>
            Title: <input type='text' name='title'/><br>
            Link: <input type='text' name='link'/><br>
            <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>'/>
            <label for='anonCheck'>Publish Anonymously?</label> <input type='checkbox' id='anonCheck' name='anonCheck'/><br><br>
            <textarea name='storyText' rows='5' cols='30'>Enter story text here...</textarea><br><br>
            <button type='submit'>Publish Story</button>
        </form><br>
        <?php
            if(isset($_POST['title']) && isset($_POST['link'])){
                $author = (string)$_SESSION['user_id'];
                $title = (string)$_POST['title'];
                $link = (string)$_POST['link'];
                $text = (string)$_POST['storyText'];
                if (strlen($title) < 1){ //someone typed something for the title
                    echo 'Your post must have a title!';
                } else if (!filter_var($link,FILTER_VALIDATE_URL)){ //check if link is a good link
                    echo 'Your post must have a valid link!';
                } else if(!hash_equals($_SESSION['token'], $_POST['token'])){ //CSRF validation
                    die("Request forgery detected");
                } else {
                    if (isset($_POST['anonCheck'])){
                        $anon = 'y';
                    } else {$anon = 'n';}
                    //adds story into the database
                    $stmt = $mysqli->prepare("INSERT into stories (story_author,story_title,story_text,link,is_anon) values (?,?,?,?,?)");
                    if(!$stmt){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $stmt->bind_param('sssss',$author,$title,$text,$link,$anon);
                    $stmt->execute();
                    $stmt->close();
                    echo "<p>Story successfully published! Go <a href='main.php'>back home</a> or to <a href='profile.php'>your profile</a> to view it and others</p>";
                }
            }
        ?>
        <p>Go <a href='main.php'>back home</a></p>
    </div>
</body>
</html>