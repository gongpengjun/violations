<?php

header("Content-Type: application/json; charset=utf-8");

session_start(); // for super global variable $_SESSION

//die(json_encode($_POST));

require_once('./lib/assertUpload.php');

$errors = array();

// validate input parameters
try 
{
    if(isset($_SESSION['userid'])) {
        $OperID = $_SESSION['userid'];
        if(0){
            if(isset($_POST['operid'])) {
                $OperID = trim($_POST['operid']);
                if(filter_var($OperID,FILTER_VALIDATE_INT) == FALSE)
                    $errors[] = "OperID is not integter";
            } else {
                $errors[] = "OperID is not specify";
            }
        }
    } else {
        if(1) {
            throw new Exception('You are not logged in. please go to login page (login.html)');
        } else {
            header( "refresh:5; url=login.html"); 
            throw new Exception('You are not logged in. will jump to login page after 5 seconds.');
        }
    }

    if(isset($_POST['employeeid'])) {
        $EmployeeID = trim($_POST['employeeid']);
        if(filter_var($EmployeeID,FILTER_VALIDATE_INT) == FALSE)
            $errors[] = "EmpoyeeID is not integter";
    } else {
        $errors[] = "EmpoyeeID is not specify";
    }

    if(isset($_POST['typenum'])){
        $ViolateTypeNum = trim($_POST['typenum']);
        if(filter_var($ViolateTypeNum,FILTER_VALIDATE_INT) == FALSE)
            $errors[] = "ViolateTypeNum is not integter";
    } else {
        $errors[] = "ViolateTypeNum is not specify";
    }

    if(isset($_POST['place'])) {
        $ViolatePlace = $_POST['place'];
        $ViolatePlace  = mysql_real_escape_string($ViolatePlace);
    }

    if(isset($_POST['mobile'])) {
        $MobileRecord = trim($_POST['mobile']);
        if(filter_var($MobileRecord,FILTER_VALIDATE_INT) == FALSE)
            $errors[] = "MobileRecord is not integter";
    } else {
        $errors[] = "MobileRecord is not specify";
    }

    if(isset($_POST['DeviceID'])) {
        $MobileDevID = trim($_POST['DeviceID']);
        //if(filter_var($MobileDevID,FILTER_VALIDATE_INT) == FALSE)
        //    $errors[] = "DeviceID is not integter";
    } else {
        $errors[] = "DeviceID is not specify";
    }

    if(isset($_POST['date'])) {
        $ViolateDate = $_POST['date'];
    }

    if(isset($_POST['time'])) {
        $ViolateTime = $_POST['time'];
    }

    if (!array_key_exists('photo1', $_FILES)) {
        throw new Exception('Photo1 not found in uploaded data');
    }

    $photo1 = $_FILES['photo1'];

    // ensure the file was successfully uploaded
    assertValidUpload($photo1['error']);

    if (!is_uploaded_file($photo1['tmp_name'])) {
        throw new Exception('File is not an uploaded file');
    }

    $info1 = getImageSize($photo1['tmp_name']);

    if (!$info1) {
        throw new Exception('File is not an image');
    }
}
catch (Exception $e) 
{
    $errors[] = $e->getMessage();
}

if (count($errors) > 0) 
{
    die(
        json_encode(
            (object)array(
                    "error" => array(
                        "code"   => 40022,
                        "prompt" => join(',', $errors),
                    )
            )
        )
    );
}

// process parameters
$image1 = mysql_real_escape_string(
            file_get_contents($photo1['tmp_name'])
          );

require_once('./lib/db.php');
require_once('./lib/employeeInfo.php');
require_once('./lib/violateInfo.php');
require_once('./lib/employeeScore.php');
require_once('./lib/systemMode.php');

