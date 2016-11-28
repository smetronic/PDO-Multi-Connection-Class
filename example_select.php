<?php
require("pdo_database.class.php");

$db = new wArLeY_DBMS("mysql", "127.0.0.1", "test", "root", "", "");
$dbCN = $db->Cnxn(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if($dbCN==false) die("Error: Cant connect to database.");
echo $db->getError(); //Show error description if exist, else is empty.

$rs = $db->query("SELECT NAME,ADDRESS FROM TB_USERS");
foreach($rs as $row){
	$tmp_name = $row["NAME"];
	$tmp_address = $row["ADDRESS"];
	echo "The user $tmp_name lives in: $tmp_address<br/>";
}

//But if you need retrieve rows in objects, not in array... you need specify like this...
$rs = $db->query("SELECT NAME,ADDRESS FROM TB_USERS");
$rs->setFetchMode(PDO::FETCH_OBJ); // <---- This is most important!
foreach($rs as $row){
	$tmp_name = $row->NAME;
	$tmp_address = $row->ADDRESS;
	echo "The user $tmp_name lives in: $tmp_address<br/>";
}
$rs = null;
$db->disconnect();
?>