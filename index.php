<?php
require("pdo_database.class.php");

$db = new wArLeY_DBMS("oracle", "localhost", "orcl", "hr", "password", "");
$dbCN = $db->Cnxn(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if($dbCN==false) die("Error: Cant connect to database.");
echo $db->getError(); //Show error description if exist, else is empty.

$rs = $db->query("SELECT title FROM posts");
foreach($rs as $row){
	$tmp_name = $row["title"];
	echo "title = $tmp_name<br/>";
}

$db->disconnect();
?>