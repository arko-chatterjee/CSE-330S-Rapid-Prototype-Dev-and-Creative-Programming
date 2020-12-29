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
    $eventid = (string)$json_obj['eventid'];
    $userid = (int)$json_obj['userid'];
    $title = (string)$json_obj['title'];
    $desc = (string)$json_obj['desc'];
    $startDate = (string)$json_obj['startDate'];
    $endDate = (string)$json_obj['endDate'];
    $startTime = (string)$json_obj['startTime'];
    $endTime = (string)$json_obj['endTime'];
    $location = (string)$json_obj['location'];
    $token = (string)$json_obj['token'];
    $tags = (string)$json_obj['tags'];

    if(!hash_equals($_SESSION['token'], $token)){
        die("Request forgery detected");
    }
    if(!$title || strlen($title)<1){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid Title"
        ));
        exit;
    }
    if(strlen($startDate) < 4 || strlen($endDate) < 4){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid Dates"
        ));
        exit;
    }
    if(strlen($startTime) < 4 || strlen($endTime) < 4){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid Times"
        ));
        exit;
    }

    
    $multi = $startDate == $endDate ? "N" : "Y";
    $begin = $startDate == $endDate ? $startTime : $startDate;
    $end = $startDate == $endDate ? $endTime : $endDate;
    $stmt = $mysqli->prepare("update events set title=?,multi=?,date=?,start=?,end=?,location=?,description=?,tags=? where event_id=? and user_id=?");
    $stmt->bind_param('ssssssssii',$title,$multi,$startDate,$begin,$end,$location,$desc,$tags,$eventid,$userid);

    $stmt->execute();
    echo json_encode(array(
        "success" => true,
        "message" => "Event Successfully Created"
    ));
    exit;
?>