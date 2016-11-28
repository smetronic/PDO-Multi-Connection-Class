<?php
require("pdo_database.class.php");

$db = new wArLeY_DBMS("mysql", "127.0.0.1", "pdo", "root", "", "");
$dbCN = $db->Cnxn(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if($dbCN==false) die("Error: Cant connect to database.");
echo $db->getError(); //Show error description if exist, else is empty.

$result = $db->query("INSERT INTO TB_USERS (NAME, ADDRESS, COMPANY) VALUES ('Evert Ulises German', 'Internet #996 Culiacan Sinaloa', 'Freelancer');");
$result = $db->insert("TB_USERS", "NAME='Yusef German',ADDRESS='Tetameche #3035 Culiacan Sin. Mexico',COMPANY='Aluminium'");
$db->disconnect();
?>