<?php
/**
******************************************************
* @file pdo_database.class_manual.php
* @brief PDO Database Class Manual: This is a guide for learn to use pdo for connect easily to multiple databases types.
* INTRODUCTION:
* Why you Should be using PHP's PDO for Database Access...
* PDO – PHP Data Objects, is a database access layer providing a uniform method of access to multiple databases.
* It doesn’t account for database-specific syntax, but can allow for the process of switching databases and platforms to be fairly painless, simply by switching the connection string in many instances.
*
* DATABASES SUPPORT:
* You need use this shortcuts for the database type:
* sqlite2	-> SQLite2 - TESTED
* sqlite3	-> SQLite3
* sqlsrv 	-> Microsoft SQL Server (Works under Windows, accept all SQL Server versions [max version 2008]) - TESTED
* mssql 	-> Microsoft SQL Server (Works under Windows and Linux, but just work with SQL Server 2000) - TESTED
* mysql 	-> MySQL - TESTED
* pg 		-> PostgreSQL - TESTED
* ibm		-> IBM
* dblib		-> DBLIB
* odbc		-> Microsoft Access
* oracle	-> ORACLE
* ifmx 		-> Informix
* fbd		-> Firebird - TESTED
*
* CHANGELOG:
* v2.2: Added doxygen documentation for friendly use in the class file.
* v2.1: Added transactional method, now you can feel the power and care for the integrity of your database with transactions.
* v2.0: Optimized all class code, added unnamed placeholder option in query_secure(), added method properties() for get information about server and connection. Manual updated for provide more clearly examples.
* v1.9: Added method for secure querys and avoid SQL Injections.
* v1.8: Optimized methods update, delete and getLatestId. Methods update and delete allow empty conditions for several changes.
* v1.7: Optimized method rowcount(), now build automatic query for count(*).
* v1.6: Fix the error handler in the connection database, modified the constructor of the class. (Critical)
* v1.5: Added 2 methods: 1.- ShowDBS and 2.- ShowTables, return databases existing on host, return all tables of database relatively.
* v1.4: Added method getError(), this return error description if exist.
* v1.3: Fix the "insert" operation works in any database.
* v1.2: Added method getLatestId(table, id), return the latest id (primary key autoincrement).
* v1.1: After insert, delete or update operations, the result is the affected rows.
* v1.0: First version working.
* @author Evert Ulises German Soto
* @version 2.2
* @date July 2012
*******************************************************/

/**
******************************************************
* @brief How To Connect To Database.
*******************************************************/
///1.- You need include the class file.
require("pdo_database.class.php");

///2.- Instantiate the class with the server parameters.
# object = new wArLeY_DBMS(shortcut_database_type, server, database_name, user, password, port);
$db = new wArLeY_DBMS("mysql", "127.0.0.1", "test", "root", "", "");

///3.- Connect to database.
$dbCN = $db->Cnxn(); //This step is really neccesary for create connection to database, and getting the errors in methods.

///4.- Check if connection are succesful or return error.
if($dbCN==false) die("Error: Cant connect to database.");

///5.- If connection fail you can print the error... Note: Every operation you execute can try print this line, for get the latest error ocurred.
echo $db->getError(); //Show error description if exist, else is empty.

///Extras: Information about server and connection only execute this:
$db->properties();

/**
******************************************************
* @brief How To Create Tables.
*******************************************************/
$db->query('DROP TABLE IF EXISTS TB_USERS;'); //drop table if exist
# Instruction SQL in variable
$query_create_table = <<< EOD
CREATE TABLE TB_USERS (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  NAME VARCHAR(100) NOT NULL,
  ADDRESS VARCHAR(100) NOT NULL,
  COMPANY VARCHAR(100) NOT NULL
);
EOD;
///Execute the create table statement
$db->query($query_create_table);
///Execute alter table statement
$db->query("ALTER TABLE TB_USERS ADD SEX CHAR(1);");

/**
******************************************************
* @brief How To Insert Rows.
*******************************************************/
///Option 1:
$result = $db->query("INSERT INTO TB_USERS (NAME, ADDRESS, COMPANY) VALUES ('Evert Ulises German', 'Internet #996 Culiacan Sinaloa', 'Freelancer');");
# $result false if operation fail.

