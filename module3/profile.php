<?php
    require '/home/arko/mod3Files/database.php';
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: main.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8"/>
    <title>My Profile</title>
    <style>
        body{
            background-color:rgb(40,41,41);
            color: lightgray;
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
        .wide {
            width: 100%;
        }
        .flex {
            display: flex;
        }
        .links {
            margin-right: 28px;
            background-color:rgb(55,55,55);
        }
        .storyButtons, .commentButtons{
            display:flex;
        }
        .info{
            font-size: 11px;
        }
        h3 {
            margin-bottom:0px;
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
            $_SESSION['pageFrom'] = 'main.php';
            if(isset($_SESSION["user_id"])){
                $username = $_SESSION['user_id'];
                //user is already logged in, no need to put the username/password login button, and register button
                //need logout button
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
    <div>
        <h2>Welcome to Your Profile</h2>
        <div id = 'stories'>
            <?php
            $_SESSION['pageFrom'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $stmtCount = $mysqli->prepare("select count(*) from stories where story_author = ?");
            if(!$stmtCount){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmtCount->bind_param('s',$username);
            $stmtCount->execute();
            $stmtCount->bind_result($stCount);
            $stmtCount->fetch();
            $stmtCount->close();
            printf("<h3 class='wide'>Your stories (%s)</h3>",$stCount);
            //want to get stories (links to the story pages), edit/delete buttons, and things to put in form for the buttons
            $stmtSt=$mysqli->prepare("select story_title,pk_story_id,link,story_text,is_anon from stories where story_author=?");
            if(!$stmtSt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmtSt->bind_param('s',$username);
            $stmtSt->execute();
            $stmtSt->bind_result($stTitle,$stID,$st_link,$st_text,$isAnon);
            echo "<div class='flex'>";
            while($stmtSt->fetch()){
                echo "<div class = 'links'>";
                printf("<a href='view.php?s=%s'>%s</a><br>",$stID,$stTitle);
                echo "<div class='storyButtons'>";
                
                echo "<form id='editSForm' method='POST' action='editStory.php'>";
                ?>
                <input type="hidden" name="token" value='<?php echo $_SESSION["token"];?>'/>
                <?php
                    printf('<input type="hidden" name="title" value="%s"/>',htmlspecialchars($stTitle));
                    printf('<input type="hidden" name="s_id" value="%s"/>',htmlspecialchars($stID));
                    printf('<input type="hidden" name="link" value="%s"/>',htmlspecialchars($st_link));
                    printf('<input type="hidden" name="text" value="%s"/>',htmlspecialchars($st_text));
                    printf('<input type="hidden" name="anon" value="%s"/>',htmlspecialchars($isAnon));
                    echo "<button type='submit'>Edit</button>&nbsp;&nbsp;";
                echo "</form>";
                echo "<form id='deleteSForm' method='POST' action='delete.php'>";
                ?>
                <input type="hidden" name="token" value='<?php echo $_SESSION["token"];?>'/>
                <?php  
                    printf('<input type="hidden" name="s_id" value="%s"/>',htmlspecialchars($stID));
                    echo '<input type="hidden" name="type" value="story"/>';
                    echo "<button type='submit'>Delete</button>";
                echo "</form>";
                
                echo "</div>";
                echo "</div>";
            }
            $stmtSt->close();
            echo "</div>";
            ?>
            <br><br>
        </div>
        <div id = 'comments'>
        <?php
            $stmtCount = $mysqli->prepare("select count(*) from comments where comment_author = ?");
            if(!$stmtCount){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmtCount->bind_param('s',$username);
            $stmtCount->execute();
            $stmtCount->bind_result($coCount);
            $stmtCount->fetch();
            $stmtCount->close();
            printf("<h3 class='wide'>Your comments (%s)</h3>",$coCount);
            //want comment text to display, story title for which comment belongs to, story id for the link to the story, edit/delete buttons, comment id
            $stmtCm=$mysqli->prepare("select pk_comment_id,comment_text,story_id,stories.story_title from comments join stories on (comments.story_id=stories.pk_story_id) where comment_author=?");
            if(!$stmtCm){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmtCm->bind_param('s',$username);
            $stmtCm->execute();
            $stmtCm->bind_result($comID,$comText,$st_ID,$st_Title);
            echo "<div class='flex'>";
            while($stmtCm->fetch()){
                echo "<div class='links'>";
                printf('"%s"<br>',$comText);
                echo "<p class='info'>";
                printf('Commented on <a href="view.php?s=%s" class="info">%s</a>',$st_ID, $st_Title);
                echo "</p>";
                echo "<div class='commentButtons'>";
                        echo "<form class='editCForm' method='POST' action='editComment.php'>";
                            ?>
                            <input type="hidden" name="token" value='<?php echo $_SESSION["token"];?>'/>
                            <?php
                            printf('<input type="hidden" name="c_id" value="%s"/>',htmlspecialchars($comID));
                            printf('<input type="hidden" name="text" value="%s"/>',htmlspecialchars($comText));
                            echo "<button type='submit'>Edit</button>&nbsp;&nbsp;";
                        echo "</form>";
                        echo "<form class='deleteCForm' method='POST' action='delete.php'>";
                            ?>
                            <input type="hidden" name="token" value='<?php echo $_SESSION["token"];?>'/>
                            <?php  
                                printf('<input type="hidden" name="c_id" value="%s"/>',htmlspecialchars($comID));
                                echo '<input type="hidden" name="type" value="comment"/>';
                                echo "<button type='submit'>Delete</button>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
            }
            $stmtCm->close();
            echo "</div>";
            ?>
        </div>
    </div>   
</body>
</html>