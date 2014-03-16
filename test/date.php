<?php
date_default_timezone_set('UTC');
$CreateDate = date_create()->format('Y-m-d H:i:s'); // SQL NOW()
?>
<pre>
<?php echo "timezone: UTC time: " . $CreateDate; ?>
</pre>

<?php
date_default_timezone_set('PRC');
$CreateDate = date_create()->format('Y-m-d H:i:s'); // SQL NOW()
?>
<pre>
<?php echo "timezone: PRC time: " . $CreateDate; ?>
</pre>