///Option 2: Method insert(table_name, data_to_insert[field=data]);
$result = $db->insert("TB_USERS", "NAME='Evert Ulises German',ADDRESS='Tetameche #3035 Culiacan Sin. Mexico',COMPANY='Freelancer'");
# $result have the inserted id or false if operation fail. IMPORTANT: For getting the currently id inserted is neccessary define the id field how primary key autoincrement.

/**
******************************************************
* @brief How To Update Rows.
*******************************************************/
///Option 1:
$db->query("UPDATE TB_USERS SET NAME='wArLeY996',COMPANY='Freelancer MX' WHERE ID=1;");
///Option 2: Method update(table_name, set_new_data[field=data], condition_if_need_but_not_required);
$getAffectedRows = $db->update("TB_USERS", "NAME='wArLeY996',COMPANY='Freelancer MX'", "ID=1"); //With Condition
$getAffectedRows = $db->update("TB_USERS", "NAME='wArLeY996',COMPANY='Freelancer MX'"); //Without Condition (must be careful!)

/**
******************************************************
* @brief How To Delete Rows.
*******************************************************/
///Option 1:
$result = $db->query("DELETE FROM TB_USERS WHERE ID=1;");
# $result false if operation fail.
///Option 2: Method delete(table_name, condition_if_need_but_not_required);
$getAffectedRows = $db->delete("TB_USERS", "ID=1"); //With Condition
$getAffectedRows = $db->delete("TB_USERS"); //Without Condition (must be careful!)

/**
******************************************************
* @brief How To Retrieve Result Set.
*******************************************************/
$rs = $db->query("SELECT NAME,ADDRESS FROM TB_USERS");
foreach($rs as $row){
	$tmp_name = $row["NAME"];
	$tmp_address = $row["ADDRESS"];
	echo "The user $tmp_name lives in: $tmp_address<br/>";
}

/**
******************************************************
* @brief How To Get The Total Rows.
*******************************************************/
# Once that you have execute any query, you can get total rows.
echo "Total rows: " . $db->rowcount() . "<br/>";

/**
******************************************************
* @brief How To Get The Latest Id.
*******************************************************/
# getLatestId(table_name, field_id);
$latestInserted = $db->getLatestId("TB_USERS","ID");
//IMPORTANT: For getting the latest id inserted is neccessary define the id column how autoincrement.

/**
******************************************************
* @brief How To Disconnect Database.
*******************************************************/
$db->disconnect();

/**
******************************************************
* @brief How To Implement Transactions.
*******************************************************/
$db->transaction("B"); //Begin the Transaction
$db->delete("TB_USERS", "ID=1");
$db->delete("TB_USERS", "ID=2");
$db->transaction("C"); //Commit and apply changes
$db->transaction("R"); //Or you can Rollback and undo changes like Ctrl+Z

/**
* ------------------------------------------ SECURE METHODS PREVENT AND AVOID SQL INJECTIONS ---------------------------------------------------
* METHOD: query_secure, "first_param": query statement, "second_param": array with params, "third_param": if you specify true, you can get the recordset, else you get true, "fourth_param": unnamed or named placeholders is your choice, "fifth_param": for change your delimiter.
* Note: the third_param, fourth_param and fifth_param not are required, have a default values: false, false, "|" relatively.
* IMPORTANT: the delimiter default is "|" (pipe), is neccessary change this delimiter if exist in your data.
* ----------------------------------------------------------------------------------------------------------------------------------------------*/
/**
******************************************************
* @brief How To Retrieve Result Set.
*******************************************************/
///Option 1: SELECT Statement With "NAMED PLACEHOLDERS":
$params = array(":id|2|INT");
$rows = $db->query_secure("SELECT NAME FROM TB_USERS WHERE ID=:id;", $params, true, false);
if($rows!=false){
	foreach($rows as $row){
		echo "User: ". $row["NAME"] ."<br />";
	}
}
$rows = null;
///Option 2: SELECT Statement With "UNNAMED PLACEHOLDERS":
$params = array(2);
$rows = $db->query_secure("SELECT NAME FROM TB_USERS WHERE ID=?;", $params, true, true);
if($rows!=false){
	foreach($rows as $row){
		echo "User: ". $row["NAME"] ."<br />";
	}
}
$rows = null;

