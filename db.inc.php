<?php
$mysql_host = 'localhost';
$mysql_user = 'dragitinuser76';
$mysql_pass = 'RBBCPVSxwpVPmD4C';
$mysql_db = 'dragitin_db';
$connection = mysql_connect($mysql_host, $mysql_user, $mysql_pass) or die ("<p class='error'>Sorry, we were unable to connect to the database server.</p>");  
mysql_select_db($mysql_db, $connection) or die ("<p class='error'>Sorry, we were unable to connect to the database.</p>");
?>