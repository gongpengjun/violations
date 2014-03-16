<?php

header("Content-Type: application/json; charset=utf-8");

require_once('./lib/violateInfo.php');

try
{
    if(!isset($_REQUEST['typeid']))
        throw new Exception("No typeid in request.");

    $violateNum = $_REQUEST['typeid'];
    $info = violateInfo($violateNum);
    echo json_encode($info);
} 
catch (Exception $e) 
{
    $result = (object)array(
        "error" => array(
            "code"   => 400019,
            "prompt" => $e->getMessage()
        )
    );
    echo json_encode($result);
}

?>