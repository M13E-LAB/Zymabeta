<?php
echo "🎉 SERVER WORKS! " . date('Y-m-d H:i:s');
echo "<br>";
echo "PHP Version: " . PHP_VERSION;
echo "<br>";
echo "Current Directory: " . getcwd();
echo "<br>";
echo "Files in public: ";
print_r(scandir('.'));
?> 