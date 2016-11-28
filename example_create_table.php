<?php
require("pdo_database.class.php");

$db = new wArLeY_DBMS("mysql", "localhost", "pdo", "root", "", "");
$dbCN = $db->Cnxn(); //This step is really neccesary for create connection to database, and getting the errors in methods.
if($dbCN==false) die("Error: Cant connect to database.");
echo $db->getError(); //Show error description if exist, else is empty.

$db->query('DROP TABLE IF EXISTS TB_USERS;'); //drop table if exist
$query_create_table = <<< EOD
CREATE TABLE TB_USERS (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  NAME VARCHAR(100) NOT NULL,
  ADDRESS VARCHAR(100) NOT NULL,
  COMPANY VARCHAR(100) NOT NULL,
  PRIMARY KEY(id)
);
EOD;

$db->query($query_create_table);
$db->query("ALTER TABLE TB_USERS ADD SEX CHAR(1);");
$db->disconnect();
?>