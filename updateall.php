<?php

header("Content-Type: application/json; charset=utf-8");

require_once('./lib/typeVersion.php');
require_once('./lib/employeeVersion.php');

try
{
    // get version
    $tVersion = typeVersion();
    $eVersion = employeeVersion();

    if(isset($_REQUEST['type_version']) && isset($_REQUEST['employee_version'])) {
        $oldTVersion = (int)$_REQUEST['type_version'];
        if(is_int($oldTVersion) && $oldTVersion >= $tVersion && is_int($oldEVersion) && $oldEVersion >= $eVersion) {
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

    $tRows = array();
    while($row = mysql_fetch_object($query)) {
        $tRows[] = $row;
    }
    
    // get all employee
    $sql = "SELECT cm_lc_userinfo . * , cm_lc_deptinfo.DeptName " .
            "FROM cm_lc_userinfo " .
            "INNER JOIN cm_lc_deptinfo ON cm_lc_deptinfo.DeptID = cm_lc_userinfo.DeptID";
    $query = mysql_query($sql);
    if(!$query)
        throw new Exception(mysql_error());
    $numrows = mysql_num_rows($query);
    if($numrows == 0)
        throw new Exception('no employee data in database.');

    $eRows = array();
    while($row = mysql_fetch_object($query)) {
        $eRows[] = $row;
    }
    
    $result = (object)array( 
        @"type_version" => "$tVersion",
        @"types" => $tRows, 
        @"employee_version" => "$eVersion",
        @"employees" => $eRows, 
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