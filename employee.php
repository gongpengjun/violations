<?php

header("Content-Type: application/json; charset=utf-8");

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

    // get all employee
    $sql = "SELECT cm_lc_userinfo . * , cm_lc_deptinfo.DeptName " .
            "FROM cm_lc_userinfo " .
            "INNER JOIN cm_lc_deptinfo";
    $query = mysql_query($sql);
    if(!$query)
        throw new Exception(mysql_error());
    $numrows = mysql_num_rows($query);
    if($numrows == 0)
        throw new Exception('no employee data in database.');

    $rows = array();
    while($row = mysql_fetch_object($query)) {
        $rows[] = $row;
    }
    
    $result = (object)array( 
        @"version" => "$version",
        @"employees" => $rows, 
    );
    echo json_encode($result);    
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