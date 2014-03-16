<?php

session_start(); // for super global variable $_SESSION

header("Content-Type: application/json; charset=utf-8;");

if(isset($_SESSION['username']) && isset($_SESSION['userid']))
{
    $result = (Object)array(
    	"status"   => 0,
    	"username" => $_SESSION['username'],
        "userid" => $_SESSION['userid'],
    );
    echo json_encode($result);
}
else
{
	$result = array(
		"status" => -1,
		"error"	 => array(
            "code"   => 40002,
            "prompt" => "You must be logged in."
		)
	);
	die(json_encode($result));
}

?>