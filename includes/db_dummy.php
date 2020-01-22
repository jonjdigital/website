<?php

/**
 * Database connections. Comment out the connection string that isn't applicable to environment. local = dev, web = public
 */
$loc_usr_name = "";
$web_usr_name = "";

$user_db_name = "";
$web_db_name = "";

$loc_password = "";
$web_password = "";

$port = "3306";
$host = "localhost";

##user db connections##
#local
$user_con = mysqli_connect($host,$loc_usr_name,$loc_password,$user_db_name,$port);
#live
#$user_con = mysqli_connect($host,$web_usr_name,$web_password,$user_db_name,$port);

##website db connections##
#local
$web_con = mysqli_connect($host,$loc_usr_name,$loc_password,$web_db_name,$port);
#live
#$web_con = mysqli_connect($host,$web_usr_name,$web_password,$web_db_name,$port);