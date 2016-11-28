<?php
require("pdo_database.class.php");

$db = new wArLeY_DBMS("mysql", "127.0.0.1", "test", "root", "", "");
$dbCN = $db->Cnxn(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if($dbCN==false) die("Error: Cant connect to database.");
echo $db->getError(); //Show error description if exist, else is empty.

$db->query("UPDATE TB_USERS SET NAME='wArLeY996',COMPANY='Freelancer MX' WHERE ID=1;");
$getAffectedRows = $db->update("TB_USERS", "NAME='Bling_Grillz',COMPANY='Freelancer MX'", "ID=2");
$db->disconnect();
?>