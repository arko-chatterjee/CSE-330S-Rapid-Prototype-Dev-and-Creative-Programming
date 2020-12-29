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
    $username = (string)$_SESSION['username'];
    $userid = (int)$json_obj['userid'];
    $token = (string)$json_obj['token'];

    if(!hash_equals($_SESSION['token'], $token)){
        die("Request forgery detected");
    }
    $stmt = $mysqli->prepare("select users.username, event_id, title, multi, date, start, end, location, description, tags from events join users on (events.user_id=users.user_id) where users.user_id=?");
    $stmt->bind_param('s',$userid);
    $stmt->execute();
    $stmt->bind_result($uname,$eid,$title,$multi,$date,$start,$end,$loc,$desc,$tags);
    $eventidArray = array();
    $titleArray = array();
    $multiArray = array();
    $dateArray = array();
    $startArray = array();
    $endArray = array();
    $locArray = array();
    $descArray = array();
    $tagArray = array();
    while($stmt->fetch()){
        if(trim($uname) == trim($username)){
            array_push($eventidArray,$eid);
            array_push($titleArray, $title);
            array_push($multiArray, $multi);
            array_push($dateArray,$date);
            array_push($startArray,$start);
            array_push($endArray,$end);
            array_push($locArray,$loc);
            array_push($descArray,$desc);
            array_push($tagArray,$tags);
        }
    }
    echo json_encode(array(
        "success"=>true,
        "eventids"=>$eventidArray,
        "titles"=>$titleArray,
        "multis"=>$multiArray,
        "dates"=>$dateArray,
        "starts"=>$startArray,
        "ends"=>$endArray,
        "locs"=>$locArray,
        "descs"=>$descArray,
        "tags"=>$tagArray
    ));
    exit;
?>