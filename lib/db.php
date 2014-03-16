<?php

// mysql database config
$DB_HOST     = 'localhost';
$DB_USERNAME = 'root';
$DB_PASSWORD = 'gpj118';
$DB_DATABASE = 'mlklocaldb';

$connect = mysql_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD) or die(
    json_encode(
        (object)array(
                "error" => array(
                    "code"   => 40011,
                    "DB_HOST" => $DB_HOST,
                    "DB_USERNAME" => $DB_USERNAME,
                    "DB_PASSWORD" => $DB_PASSWORD,
                    "prompt" => "Coundn't connect MySQL server."
                )
        )
    )
);
$db = mysql_select_db($DB_DATABASE, $connect) or die(
    json_encode(
        (object)array(
                "error" => array(
                    "code"   => 40012,
                    "database" => $DB_DATABASE,
                    "prompt" => "Couldn't find database."
                )
        )
    )
);

mysql_query("SET NAMES 'utf8'");

?>