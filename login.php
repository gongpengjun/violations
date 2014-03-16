<?php

session_start();

header("Content-Type: application/json; charset=utf-8");

try
{
	// check post parameter
	if(!isset($_POST['username']) || strlen($_POST['username']) == 0)
		throw new Exception('username is empty.');
	if(!isset($_POST['password']))
		throw new Exception('password is empty.');
	
	// read post parameter
	$username = $_POST['username'];
	$password = $_POST['password'];

	// query database
	require_once('./lib/db.php');
	$sql = "SELECT * FROM ap_sysuser WHERE SysUserName='$username'";
	$query = mysql_query($sql);
	if(!$query)
		throw new Exception(mysql_error());

	$numrows = mysql_num_rows($query);
	if($numrows == 0)
		throw new Exception("User ($username) doesn't exist.");

	while ($row = mysql_fetch_assoc($query))
	{
		$dbuserid = $row['SysUserID'];
		$dbusername = $row['SysUserName'];
		$dbpassword = $row['Password'];

		if($username==$dbusername && $password==$dbpassword)
		{
			$_SESSION['userid'] = $dbuserid;
			$_SESSION['username'] = $dbusername;

            $result = (object)array(
                "message" => "You're in!",
                "user" => array(
                	"userid" => $dbuserid,
                    "username" => $username,
                    "password" => $password
                )
            );    
            echo json_encode($result);
            exit;
		}
	}
	throw new Exception("Incorrect password");
}
catch(Exception $e)
{
    $result = (object)array(
        "error" => array(
            "code"   => 40004,
            "prompt" => $e->getMessage()
        )
    );    
    echo json_encode($result);
}

?>