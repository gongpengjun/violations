<?php

define('DEFAULT_INITIAL_SCORE', 1000);

function employeeScore($employeeid) 
{
    if(is_int($employeeid)) {
        throw new Exception("Invalid employeeid ($employeeid)");
    }

    require_once('./lib/db.php');

    $query = "SELECT AfterScores FROM ap_ViolateRecord WHERE EmployeeID = $employeeid ORDER BY AfterScores ASC LIMIT 1";
    $result = mysql_query($query);
    if(!$result)
        throw new Exception(mysql_error());
    $numrows = mysql_num_rows($result);
    if($numrows == 0) {
        $score = DEFAULT_INITIAL_SCORE;
    } else {
        $info = mysql_fetch_assoc($result);
        $score = $info['AfterScores'];
    }
    return $score;
}

?>