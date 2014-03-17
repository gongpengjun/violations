<?php

header("Content-Type: application/json; charset=utf-8");

require_once('./lib/db.php');
require_once('./lib/employeeVersion.php');

try
{
    // get version
    $version = employeeVersion();

    if(isset($_REQUEST['version'])) {
        $oldversion = (int)$_REQUEST['version'];
        if(is_int($oldversion) && $oldversion >= $version) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }
    }
    $result = (object)array( 
        @"version" => "$version",
    );
    echo json_encode($result);
}
catch(Exception $e)
{
    json_encode(
        (object)array(
                "error" => array(
                    "code"   => 40012,
                    "prompt" => $e->getMessage()
                )
        )
    );
}

?>