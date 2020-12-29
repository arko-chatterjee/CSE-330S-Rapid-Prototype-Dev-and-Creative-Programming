<?php
    require '/home/arko/mod3Files/database.php';
    session_start();
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8"/>
    <title>Editing</title>
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
    if((!isset($_SESSION['user_id']) || !isset($_POST['s_id']) || !isset($_POST['title']) || !isset($_POST['link']) || !isset($_POST['text']) || !isset($_POST['anon'])) && !(isset($_POST['newTitle']) && isset($_POST['newLink']))){// check to see if someone should be here
        header("Location: main.php");
        exit;
    } else if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    ?>
    <div>
        <h1>Edit a Post</h1>
        <form method='POST' id='storyForm'>
            <?php
                //did the editor come from the right page/is the owner/correct things exist?
                if(isset($_POST['title']) && isset($_POST['link']) && isset($_POST['anon']) && isset($_POST['text'])){
                    $oldTitle = (string)$_POST['title'];
                    $oldLink = (string) $_POST['link'];
                    $oldText = (string)$_POST['text'];
                    $oldAnon = (string)$_POST['anon'];
                    printf("Title: <input type='text' name='newTitle' value='%s'/><br>",htmlspecialchars($oldTitle));
                    printf("Link: <input type='text' name='newLink' value='%s'/><br>",htmlspecialchars($oldLink));
                    if($oldAnon === 'y'){
                        printf("<label for='anonCheck'>Publish Anonymously?</label> <input type='checkbox' id='anonCheck' name='anonCheck' checked/><br><br>");
                    } else {
                        printf("<label for='anonCheck'>Publish Anonymously?</label> <input type='checkbox' id='anonCheck' name='anonCheck'/><br><br>");
                    }
                    printf("<textarea name='newText' rows='5' cols='30'>%s</textarea><br><br>",htmlspecialchars($oldText));
                    printf("<input type='hidden' name='s_id' value='%s'/>",htmlspecialchars($_POST['s_id']));
                } else {
                    echo "Title: <input type='text' name='newTitle'/><br>";
                    echo "Link: <input type='text' name='newLink'/><br>";
                    echo "<label for='anonCheck'>Publish Anonymously?</label> <input type='checkbox' id='anonCheck' name='anonCheck'/><br><br>";
                    echo "<textarea name='storyText' rows='5' cols='30'>Enter story text here...</textarea><br><br>";
                    printf("<input type='hidden' name='s_id' value='%s'/>",htmlspecialchars($_POST['s_id']));
                }
            ?>
            <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>'/>
            <button type='submit'>Update Story</button>
        </form><br>
        <?php
            if(isset($_POST['newTitle']) && isset($_POST['newLink'])){
                $author = (string)$_SESSION['user_id'];
                $title = (string)$_POST['newTitle'];
                $link = (string)$_POST['newLink'];
                $text = (string)$_POST['newText'];
                $storyID = (int)$_POST['s_id'];
                $from = (string)$_SESSION['pageFrom'];
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
                    //updates entry in the database
                    $stmt = $mysqli->prepare("update stories set story_title=?,story_text=?,link=?,is_anon=? where pk_story_id=? and user_id=?");
                    if(!$stmt){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $stmt->bind_param('ssssis',$title,$text,$link,$anon,$storyID,$author);
                    $stmt->execute();
                    $stmt->close();
                    printf("<p>Story successfully edited! Go <a href='%s'>back to the story</a> or <a href='main.php'>back home</a> to view it and others</p>",htmlspecialchars($from));
                }
            }
        ?>
        <p>Go <a href='main.php'>back home</a></p>
    </div>
</body>
</html>