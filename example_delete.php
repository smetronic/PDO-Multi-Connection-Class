<?php
require("pdo_database.class.php");

$db = new wArLeY_DBMS("mysql", "127.0.0.1", "test", "root", "", "");
$dbCN = $db->Cnxn(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if($dbCN==false) die("Error: Cant connect to database.");
echo $db->getError(); //Show error description if exist, else is empty.

$result = $db->query("DELETE FROM TB_USERS WHERE ID=2;");
$getAffectedRows = $db->delete("TB_USERS", "ID=1");
$db->disconnect();
?>