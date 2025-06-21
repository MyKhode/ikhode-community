<?php
$file = '/var/www/storage/logs/testfile.txt';
file_put_contents($file, "This is a test.");
echo "File written: " . $file;
?>