/**
******************************************************
* @brief How To Insert Rows.
*******************************************************/
///Option 1: INSERT Row With "NAMED PLACEHOLDERS":
$params = array(":id|2|INT", ":name|Amy Julyssa German|STR", ":address|Internet #996 Culiacan Sinaloa|STR", ":company|Nothing|STR");
$result = $db->query_secure("INSERT INTO TB_USERS (ID,NAME,ADDRESS,COMPANY) VALUES(:id,:name,:address,:company);", $params, false, false);
///Option 2: INSERT Row With "UNNAMED PLACEHOLDERS":
$params = array(2, "Amy Julyssa German", "Internet #996 Culiacan Sinaloa", "Nothing");
$result = $db->query_secure("INSERT INTO TB_USERS (ID,NAME,ADDRESS,COMPANY) VALUES(?,?,?,?);", $params, false, true);

/**
******************************************************
* @brief How To Update Rows.
*******************************************************/
///Option 1: UPDATE Rows With "NAMED PLACEHOLDERS":
$params = array(":id|2|INT", ":address|Internet #996 Culiacan, Sinaloa, Mexico|STR", ":company|Nothing!|STR");
$result = $db->query_secure("UPDATE TB_USERS SET ADDRESS=:address,COMPANY=:company WHERE ID=:id;", $params, false, false);
///Option 2: UPDATE Rows With "UNNAMED PLACEHOLDERS":
$params = array("Internet #996 Culiacan, Sinaloa, Mexico", "Nothing!", 2);
$result = $db->query_secure("UPDATE TB_USERS SET ADDRESS=?,COMPANY=? WHERE ID=?;", $params, false, true);

/**
******************************************************
* @brief How To Delete Rows.
*******************************************************/
///Option 1: DELETE Rows With "NAMED PLACEHOLDERS":
$params = array(":id|2|INT");
$result = $db->query_secure("DELETE FROM TB_USERS WHERE ID=:id;", $params, false, false);
///Option 2: DELETE Rows With "UNNAMED PLACEHOLDERS":
$params = array(2);
$result = $db->query_secure("DELETE FROM TB_USERS WHERE ID=?;", $params, false, true);

///IMPORTANT: UPDATE and DELETE works fine but not return the affected rows, just return false if fails.
echo "AFFECTEDS -> " . (($result===false) ? "NO... ".$db->getError() : "YES") . "<br />";

/**
******************************************************
* @brief How To Get All Databases.
*******************************************************/
$rs = $db->ShowDBS();  //Depending of your database type you can get results
foreach($rs as $row){
	$tmp_table = $row[0];
	echo "Database named: $tmp_table<br/>";
}

/**
******************************************************
* @brief How To Get All Tables From Database.
*******************************************************/
$rs = $db->ShowTables("test");  //Depending of your database type you can specify the database
foreach($rs as $row){
	$tmp_table = $row[0];
	echo "The table from database is: $tmp_table<br/>";
}

/**
******************************************************
* @brief How To Get Columns Name From Table.
*******************************************************/
$column_array = $db->columns("TB_USERS");
if($column_array!=false){
	foreach($column_array as $column){
		echo "$column<br/>";
	}
}else{
	echo $db->getError();
}

/**
******************************************************
* @brief How To Install Library "mssql" In Windows.
*******************************************************/
# If "php_pdo_mssql" not is running... 
# Open path: "C:/xampp/php/ext/"
# Rename the file: php_pdo_mssql.dll to php_pdo_mssql_new.dll 
# Open php.ini file, add extension:
# extension=php_pdo_mssql_new.dll
# Copy dll file "ntwdblib.dll" in the extensions path with the same name: "php/ext/" and too in path "C:/windows/system32/"
# Restart webserver and try your connection

/**
******************************************************
* @brief How To Install Library "sqlsrv" In Windows.
*******************************************************/
# If "php_pdo_sqlsrv" not is running... 
# Open php.ini file, add extension:
# extension=php_pdo_sqlsrv_53_ts_vc6.dll
# Copy dll file with the same name in the path: "php/ext/"
# Restart webserver and try your connection
?>