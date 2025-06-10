<?php
echo "ZYMA SIMPLE PHP TEST<br>";
echo "Time: " . date('Y-m-d H:i:s') . "<br>";
echo "PHP: " . PHP_VERSION . "<br>";
echo "Working Directory: " . getcwd() . "<br>";
echo "File exists index.php: " . (file_exists('index.php') ? 'YES' : 'NO') . "<br>";
echo "Status: OK";
?> 