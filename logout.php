<?php
session_start();
echo 'Session started<br>'; // Debugging
session_unset(); 
echo 'Session unset<br>'; // Debugging
session_destroy(); 
echo 'Session destroyed<br>'; // Debugging
header("Location: volunteer.php"); 
exit();
?>
