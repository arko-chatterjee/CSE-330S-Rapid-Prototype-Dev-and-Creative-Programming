<?php
    require '/home/arko/mod3Files/database.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8"/>
    <title>Bruh News</title>
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
            width:60%;
            background-color:rgb(55,55,55);
            border-radius:5%;
        }
        h2{
            padding-bottom:0px;
            margin-bottom:0px;
        }
        p {
            margin-top: 1px;
            padding-top: 1px;
        }
        #belowTop{
            display: inline-block;
            width:100%;
        }
        #sort{
            float:right;
            text-align: right;
        }
        #writeButton{
            float:left;
            text-align: left;
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
        <div id='belowTop'>
    <?php
        //Option to write story only if logged in
        if(isset($_SESSION['user_id'])){
            echo '<a href="create.php" id="writeButton"><button>Write a Story</button></a>';
        }
        ?>
        <form id='sort' method='POST'>
            <label for='sortBy'>Sort by:</label>
            <select name="sortBy" id="sortBy">
                <option value="alpha" <?php if (isset($_POST['sortBy'])){if($_POST['sortBy']==='alpha'){echo "selected";}}?>>Story Title (Ascending)</option>
                <option value="alphaDesc" <?php if (isset($_POST['sortBy'])){if($_POST['sortBy']==='alphaDesc'){echo "selected";}}?>>Story Title (Descending)</option>
                <option value="create" <?php if (isset($_POST['sortBy'])){if($_POST['sortBy']==='create'){echo "selected";}}?>>Creation Time (Ascending)</option>
                <option value="createDesc" <?php if (isset($_POST['sortBy'])){if($_POST['sortBy']==='createDesc'){echo "selected";}}?>>Creation Time (Descending)</option>
                <option value="modify" <?php if (isset($_POST['sortBy'])){if($_POST['sortBy']==='modify'){echo "selected";}}?>>Edit Time (Ascending)</option>
                <option value="modifyDesc" <?php if (isset($_POST['sortBy'])){if($_POST['sortBy']==='modifyDesc'){echo "selected";}}?>>Edit Time (Descending)</option>
            </select>
            <button type='submit'>Sort</button>
        </form>
        <?php
        echo "</div>";
        //do a prepared sql statement to get all the stories that exist
        //populate page with all the stories that exist so far
            //title (hyperlink to the story)
            //author //datetime-created,modified, link to the website
        //default sorted by story name
        $stmt=$mysqli->prepare("SELECT pk_story_id, story_author, story_title, link, is_anon, creation_time, modify_time FROM stories order by story_title asc");
        if(isset($_POST['sortBy'])){
            $sorted = (string)$_POST['sortBy'];
            if($sorted === 'alpha'){
                $stmt=$mysqli->prepare("SELECT pk_story_id, story_author, story_title, link, is_anon, creation_time, modify_time FROM stories order by story_title asc");
            } else if($sorted === 'create'){
                $stmt=$mysqli->prepare("SELECT pk_story_id, story_author, story_title, link, is_anon, creation_time, modify_time FROM stories order by creation_time asc");
            } else if($sorted === 'modify'){
                $stmt=$mysqli->prepare("SELECT pk_story_id, story_author, story_title, link, is_anon, creation_time, modify_time FROM stories order by modify_time asc");
            } else if($sorted === 'alphaDesc'){
                $stmt=$mysqli->prepare("SELECT pk_story_id, story_author, story_title, link, is_anon, creation_time, modify_time FROM stories order by story_title desc");
            } else if($sorted === 'createDesc'){
                $stmt=$mysqli->prepare("SELECT pk_story_id, story_author, story_title, link, is_anon, creation_time, modify_time FROM stories order by creation_time desc");
            } else if($sorted ==='modifyDesc'){
                $stmt=$mysqli->prepare("SELECT pk_story_id, story_author, story_title, link, is_anon, creation_time, modify_time FROM stories order by modify_time desc");
            }
        }
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->execute();
        $stmt->bind_result($story_id,$author,$title,$link,$is_anon,$create,$modify);
        while($stmt->fetch()){
            echo '<div class="storyBox">';
            printf("<h2><a href='view.php?s=%s'>%s</a></h2>",htmlspecialchars($story_id), htmlspecialchars($title));
            if($is_anon==='y'){
                $author='Anonymous';
            }
            if($modify){
                printf("<p>Author: %s, Created: %s, Last Modified: %s,         (<a href='%s' target='blank'>%s</a>)</p>",htmlspecialchars($author),htmlspecialchars($create),htmlspecialchars($modify),htmlspecialchars($link),htmlspecialchars(parse_url($link)['host']));
            } else {
                printf("<p>Author: %s, Created: %s,         (<a href='%s' target='blank'>%s</a>)</p>",htmlspecialchars($author),htmlspecialchars($create),htmlspecialchars($link),htmlspecialchars(parse_url($link)['host']));
            }
            echo '</div>';
        }
        $stmt->close();
    ?>
    </div>
</body>
</html>