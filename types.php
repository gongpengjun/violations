<?php

header("Content-Type: application/json; charset=utf-8");

require_once('./lib/db.php');
require_once('./lib/typeVersion.php');

try
{
    // get version
    $version = typeVersion();

    if(isset($_REQUEST['version'])) {
        $oldversion = (int)$_REQUEST['version'];
        if(is_int($oldversion) && $oldversion >= $version) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }
    }

    // get types
    $sql = "SELECT ap_ViolateCategory.ViolateCategoryID, ViolateCategoryName, ViolateTypeNum, ViolateTypeName, ViolateScores " .
            "FROM ap_ViolateType, ap_ViolateCategory " .
            "WHERE ap_ViolateType.ViolateCategoryID = ap_ViolateCategory.ViolateCategoryID";
    $query = mysql_query($sql);
    if(!$query)
        throw new Exception(mysql_error());
    $numrows = mysql_num_rows($query);
    if($numrows == 0)
        throw new Exception('no violate type data in database.');

    $rows = array();
    while($row = mysql_fetch_object($query)) {
        $rows[] = $row;
    }
    
    $result = (object)array( 
        @"version" => "$version",
        @"types" => $rows, 
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