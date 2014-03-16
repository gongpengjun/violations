<?php

function employeeInfo($employeeid) 
{
    if(is_int($employeeid)) {
        throw new Exception("Invalid employeeid ($employeeid)");
    }

    require_once('./lib/db.php');

    $query = "SELECT EmployeeID, UserName, cm_lc_userinfo.DeptID, DeptName " .
        "FROM cm_lc_userinfo, cm_lc_deptinfo " .
        "WHERE EmployeeID = $employeeid " .
        "AND cm_lc_userinfo.DeptID = cm_lc_deptinfo.DeptID";

    $result = mysql_query($query);
    if(!$result)
        throw new Exception(mysql_error());
    $numrows = mysql_num_rows($result);
    if($numrows == 0) {
        throw new Exception("employee ($employeeid) doesn't exist.");
    } else if($numrows > 1) {
        throw new Exception("too many employee with same EmployeeID ($employeeid).");
    } else {
        $info = mysql_fetch_assoc($result);
    }
    return $info;
}

?>