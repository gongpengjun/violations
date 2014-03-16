<?php

function allowNegativeScores() 
{
    require_once('./lib/db.php');

    $query = "SELECT NegativeScores FROM ap_systemmode";
    $result = mysql_query($query);
    if(!$result)
        throw new Exception(mysql_error());
    $numrows = mysql_num_rows($result);
    if($numrows == 0) {
        $allow = FALSE;
        //throw new Exception("NegativeScores doesn't exist.");
    } else if($numrows > 1) {
        $allow = FALSE;
        //throw new Exception("too many NegativeScores.");
    } else {
        $info = mysql_fetch_assoc($result);
        $allow = (BOOL)$info['NegativeScores'];
    }
    return $allow;
}

?>