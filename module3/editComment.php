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
    if((!isset($_SESSION['user_id']) || !isset($_POST['c_id']) || !isset($_POST['text'])) && !isset($_POST['newText'])){// check to see if someone should be here
        header("Location: main.php");
        exit;
    } else if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    echo "<div>";
    echo "<h1>Edit Comment</h1>";
    echo "<form method='POST'>";
    //populates form with textarea pre-filled with the text from the comment
    if(isset($_POST['text'])){
        $oldText = (string)$_POST['text'];
        printf("<textarea name='newText' rows='5' cols='30'>%s</textarea><br><br>",htmlspecialchars($oldText));
    } else {
        printf("<textarea name='newText' rows='5' cols='30'>Top Text</textarea><br><br>");
    }
    printf("<input type='hidden' name='c_id' value='%s'/>",htmlspecialchars($_POST['c_id']));
    ?>
    <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>'/>
    <?php
    echo "<button type='submit'>Edit Comment</button>";
    echo "</form><br>";
    if(isset($_POST['newText'])){//only executes once the new text that was edited and submit button was pressed
        $text = (string)$_POST['newText'];
        $commentID = (int)$_POST['c_id'];
        $uID = (int)$_SESSION['user_id'];
        if(strlen($text) < 1){
            echo "Must have some text in the box!";
        } else if(!hash_equals($_SESSION['token'], $_POST['token'])){//csrf validation
            die("Request forgery detected");
        } else { //updates comment in the database
            $stmt=$mysqli->prepare("update comments set comment_text=? where pk_comment_id=? and user_id=?");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->bind_param('sis',$text,$commentID,$uID);
            $stmt->execute();
            $locationString="Location: main.php";
            if(isset($_SESSION['pageFrom'])){
                $pageFrom = (string)$_SESSION['pageFrom'];
                $locationString = sprintf("Location: %s",htmlspecialchars($pageFrom));
            }
            $stmt->close();
            header($locationString);
            //back to the story that the comment editor came from
        }
        //a way to go back to home page if something went wrong
        echo "<p><a href='main.php'>go home</a></p>";
    }
    echo "</div>";
    ?>
</body>
</html>