<?php

header("Content-Type: application/json; charset=utf-8");

require_once('./lib/employeeScore.php');

try
{
    if(!isset($_REQUEST['employeeid']))
        throw new Exception("No employeeid request.");

    $employeeid = $_REQUEST['employeeid'];
    $score = employeeScore($employeeid);
    echo json_encode( array("score" => $score) );
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