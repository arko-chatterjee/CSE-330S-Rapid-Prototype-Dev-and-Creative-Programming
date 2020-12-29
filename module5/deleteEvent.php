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
    $eventid = (int)$json_obj['eventid'];
    $userid = (int)$json_obj['userid'];
    $token = (string)$json_obj['token'];

    if(!hash_equals($_SESSION['token'], $token)){
        die("Request forgery detected");
    }
    $stmtCount = $mysqli->prepare("select count(*) from events where user_id=? and event_id=?");
    $stmtCount->bind_param('ii',$userid,$eventid);
    $stmtCount->execute();
    $stmtCount->bind_result($count);
    $stmtCount->fetch();
    $stmtCount->close();

    if($count < 1){
        echo json_encode(array(
            "success" => false,
            "message" => "Unable to Delete Event"
        ));
        exit;
    }
    $stmt = $mysqli->prepare("delete from events where user_id=? and event_id=?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => $mysqli->error
        ));
        exit;
    }
    $stmt->bind_param('ii',$userid,$eventid);
    $stmt->execute();

    echo json_encode(array(
        "success"=>true,
        "message"=>"Successfully Deleted Event"
    ));
    exit;
?>