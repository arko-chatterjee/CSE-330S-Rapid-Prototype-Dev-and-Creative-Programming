<?php
    require '/home/arko/mod3Files/database.php';
    session_start();
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8"/>
    <title>Delete</title>
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
    <div>
        <?php
            if(!isset($_POST['token'])|| !isset($_POST['type'])){ //checks if token exists and if type exists from submitted form
                echo "<p>You must be lost, weary traveller. <a href='main.php'>Go home?</a></p>";
                exit;
            } else if(!hash_equals($_SESSION['token'], $_POST['token'])){ //CSRF validation
                die("Request forgery detected");
            } else {
                $type = (string)$_POST['type'];
                if($type === 'story'){
                    if(!isset($_POST['s_id'])){ //if there's no story id, send them to main page
                        echo "<p>You must be lost, weary traveller. <a href='main.php'>Go home?</a></p>";
                        exit;
                    } else { //delete the story who has pk_story_id=$story
                        $story = $_POST['s_id'];
                        $stmt=$mysqli->prepare("delete from stories where pk_story_id=?");
                        if(!$stmt){
                            printf("Query Prep Failed: %s\n", $mysqli->error);
                            exit;
                        }
                        $stmt->bind_param('i',$story);
                        $stmt->execute();
                        $stmt->close();
                        $stmtTwo=$mysqli->prepare("delete from comments where story_id=?");
                        if(!$stmtTwo){
                            printf("Query Prep Failed: %s\n", $mysqli->error);
                            exit;
                        }
                        $stmtTwo->bind_param('i',$story);
                        $stmtTwo->execute();
                        $stmtTwo->close();
                    }
                } else if ($type === 'comment'){
                    if(!isset($_POST['c_id'])){ //if there's no comment id, send to main page
                        echo "<p>You must be lost, weary traveller. <a href='main.php'>Go home?</a></p>";
                        exit;
                    } else { //delete the story who has pk_comment_id=$comment
                        $comment = $_POST['c_id'];
                        $stmt=$mysqli->prepare("delete from comments where pk_comment_id=?");
                        if(!$stmt){
                            printf("Query Prep Failed: %s\n", $mysqli->error);
                            exit;
                        }
                        $stmt->bind_param('i',$comment);
                        $stmt->execute();
                        $stmt->close();
                    }
                } else {
                    echo "<p>You must be lost, weary traveller. <a href='main.php'>Go home?</a></p>";
                    exit;
                }
                
                echo "<p>Post/Comment Sucessfully Deleted, <a href='main.php'>go home </a>or ";
                if($type === 'comment'){
                    $locationString="main.php";
                        if(isset($_SESSION['pageFrom'])){
                            $pageFrom = (string)$_SESSION['pageFrom'];
                        }
                    printf("<a href='%s'>go back</a></p>",htmlspecialchars($pageFrom));
                }
            }
        ?>
    </div>
</body>
</html>