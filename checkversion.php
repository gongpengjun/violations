<?php


header("Content-Type: application/json; charset=utf-8");

require_once('./lib/typeVersion.php');

try {
    $version = typeVersion();
    echo json_encode( array( "version" => $version ) );
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