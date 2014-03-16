<?php

header("Content-Type: application/json; charset=utf-8");

require_once('./lib/violateInfo.php');

try
{
    if(!isset($_REQUEST['typeid']))
        throw new Exception("No typeid in request.");

    $$violateTypeID = (int)$_REQUEST['typeid'];
    if(!is_int($$violateTypeID))
        throw new Exception('typeid is not integer.');

    $info = violateInfo($$violateTypeID);
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