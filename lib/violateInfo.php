<?php
function violateInfo($violateTypeNum) 
{
    if(is_int($violateTypeNum)) {
        throw new Exception("Invalid violateTypeNum ($violateTypeNum)");
    }

    require_once('./lib/db.php');

    $query = "SELECT ViolateTypeID, ViolateTypeNum, ViolateTypeName, ViolateScores " .
        	"FROM ap_ViolateType " .
        	"WHERE ViolateTypeNum = $violateTypeNum";

    $result = mysql_query($query);
    if(!$result)
    	throw new Exception(mysql_error());
    $numrows = mysql_num_rows($result);
    if($numrows == 0) {
        throw new Exception("violateTypeNum ($violateTypeNum) doesn't exist.");
    } else if($numrows > 1) {
        throw new Exception("too many ($numrows) violate info with same violateTypeNum ($violateTypeNum).");
    } else {
        $info = mysql_fetch_assoc($result);
    }
    return $info;
}
?>