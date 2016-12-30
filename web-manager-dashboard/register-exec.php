<?php

////Start session
session_start();

//Include database connection details
require_once('config.php');
require('includes/custom-add-user.php');
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
$username = clean($_POST['username']);
$supermarketId = clean($_POST['supermarketId']);
$accessLevel = clean($_POST['accessLevel']);
$password = clean($_POST['password']);
$cpassword = clean($_POST['cpassword']);

//Input Validations
if (empty($username)) {
    $errmsg_arr[] = 'A username is required';
    $errflag = true;
	}
if ($supermarketId == '') {
    $errmsg_arr[] = 'Select supermarket to assign';
    $errflag = true;
}
if ($accessLevel == '') {
    $errmsg_arr[] = 'Select the Access level';
    $errflag = true;
}
if ($password == '') {
    $errmsg_arr[] = 'Password is missing';
    $errflag = true;
}
if ($cpassword == '') {
    $errmsg_arr[] = 'Confirm password missing';
    $errflag = true;
}
if (strcmp($password, $cpassword) != 0) {
    $errmsg_arr[] = 'Passwords do not match';
    $errflag = true;
}

//Check for duplicate username ID
if ($username != '') {
    $qry = "SELECT * FROM users WHERE username='$username'";
    $result = mysql_query($qry);
    if ($result) {
        if (mysql_num_rows($result) > 0) {
            $errmsg_arr[] = 'That Username is already in use';
            $errflag = true;
        }
        @mysql_free_result($result);
    } else {
        die("Query failed");
    }
}

//If there are input validations, redirect back to the registration form
if ($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
    header("location: add-user.php");
    exit();
}

//Create INSERT query
$qry = "INSERT INTO users(username, supermarketId, accessLevel, password) VALUES('$username','$supermarketId','$accessLevel','".md5($_POST['password'])."')";
//$qry = "INSERT INTO users(username, supermarketId, accessLevel, password) VALUES('$username','$supermarketId','$accessLevel','" . $_POST['password'] . "')";
$result = @mysql_query($qry);

//Check whether the query was successful or not
if ($result) {
   	$show = "null";
	echo '<div class="content"><div id="error">';
	echo 'Success! User was added<br/><a href="index.php"><input type="submit" name="continue" value="Continue" /></a>';
	echo '</div></div>';
    exit();
} else {
    echo '<div class="content"><div id="error">';
	echo 'Error. User was not added<br/><a href="index.php"><input type="submit" name="continue" value="Continue" /></a>';
	echo '</div></div>';
}
?>