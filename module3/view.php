<?php
    require '/home/arko/mod3Files/database.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8"/>
    <title>View Story</title>
    <style>
        body{
            background-color:rgb(40,41,41);
            color: lightgray;
            margin: 1px;
            padding: 1px;
        }
        .topBar {
            width: 100%;
            background-color:rgb(60,60,60);
            /* text-align : right; */
            padding:1px;
            display : inline-block;
        }
        #topLeft{
            padding : 0px;
            margin: 0px;
            text-align: left;
            float: left;
        }
        #topRight{
            text-align: right;
            right : 0px;
            float : right;
        }
        a{
            color:lightblue;
        }
        .normalColor{
            color: lightgray;
            text-decoration: none;
        }
        .normalColor:hover{
            color:lightblue;
        }
        .storyBox{
            width:90%;
            background-color:rgb(55,55,55);
            border-radius:5%;
            margin-top: 17px;
            display: inline-block;
            margin-left: 15px;
        }
        h2 {
            margin-top: 2px;
            margin-bottom: 3px;
        }
        .info{
            font-size: 11px;
        }
        #textBox{
            border-width: 4px;
            border-radius: 10%;
            border-color: black;
            border-style: solid;
            padding-right: 5px;
            padding-left: 5px;
            margin-right: 5px;
            margin-left: 5px;
        }
        #editSForm{
            float:right;
        }
        #deleteSForm{
            float: right;
        }
        .smallMargin{
            margin: 23px 1px 1px 1px;
        }
        .comments {
            margin-left: 7px;
            width:100%;
        }
        .commentBox{
            text-align: left;
            margin-bottom: 35px;
            padding-left:1em;
            /* display:inline-block; */
        }
        .commentButtons{
            display: inline-block;
        }
        .editCForm{
            float:left;
        }
        .deleteCForm{
            float:left;
        }
    </style>
