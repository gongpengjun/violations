<?php

// in php.ini, inseconds
// session.gc_maxlifetime = 1440
//ini_set('session.gc_maxlifetime',1440);

session_start(); // see http://www.php.net/session_start


if(isset($_SESSION['PageLoaded']))
{
	$_SESSION['PageLoaded']++;
}
else
{
	$_SESSION['PageLoaded'] = 1;
}

$result = array(
    "PageLoaded" => $_SESSION['PageLoaded'],
    "SID" => session_id()
);

header("Content-Type: application/json");
echo json_encode($result);

?>
