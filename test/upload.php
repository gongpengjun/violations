<html>
<header>
<meta charset="utf-8">
<title>违章记录 - 上传结果</title>
</header>
<body>
<h1> 伤处处理结果 </h1>
<?php
$uploaddir = './files/';

require './lib/UploadException.php';

echo '<pre>';
if($_FILES['photo1']['error'] === UPLOAD_ERR_OK) {
	$filename = basename($_FILES['photo1']['name']);
	$uploadfile = $uploaddir . $filename;
	if (move_uploaded_file($_FILES['photo1']['tmp_name'], $uploadfile)) {
	    echo "照片一上传成功.\n";
	} else {
	    echo "Possible file upload attack!\n";
	}	
} else {
	echo "照片一上传失败, error code: " . $_FILES['photo1']['error'] . "\n";
}

if($_FILES['photo2']['error'] === UPLOAD_ERR_OK)
{
	$filename = basename($_FILES['photo2']['name']);
	$uploadfile = $uploaddir . $filename;
	if (move_uploaded_file($_FILES['photo2']['tmp_name'], $uploadfile)) {
	    echo "照片二上传成功.\n";
	} else {
	    echo "Possible file upload attack!\n";
	}
} else {
	echo "照片二上传失败, error code: " . $_FILES['photo2']['error'] . "\n";
}	

if($_FILES['photo3']['error'] === UPLOAD_ERR_OK)
{
	$filename = basename($_FILES['photo3']['name']);
	$uploadfile = $uploaddir . $filename;
	if (move_uploaded_file($_FILES['photo3']['tmp_name'], $uploadfile)) {
	    echo "照片三上传成功.\n";
	} else {
	    echo "Possible file upload attack!\n";
	}	
} else {
	echo "照片三上传失败, error code: " . $_FILES['photo3']['error'] . "\n";
}

echo 'Here is some more debugging info:';
print_r($_POST);
print_r($_FILES);
print "</pre>";

?>
</body>
</html>