// get info from database
try
{
    // employee name, dept info
    $info = employeeInfo($EmployeeID);
    if(isset($info['UserName']))
        $UserName = $info['UserName'];
    else
        throw new Exception('UserName not found');

    if(isset($info['DeptID']))
        $DeptID = $info['DeptID'];
    else
        throw new Exception('DeptID not found');

    if(isset($info['DeptName']))
        $DeptName = $info['DeptName'];
    else
        throw new Exception('DeptName not found');

    // violate type, score info
    $info = violateInfo($ViolateTypeNum);
    if(isset($info['ViolateTypeName']))
        $ViolateTypeName = $info['ViolateTypeName'];
    else
        throw new Exception('ViolateTypeNum not found');

    if(isset($info['ViolateTypeNum']))
        $ViolateTypeNum = $info['ViolateTypeNum'];
    else
        throw new Exception('ViolateTypeNum not found');

    if(isset($info['ViolateScores']))
        $ViolateScores = $info['ViolateScores'];
    else
        throw new Exception('ViolateScores not found');

    // current score info
    $BeforeScores = employeeScore($EmployeeID);
}
catch(Exception $e)
{
    die(
        json_encode(
            (object)array(
                    "error" => array(
                        "code"   => 40022,
                        "prompt" => $e->getMessage(),
                    )
            )
        )
    );
}

$AfterScores = $BeforeScores - $ViolateScores;
if($AfterScores < 0) {
    if( !allowNegativeScores() ) {
        $AfterScores = 0;
    }
}

date_default_timezone_set('PRC');
$now = date_create();
$CreateDate = $now->format('Y-m-d H:i:s');

if(!isset($ViolateDate)) {
    $ViolateDate = $now->format('Y-m-d');
}
if(!isset($ViolateTime)) {
    $ViolateTime = $now->format('H:i:s');
}

if(0) {
die(
    json_encode(
        array(
            "UserName" => $UserName, 
            "EmployeeID" => $EmployeeID, 
            "DeptID" => $DeptID, 
            "DeptName" => $DeptName, 
            "ViolatePlace" => $ViolatePlace,
            "ViolateTypeNum" => $ViolateTypeNum, 
            "BeforeScores" => $BeforeScores,
            "ViolateScores" => $ViolateScores, 
            "AfterScores" => $AfterScores,
            "OperID" => $OperID, 
            "MobileRecord" => $MobileRecord, 
            "MobileDevID" => $MobileDevID,
            "ViolateDate" => $ViolateDate,
            "ViolateTime" => $ViolateTime,
            "CreateDate" => $CreateDate,
        )
    )
);
}

// insert into database
if(isset($ViolatePlace) && $ViolatePlace != '') {
    $sql = "INSERT INTO ap_ViolateRecord " .
    "(UserName, EmployeeID, DeptID, DeptName, ViolatePlace, 
      ViolateDate, ViolateTime, CreateDate, 
      ViolateTypeNum, BeforeScores, ViolateScores, AfterScores,
      OperID, MobileRecord, MobileDevID, Photo1) " . 
    "VALUES " . 
    "('$UserName', '$EmployeeID', '$DeptID', '$DeptName', '$ViolatePlace', 
      '$ViolateDate', '$ViolateTime', '$CreateDate', 
      '$ViolateTypeNum', '$BeforeScores', '$ViolateScores', '$AfterScores', 
      '$OperID', '$MobileRecord', '$MobileDevID', '$image1')";    
} else {
    $sql = "INSERT INTO ap_ViolateRecord " .
    "(UserName, EmployeeID, DeptID, DeptName, 
      ViolateDate, ViolateTime, CreateDate, 
      ViolateTypeNum, BeforeScores, ViolateScores, AfterScores,
      OperID, MobileRecord, MobileDevID, Photo1) " . 
    "VALUES " . 
    "('$UserName', '$EmployeeID', '$DeptID', '$DeptName',
      '$ViolateDate', '$ViolateTime', '$CreateDate', 
      '$ViolateTypeNum', '$BeforeScores', '$ViolateScores', '$AfterScores', 
      '$OperID', '$MobileRecord', '$MobileDevID', '$image1')";    
}

$query = mysql_query($sql);
if($query)
{
    $id = mysql_insert_id();

    echo json_encode( array("record_id" => $id, "message" => "上传成功") );

    //header('Location: view.php?id=' . $id);

    // $image = mysql_query("SELECT Photo1 FROM ap_ViolateRecord WHERE RecordID=$id");
    // $image = mysql_fetch_assoc($image);
    // $image = $image['Photo1'];
    // header("Content-Type: image/jpeg");
    // echo $image;
}
else
{
    die(
        json_encode(
            (object)array(
                    "error" => array(
                        "code"   => 40012,
                        "prompt" => mysql_error()
                    )
            )
        )
    );
}


?>