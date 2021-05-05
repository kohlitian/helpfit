<?php

//config access to localhost
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "HelpFit";

//config access to database
$servername = "remotemysql.com";
$username = "KJi4TiIoYF";
$password = "viUjf6BqCu";
$dbname = "KJi4TiIoYF";

//connect to database
$connect = new mysqli($servername, $username, $password, $dbname);

//start php session access
session_start();

//set timezone of website
date_default_timezone_set('Asia/Kuala_Lumpur');

//limit how many session records to show per page
$limit=10;

?>