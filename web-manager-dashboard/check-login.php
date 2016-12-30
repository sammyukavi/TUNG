<?php

//Start session
session_start();

//Include database connection details
require_once('config.php');

//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;

//Connect to mysql server
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$link) {
    die('Failed to connect to server: ' . mysql_error());
}

//Select database
$db = mysql_select_db(DB_DATABASE);
if (!$db) {
    die("Unable to select database");
}

//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
    $str = @trim($str);
    if (get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysql_real_escape_string($str);
}

//Sanitize the POST values
$login = clean($_POST['login']);
$password = clean($_POST['password']);

//Input Validations
if ($login == '') {
    $errmsg_arr[] = 'Login ID missing';
    $errflag = true;
}
if ($password == '') {
    $errmsg_arr[] = 'Password missing';
    $errflag = true;
}

//If there are input validations, redirect back to the login form
if ($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
    header("location: login.php");
    exit();
}

//Create query
$qry="SELECT * FROM users WHERE username='$login' AND password='".md5($_POST['password'])."'";
//$qry = "SELECT * FROM users WHERE username='$login' AND password='" . $_POST['password'] . "'";
$result = mysql_query($qry);

//Check whether the query was successful or not
if ($result) {
    if (mysql_num_rows($result) == 1) {
        //Login Successful
        session_regenerate_id();
        $member = mysql_fetch_assoc($result);
        $sql2 = 'SELECT supermarketName FROM  supermarkets WHERE supermarketId=' . $member['supermarketId'];
        $result2 = mysql_query($sql2);
        $member2 = mysql_fetch_assoc($result2);
        $_SESSION['SESS_USER_ID'] = $member['userId'];
        $_SESSION['SESS_SUPERMARKET_ID'] = $member['supermarketId'];
        $_SESSION['SESS_SUPERMARKET_NAME'] = $member2['supermarketName'];
        $_SESSION['SESS_ACCESS_LEVEL'] = $member['accessLevel'];
        $_SESSION['SESS_USER'] = $member['username'];
        session_write_close();
        header("location: /index.php");
        exit();
    } else {
        //Login failed
        header("location: login.php");
        exit();
    }
} else {
    die("Query failed");
}
?>