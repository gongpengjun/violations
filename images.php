<?php
    require('./lib/db.php');
 
    $query = sprintf('select RecordID from ap_ViolateRecord');
    $result = mysql_query($query);
 
    $images = array();
 
    while ($row = mysql_fetch_array($result)) {
        $id = $row['RecordID'];
        $images[] = $id;
    }
?>
<html>
    <head>
        <title>Uploaded Images</title>
    </head>
    <body>
        <div>
            <ul>
                <?php if (count($images) == 0) { ?>
                    <li>No uploaded images found</li>
                <?php } else foreach ($images as $id) { ?>
                    <li>
                        <a href="view.php?id=<?php echo $id ?>">
                            <?php echo htmlSpecialChars($id)  ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
    </body>
</html>