<?php
//Start session
session_start();

//Unset the variables stored in session	
unset($_SESSION['SESS_USER_ID']);
unset($_SESSION['SESS_SUPERMARKET_ID']);
unset($_SESSION['SESS_ACCESS_LEVEL']);
unset($_SESSION['SESS_USER']);

header("location: login.php");
?>
