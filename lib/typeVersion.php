<?php

function typeVersion() 
{
    require_once('./lib/db.php');

    $query = "SELECT ViolateTypeSeq FROM ap_systemmode";
    $result = mysql_query($query);
    if(!$result)
        throw new Exception(mysql_error());
    $numrows = mysql_num_rows($result);
    if($numrows == 0) {
        $version = 0;
        //throw new Exception("ViolateTypeSeq doesn't exist.");
    } else if($numrows > 1) {
        $version = 0;
        //throw new Exception("too many ViolateTypeSeq.");
    } else {
        $info = mysql_fetch_assoc($result);
        $version = (int)$info['ViolateTypeSeq'];
    }
    return $version;
}

?>