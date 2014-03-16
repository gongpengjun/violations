<?php

header("Content-Type: application/json; charset=utf-8");

require_once('./lib/employeeInfo.php');

try
{
    if(!isset($_REQUEST['employeeid']))
        throw new Exception("No employeeid request.");

    $employeeid = $_REQUEST['employeeid'];
    $info = employeeInfo($employeeid);
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