<?php

try 
{
	$id = $_REQUEST['id'];
    if (!isset($id)) {
        throw new Exception('ID not specified');
    }

    $id = (int)$id;

    if ($id <= 0) {
        throw new Exception('Invalid ID specified');
    }

	require('./lib/db.php');
	$result = mysql_query("SELECT Photo1 FROM ap_ViolateRecord WHERE RecordID=$id");
    if (mysql_num_rows($result) == 0) {
        throw new Exception('Image with specified ID not found');
    }
    $row = mysql_fetch_assoc($result);
	$image = $row['Photo1'];
	$info = getimagesizefromstring($image);
	$mime = $info['mime'];
	header('Content-type: ' . $mime);
	echo $image;
}
catch (Exception $e) 
{
    header('HTTP/1.1 404 Not Found');
    header("Content-Type: application/json; charset=utf-8");
    die(
        json_encode(
            (object)array(
                    "error" => array(
                        "code"   => 40018,
                        "prompt" => $e->getMessage()
                    )
            )
        )
    );
}

?>