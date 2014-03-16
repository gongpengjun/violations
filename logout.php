<?php

session_start();
session_destroy();

$result = (object)array(
    "status" => 0,
    "message" => "You've been logged out."
);
header("Content-Type: application/json; charset=utf-8");
echo json_encode($result);

?>