<?php


header("Content-Type: application/json; charset=utf-8");

require_once('./lib/typeVersion.php');
require_once('./lib/employeeVersion.php');

try {
    $typeVersion = typeVersion();
    $employeeVersion = employeeVersion();
    echo json_encode( array( "type_version" => $typeVersion, "employee_version" => $employeeVersion ) );

} catch (Exception $e) {
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