</head>
<body>
    <div class='topBar'>
        <div>
            <h1 id='topLeft'><a class='normalColor' href="main.php">News Site&reg;&trade;</a></h1>
        </div>
        <div id='topRight'>
        <?php
            session_start();
            $_SESSION['pageFrom'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            if(isset($_SESSION["user_id"])){
                $username = $_SESSION['user_id'];
                //user is already logged in, no need to put the username/password login button, and register button
                // logout button
                echo 'Hello, ', htmlentities($username),'&nbsp;<br>';
                echo '<a href="profile.php">My profile</a>&nbsp;&nbsp;&nbsp;&nbsp;','<a href="logout.php"><button>Logout</button></a>';
            } else {
                //login username/password texts and button
                echo '<form method="POST" action="login.php">';
                echo '<label>Username:</label> <input type="text" name="username"/>  <label>Password:</label> <input type="password" name="password"/>';
                echo '    ';
                ?>
                <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>'/>
                <?php
                echo '<button type="submit">Login</button>&nbsp;<br>';
                echo "New to this site? <a href='register.php'> Register here</a>&nbsp;";
                printf("</form>");
            }
        ?>
        </div>
    </div>
    <div class='storyBox'>
        <?php
            if(!isset($_GET['s'])){ //using get for story populating, since all stories on site are public
                echo "Oops! You look like you shouldn't be here!";
                exit;
            } else {
                $story_id = (int)$_GET['s'];
                $stmt=$mysqli->prepare("SELECT COUNT(*),story_author,story_title,story_text,link,is_anon,creation_time,modify_time FROM stories where pk_story_id = ?");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('i',$story_id);
                $stmt->execute();
                $stmt->bind_result($count,$st_author,$st_title,$st_text,$st_link,$isAnon,$st_create,$st_mod);
                $stmt->fetch();
                echo "<br>";
                if($count === 0){
                    $stmt->close();
                    echo "That story doesn't exist!";
                    exit;
                } else { //show the story
                    $stmt->close();
                    printf("<h2>%s</h2>",htmlspecialchars($st_title));
                    $ogAuthor = (string)$st_author;
                    if($isAnon==='y'){
                        $st_author='Anonymous';
                    }
                    //display info about author, creation time, modify time
                    printf("<p class='info'>Author: %s, Created: %s, Last Modified: %s,         (<a href='%s' target='blank'>%s</a>)</p>",htmlspecialchars($st_author),htmlspecialchars($st_create),htmlspecialchars($st_mod),htmlspecialchars($st_link),htmlspecialchars(parse_url($st_link)['host']));
                    printf("<p id='textBox'>%s</p>",htmlspecialchars($st_text));
                    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $ogAuthor){ //provide edit/delete buttons for when author is logged in
                        echo "<div class='storyButtons'>";
                        echo "<form id='deleteSForm' method='POST' action='delete.php'>";
                        ?>
                        <input type="hidden" name="token" value='<?php echo $_SESSION["token"];?>'/>
                        <?php  
                            printf('<input type="hidden" name="s_id" value="%s"/>',htmlspecialchars($story_id));
                            echo '<input type="hidden" name="type" value="story"/>';
                            echo "<button type='submit'>Delete Story</button>";
                        echo "</form>";
                        echo "<form id='editSForm' method='POST' action='editStory.php'>";
                        ?>
                        <input type="hidden" name="token" value='<?php echo $_SESSION["token"];?>'/>
                        <?php
                            printf('<input type="hidden" name="title" value="%s"/>',htmlspecialchars($st_title));
                            printf('<input type="hidden" name="s_id" value="%s"/>',htmlspecialchars($story_id));
                            printf('<input type="hidden" name="link" value="%s"/>',htmlspecialchars($st_link));
                            printf('<input type="hidden" name="text" value="%s"/>',htmlspecialchars($st_text));
                            printf('<input type="hidden" name="anon" value="%s"/>',htmlspecialchars($isAnon));
                            echo "<button type='submit'>Edit Story</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo "</form>";
                        
                        echo "</div>";
                    }
                }
            }
        ?>
    </div>
    <div class='comments'>
        <?php
            //create comment box form
                //only if the user is logged in
                //with the csrf token for creating comment
            if(isset($_SESSION["user_id"])){
                echo"<p class='smallMargin'>Write a Comment:</p>";
                printf("<form method='POST' id='commentCreate' action='%s'>",htmlspecialchars((string)$_SESSION['pageFrom']));
                ?>
                <input type="hidden" name="token" value='<?php echo $_SESSION["token"];?>'/>
                <?php
                echo"<textarea name='commentText' rows='5' cols='40'>Enter comment text here...</textarea><br>";
                echo "<button type='submit'>Comment</button>";
                echo "</form>";
                if(isset($_POST['commentText'])){
                    if(!hash_equals($_SESSION['token'], $_POST['token'])){
                        die('Request forgery detected');
                    }
                    $comTextSubmit = (string)$_POST['commentText'];
                    if(strlen($comTextSubmit) < 1){ //makes sure user doesn't submit a blank comment
                        echo "Comment must be longer than 0 characters!";
                    } else {
                        $stmtComPost = $mysqli->prepare("insert into comments (comment_author,comment_text,story_id) values (?,?,?)");
                        if(!$stmtComPost){
                            printf("Query Prep Failed: %s\n", $mysqli->error);
                            exit;
                        }
                        $stmtComPost->bind_param('ssi',$username,$comTextSubmit,$story_id);
                        $stmtComPost->execute();
                        $stmtComPost->close();
                    }
                }
            }
            //query database for comments to fill below (if they exist)
                //if the user is the comment author, options to edit/delete
                //2 forms, both with csrf tokens
            $stmtGetCom=$mysqli->prepare("select pk_comment_id,comment_author,comment_text,comment_write_time,comment_edit_time from comments where story_id=?");
            if(!$stmtGetCom){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmtGetCom->bind_param('i',$story_id);
            $stmtGetCom->execute();
            $stmtGetCom->bind_result($comId,$comAuthor,$comText,$comWT,$comET);
            while($stmtGetCom->fetch()){
                if(isset($username)){
                    $isMine = $username === $comAuthor;
                }
                echo "<div class='commentBox'>";
                printf("<p>%s</p>",htmlspecialchars($comText));
                if($comET){
                    printf("<p class='info'>Author: %s, Created: %s, Last Modified: %s</p>",htmlspecialchars($comAuthor),htmlspecialchars($comWT),htmlspecialchars($comET));
                } else {
                    printf("<p class='info'>Author: %s, Created: %s",htmlspecialchars($comAuthor),htmlspecialchars($comWT));
                }
                if(isset($isMine)){ //if comment author is the one logged in, provide edit and delete buttons
                    if($isMine){
                        echo "<div class='commentButtons'>";
                        echo "<form class='editCForm' method='POST' action='editComment.php'>";
                            ?>
                            <input type="hidden" name="token" value='<?php echo $_SESSION["token"];?>'/>
                            <?php
                            printf('<input type="hidden" name="c_id" value="%s"/>',htmlspecialchars($comId));
                            printf('<input type="hidden" name="text" value="%s"/>',htmlspecialchars($comText));
                            echo "<button type='submit'>Edit</button>&nbsp;&nbsp;&nbsp;";
                        echo "</form>";
                        echo "<form class='deleteCForm' method='POST' action='delete.php'>";
                            ?>
                            <input type="hidden" name="token" value='<?php echo $_SESSION["token"];?>'/>
                            <?php  
                                printf('<input type="hidden" name="c_id" value="%s"/>',htmlspecialchars($comId));
                                echo '<input type="hidden" name="type" value="comment"/>';
                                echo "<button type='submit'>Delete</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                }
                echo "</div>";
            }
            $stmtGetCom->close();
        ?>
    </div>
</body>
</html>