<?php
require_once('auth.php');
require('connect.php');
$accessLevel = $_SESSION['SESS_ACCESS_LEVEL'];
require('includes/custom.php');
$supermarketId = $_SESSION['SESS_SUPERMARKET_ID'];
$userId = $_SESSION['SESS_USER_ID'];
$show = 'view';
date_default_timezone_set( "Africa/Nairobi" );

function clean($str) {
    $str = @trim($str);
    if (get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }
    return mysql_real_escape_string($str);
}

if (isset($_POST['submit'])) {
    $action = $_POST['action'];
    if ($action == 'addStock') {
        $stockName = $_POST['stockName'];
        $categoryId = $_POST['categoryId'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $sql = "INSERT INTO stocks (supermarketID,categoryId,stockName,quantity,price) VALUES ('$supermarketId','$categoryId','$stockName','$quantity','$price')";
        $ok = (mysql_query($sql) or die('Error: ' . mysql_error()));
    }
    if ($action == 'addCategory') {
        $categoryName = $_POST['categoryName'];
        $sql = "INSERT INTO categories (supermarketID,categoryName) VALUES ('$supermarketId','$categoryName')";
        $ok = (mysql_query($sql) or die('Error: ' . mysql_error()));
    }
    if ($action == 'updateStock') {
        $stockId = $_POST['stockId'];
        $stockName = $_POST['stockName'];
        $categoryId = $_POST['categoryId'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $sql = "UPDATE stocks SET categoryId='" . $categoryId . "',stockName='" . $stockName . "',quantity='" . $quantity . "',price='" . $price . "' WHERE stockId=" . $stockId . "";
        $ok = (mysql_query($sql) or die('Error: ' . mysql_error()));
    }
    if ($action == 'updateCategory') {
        $categoryId = $_POST['categoryId'];
        $categoryName = $_POST['categoryName'];
        $sql = "UPDATE categories SET categoryName='" . $categoryName . "' WHERE categoryId=" . $categoryId . "";
        $ok = (mysql_query($sql) or die('<div class="content"><div id="error">' . mysql_error()));
    }

    if ($action == 'updateUser') {

        $password = clean($_POST['password']);
        $cpassword = clean($_POST['cpassword']);
        $userId = clean($_POST['userId']);		
		
				
        $errmsg_arr = array();

        //Validation error flag
        $errflag = false;
        if ($accessLevel == 1) {
		
            $username = clean($_POST['username']);
			$userLevel = clean($_POST['userLevel']);
			$supId = clean($_POST['supId']);
            if ($username=="") {
                $errmsg_arr[] = 'A username is required';
                $errflag = true;
            }
            if ($supermarketId="") {
                $errmsg_arr[] = 'Select supermarket to assign';
                $errflag = true;
            }
			
            if ($userLevel=="") {
                $errmsg_arr[] = 'Select the Access level';
                $errflag = true;
            }
			
			
        }
		
        if (empty($password)) {
            $errmsg_arr[] = 'Password is missing';
            $errflag = true;
        }
        if (empty($cpassword)) {
            $errmsg_arr[] = 'Confirm password missing';
            $errflag = true;
        }

        if (strcmp($password, $cpassword) != 0) {
            $errmsg_arr[] = 'Passwords do not match';
            $errflag = true;
        }

        //If there are input validations, redirect back to the registration form
        if ($errflag) {
            $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
            $show = "null";
            echo '<div class="content"><div id="error">';
            if (isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0) {
                echo '<ul class="err">';
                foreach ($_SESSION['ERRMSG_ARR'] as $msg) {
                    echo '<li>', $msg, '</li>';
                }
                echo '</ul>';
                
            }
            echo '</div>';
            ?>
                <fieldset>
                    <form method="post" action='<?php echo $_SERVER['PHP_SELF'] ?>'>
                        <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
            <?php
            $sql = "SELECT * FROM users WHERE userId = " . $userId;
            $result = mysql_query($sql);
            while ($row = mysql_fetch_array($result)) {
                if ($accessLevel == 1) {
                    echo '<tr><th>Username </th><td><input name="username" type="text" class="textfield" id="username" value="' . $row['username'] . '"/></td></tr>';
                    echo '<tr><th>Supermarket </th><td><select name="supId" class="textfield" id="supId">
                                                <option value="">Select Supermarket</option>
                                                <option value="1">Tuskys</option>
                                                <option value="2">Uchumi</option>
                                                <option value="3">Nakumatt</option>
                                                <option value="4">Game</option>
                                            </select></td>
                                    </tr>';
								echo '<tr><th>Access Level </th><td><select name="userLevel" class="textfield" id="userLevel">
                                                <option value="">Select Access Level</option>
                                                <option value="0">User</option>
                                                <option value="1">Administrator</option>
                                            </select></td>
                                    </tr>';
						echo '
                                    <tr>
                                        <th>Enter new password</th>
                                        <td><input name="password" type="password" class="textfield" id="password" /></td>
                                    </tr>
                                    <tr>
                                        <th>Confirm new password </th>
                                        <td><input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
                                    </tr>';
                    echo '<tr><td><input type="hidden" name="userId" value="' . $row['userId'] . '" />';
                } else
                if ($accessLevel == 0) {
                    echo '
									<tr>
                                        <th>Enter old password</th>
                                        <td><input name="oldPassword" type="password" class="textfield" id="oldPassword" /></td>
                                    </tr>
                                    <tr>
                                        <th>Enter new password</th>
                                        <td><input name="password" type="password" class="textfield" id="password" /></td>
                                    </tr>
                                    <tr>
                                        <th>Confirm new password </th>
                                        <td><input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
                                    </tr>';
                    echo '<tr><td><input type="hidden" name="userId" value="' . $row['userId'] . '" />';
                }
            }
            ?>
                            <tr>
                            <tr><td><input type="hidden" name="action" value="updateUser" /><a href="index.php"><input type="submit" value="Cancel" /></a></td>
                                <td><input type="submit" name = "submit" value="Save"/></td></tr>
                            </tr>

                        </table>
                    </form>
                </fieldset>
            </div>
            <?php
        } else

        if ($accessLevel == 0) {
            $oldPassword = clean($_POST['oldPassword']);
            $sql = "SELECT password FROM users WHERE userId=" . $userId;
            $result = mysql_query($sql);
            while ($row = mysql_fetch_array($result)) {
                if (md5($oldPassword) == $row['password']) {
                    $sql = "UPDATE users SET password='" . md5($password) . "' WHERE userId=" . $userId . "";
                    $ok = (mysql_query($sql) or die('Error: ' . mysql_error()));
					$show = "null";
				echo '<div class="content"><div id="error">';
				echo 'Password change successful<br/><a href="index.php"><input type="submit" name="continue" value="Continue" /></a>';
				echo '</div></div>';
                }else
				{				
				$errmsg_arr[] = 'Wrong old password';
				$errflag = true;
				$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
				$show = "null";
				echo '<div class="content"><div id="error">';
				if (isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0) {
                echo '<ul class="err">';
                foreach ($_SESSION['ERRMSG_ARR'] as $msg) {
                    echo '<li>', $msg, '</li>';
                }
                echo '</ul>';
				echo '<br/><a href="index.php"><input type="submit" name="continue" value="Ok" /></a>';
                unset($_SESSION['ERRMSG_ARR']);
            }
            echo '</div></div>';
			}
        
            }
        } else
        if ($accessLevel == 1) {
            $supId = clean($_POST['supId']);
            $userLevel = clean($_POST['userLevel']);
			$username = clean($_POST['username']);
            $sql = "UPDATE users SET username='".$username."',supermarketId='" . $supId . "',accessLevel='" . $userLevel . "',password='" . md5($password). "' WHERE userId=" . $userId . "";
            $result = mysql_query($sql);
			if ($result) {
			$show = "null";
			echo '<div class="content"><div id="error">';
				echo 'Update was successful<br/><a href="index.php"><input type="submit" name="continue" value="Continue" /></a>';
            echo '</div></div>';
} else {
$show = "null";
    echo '<div class="content"><div id="error">';
	echo 'Error: update was not successful'.mysql_error().'<br/><a href="index.php"><input type="submit" name="continue" value="Continue" /></a>';
		
            echo '</div></div>';
} }
    }

    if ($action == 'deleteCategory') {
        $categoryId = $_POST['categoryId'];
        $sql = "DELETE FROM categories WHERE categoryId=" . $categoryId . "";
        mysql_query($sql);
        $sql2 = "DELETE FROM stocks WHERE categoryId=" . $categoryId . "";
        mysql_query($sql2);
    }
    if ($action == 'deleteStock') {
        $stockId = $_POST['stockId'];
        $sql2 = "DELETE FROM stocks WHERE stockId=" . $stockId . "";
        mysql_query($sql2);
    }
	
	 if ($action == 'deleteUser') {
        $userId = $_POST['userId'];
        $sql2 = "DELETE FROM users WHERE userId=" . $userId . "";
        mysql_query($sql2);
    }
	
    if ($action == 'addUser') {
        //Array to store validation errors
        $errmsg_arr = array();

        //Validation error flag
        $errflag = false;

        //Connect to mysql server
        $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
        if (!$link) {
            die('Failed to connect to server: ' . mysql_error());
        }

        //Sanitize the POST values
        $username = clean($_POST['username']);
        $supermarketId = clean($_POST['supermarketId']);
        $userLevel = clean($_POST['userLevel']);
        $password = clean($_POST['password']);
        $cpassword = clean($_POST['cpassword']);


        if (empty($username)) {
            $errmsg_arr[] = 'A username is required';
            $errflag = true;
        }
		
       
		
        if (empty($userLevel)) {
            $errmsg_arr[] = 'Select the Access level';
            $errflag = true;
        }
		
        if (empty($password)) {
            $errmsg_arr[] = 'Password is missing';
            $errflag = true;
        }
        if (empty($cpassword)) {
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
            $show = "null";
            echo '<div class="content"><div id="error">';
            if (isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0) {
                echo '<ul class="err">';
                foreach ($_SESSION['ERRMSG_ARR'] as $msg) {
                    echo '<li>', $msg, '</li>';
                }
                echo '</ul>';
                unset($_SESSION['ERRMSG_ARR']);
            }
            echo '</div>';
			
            ?>	
            <fieldset>
                <form method="post" action='<?php echo $_SERVER['PHP_SELF'] ?>'>
                    <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
                        <tr>
                            <th>Username </th>
                            <td><input name="username" type="text" class="textfield" id="username" /></td>
                        </tr>
                        <tr>
                            <th>Supermarket </th>
                            <td><select name="supId" class="textfield" id="supId">
                                    <option value="">-Select Supermarket-</option>
                                    <option value="1">Tuskys</option>
                                    <option value="2">Uchumi</option>
                                    <option value="3">Nakumatt</option>
                                    <option value="4">Game</option>
                                </select></td>
                        </tr>
                        <tr><th>Access Level </th><td><select name="userLevel" class="textfield" id="userLevel">
                                                <option value="">Select Access Level</option>
                                                <option value="0">User</option>
                                                <option value="1">Administrator</option>
                                            </select></td>
                                    </tr>
                        <tr>
                            <th>Password</th>
                            <td><input name="password" type="password" class="textfield" id="password" /></td>
                        </tr>
                        <tr>
                            <th>Confirm Password </th>
                            <td><input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
                        </tr>
                        <tr>
                        <tr><td><input type="hidden" name="action" value="addUser" /><a href="index.php"><input type="submit" value="Cancel" /></a></td>
                            <td><input type="submit" name = "submit" value="Save"/></td></tr>
                        </tr>
                    </table>
                </form>
            </fieldset>					
            <?php
            echo '</div>';
        }
		else
		{
			$qry = "INSERT INTO users(username, supermarketId, accessLevel, password) VALUES('$username','$supermarketId','$userLevel','".md5($_POST['password'])."')";
			//$sql = "INSERT INTO users(username, supermarketId, accessLevel, password) VALUES('$username','$supermarketId','$userLevel','" . $_POST['password'] . "')";
			$result = @mysql_query($sql);
			if ($result) {
				$show = "null";
				echo '<div class="content"><div id="error">';
				echo 'Usser added successfuly<br/><a href="index.php"><input type="submit" name="continue" value="Continue" /></a>';
				echo '</div></div>';
			} else {
				$show = "null";
				echo '<div class="content"><div id="error">';
				echo 'Error. User was not added<br/><a href="index.php"><input type="submit" name="continue" value="Continue" /></a>';
				echo '</div></div>';

			}
		}
    }
} else {
    if (isset($_GET['action']) && !empty($_GET['action'])) {
        if ($_GET['action'] == 'add') {
            if (isset($_GET['mode']) && !empty($_GET['mode'])) {
                if ($_GET['mode'] == 'stock') {
                    //Show form to add stock
                    $show = "null";
                    ?>
                    <div class="content">
                        <fieldset>
                            <fieldset>
                                <form method="post" action='<?php echo $_SERVER['PHP_SELF'] ?>' >
                                    <table align="center">
                                        <tr><td>Select Category</td>
                                            <td>
                    <?php
                    echo '<select name="categoryId">';
                    $result = mysql_query("SELECT * FROM categories WHERE supermarketID='" . $supermarketId . "'");
                    while ($row = mysql_fetch_array($result)) {
                        echo '<option value="' . $row['categoryId'] . '">' . $row['categoryName'] . '</option>';
                    }
                    echo '</select>';
                    ?>
                                            </td>
                                        </tr>
                                        <tr><td>Enter stock Name</td><td><input type="text" name="stockName" /></td></tr>
                                        <tr><td>Enter stock Quantity</td><td><input type="text" name="quantity" /></td></tr>
                                        <tr><td>Enter stock price per unit</td><td><input type="text" name="price" /></td></tr>
                                        <tr><td><input type="hidden" name="action" value="addStock" /><a href="index.php"><input type="submit" value="Cancel" /></a></td>
                                            <td><input type="submit" name = "submit" value="Save"/></td></tr>
                                    </table>
                                </form>
                            </fieldset>
                    </div>
                    <?php
                } else
                if ($_GET['mode'] == 'category') {
                    $show = "null";
                    //Show form to add stock category						
                    ?>
                    <div class="content">
                        <fieldset>
                            <form method="post" action='<?php echo $_SERVER['PHP_SELF'] ?>'>
                                <table align="center">
                                    <tr>
                                        <td>Enter new category name</td><td><input type="text" name="categoryName"/></td></tr>
                                    <tr><td><input type="hidden" name="action" value="addCategory" /><a href="index.php"><input type="submit" value="Cancel" /></a></td>
                                        <td><input type="submit" name = "submit" value="Save"/></td></tr>
                                </table>
                            </form>
                        </fieldset>
                    </div>
                    <?php
                } else
                if ($_GET['mode'] == 'users') {
                    $show = "null";
                    //Show form to add a system user
                    ?>
                    <div class="content">
                        <fieldset>
                            <form method="post" action='<?php echo $_SERVER['PHP_SELF'] ?>'>
                                <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
                                    <tr>
                                        <th>Username </th>
                                        <td><input name="username" type="text" class="textfield" id="username" /></td>
                                    </tr>
                                    <tr>
                                        <th>Supermarket </th>
                                        <td><select name="supId" class="textfield" id="supId">
                                                <option value="">---Select Supermarket</option>
                                                <option value="1">Tuskys</option>
                                                <option value="2">Uchumi</option>
                                                <option value="3">Nakumatt</option>
                                                <option value="4">Game</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <th width="124">Access Level</th>
                                        <td><select name="userLevel" class="textfield" id="userLevel">
                                                <option value="">---Select User Role</option>
                                                <option value="0" selected="selected">User</option>
                                                <option value="1">Administrator</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <th>Password</th>
                                        <td><input name="password" type="password" class="textfield" id="password" /></td>
                                    </tr>
                                    <tr>
                                        <th>Confirm Password </th>
                                        <td><input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
                                    </tr>
                                    <tr>
                                    <tr><td><input type="hidden" name="action" value="addUser" /><a href="index.php"><input type="submit" value="Cancel" /></a></td>
                                        <td><input type="submit" name = "submit" value="Save"/></td></tr>
                                    </tr>
                                </table>
                            </form>
                        </fieldset>
                    </div>
                    <?php
                }
            }
        } else
        if ($_GET['action'] == 'edit') {
            if (isset($_GET['mode']) && !empty($_GET['mode'])) {
                if ($_GET['mode'] == 'stock') {
                    //Show form to edit stock
                    $show = "null";
                    $stockId = $_GET['id'];
                    ?>
                    <div class="content">
                        <fieldset>
                            <form method="post" action='<?php echo $_SERVER['PHP_SELF'] ?>' >
                                <table align="center">
                                    <tr><td>Select Category</td>
                                        <td>
                    <?php
                    echo '<select name="categoryId">';
                    $result = mysql_query("SELECT * FROM categories WHERE supermarketID='" . $supermarketId . "'");
                    while ($row = mysql_fetch_array($result)) {
                        echo '<option value="' . $row['categoryId'] . '">' . $row['categoryName'] . '</option>';
                    }
                    echo '</select>';
                    ?>
                                        </td>
                                    </tr>
                                            <?php
                                            $sql = "SELECT * FROM stocks WHERE stockId = " . $stockId;
                                            $result = mysql_query($sql);
                                            while ($row = mysql_fetch_array($result)) {
                                                echo '<tr><td>Edit stock Name</td><td><input type="text" name="stockName" value="' . $row['stockName'] . '"/></td></tr>';
                                                echo '<tr><td>Edit stock Quantity</td><td><input type="text" name="quantity" value="' . $row['quantity'] . '"/></td></tr>';
                                                echo '<tr><td>Enter stock price per unit</td><td><input type="text" name="price" value="' . $row['price'] . '" /></td></tr>';
                                                echo '<tr><td><input type="hidden" name="stockId" value="' . $row['stockId'] . '" />';
                                            }
                                            ?>
                                    <tr><td><input type="hidden" name="action" value="updateStock" /><a href="index.php"><input type="submit" value="Cancel" /></a></td>
                                        <td><input type="submit" name = "submit" value="Save"/></td></tr>
                                </table>
                                </table>
                            </form>
                        </fieldset>
                    </div>
                                    <?php
                                }

                                if ($_GET['mode'] == 'category') {
                                    $show = "null";
                                    $categoryId = $_GET['id'];
                                    //Show form to edit category records					
                                    ?>
                    <div class="content">
                        <fieldset>
                            <form method="post" action='<?php echo $_SERVER['PHP_SELF'] ?>'>
                                <table align="center">
                                    <tr><th>Edit Category</th></tr>
                    <?php
                    $result = mysql_query("SELECT * FROM categories WHERE categoryId='" . $categoryId . "'");
                    while ($row = mysql_fetch_array($result)) {
                        echo '<tr><td>Edit Category Name</td><td><input type="text" name="categoryName" value="' . $row['categoryName'] . '"/></td></tr>';
                        echo '<input type="hidden" name="categoryId" value="' . $row['categoryId'] . '"/>';
                    }
                    ?>
                                    <tr><td><input type="hidden" name="action" value="updateCategory" /><a href="index.php"><input type="button" value="Cancel" /></a></td>
                                        <td><input type="submit" name = "submit" value="Save"/></td></tr>
                                </table>
                            </form>
                        </fieldset>
                    </div><?php
                }


                if ($_GET['mode'] == 'users') {
                    //Show form to edit  a user's account
                    $show = "null";
                    $userId = $_GET['id'];
                    ?>
                    <div class="content">
                        <fieldset>
                            <form method="post" action='<?php echo $_SERVER['PHP_SELF'] ?>'>
                                <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
                    <?php
                    $sql = "SELECT * FROM users WHERE userId = " . $userId;
                    $result = mysql_query($sql);
                    while ($row = mysql_fetch_array($result)) {
                        if ($accessLevel == 1) {
                            echo '<tr><th>Username </th><td><input name="username" type="text" class="textfield" id="username" value="' . $row['username'] . '"/></td></tr>';
                            echo '<tr><th>Supermarket </th><td><select name="supId" class="textfield" id="supId">
                                                <option value="">---Select Supermarket</option>
                                                <option value="1">Tuskys</option>
                                                <option value="2">Uchumi</option>
                                                <option value="3">Nakumatt</option>
                                                <option value="4">Game</option>
                                            </select></td>
                                    </tr>';
                            echo '<tr>
                                        <th width="124">Access Level</th>
                                        <td><select name="userLevel" class="textfield" id="userLevel">
                                                <option value="">---Select User Role</option>
                                                <option value="0" selected="selected">User</option>
                                                <option value="1">Administrator</option>
                                            </select></td>
                                    </tr>';
                            echo '
                                    <tr>
                                        <th>Enter new password</th>
                                        <td><input name="password" type="password" class="textfield" id="password" /></td>
                                    </tr>
                                    <tr>
                                        <th>Confirm new password </th>
                                        <td><input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
                                    </tr>';
                            echo '<tr><td><input type="hidden" name="userId" id="userId" value="' . $row['userId'] . '" /></td></tr>';
                        } else
                        if ($accessLevel == 0) {
                            echo '
									<tr>
                                        <th>Enter old password</th>
                                        <td><input name="oldPassword" type="password" class="textfield" id="oldPassword" /></td>
                                    </tr>
                                    <tr>
                                        <th>Enter new password</th>
                                        <td><input name="password" type="password" class="textfield" id="password" /></td>
                                    </tr>
                                    <tr>
                                        <th>Confirm new password </th>
                                        <td><input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
                                    </tr>';
                            echo '<tr><td><input type="hidden" name="userId" value="' . $row['userId'] . '" />';
                        }
                    }
                    ?>
                                    <tr>
                                    <tr><td><input type="hidden" name="action" value="updateUser" /><a href="index.php"><input type="submit" value="Cancel" /></a></td>
                                        <td><input type="submit" name = "submit" value="Save"/></td></tr>
                                    </tr>

                                </table>
                            </form>
                        </fieldset>
                    </div>
                                    <?php
                                }
                            }
                        }

                        if ($_GET['action'] == 'delete') {
                            if (isset($_GET['mode']) && !empty($_GET['mode'])) {
                                if ($_GET['mode'] == 'order') {
                                    $show = "null";
                                    //Show form to delete orders						
                                    ?>
							<div class="content">
                        You cant delete an order. You do not have sufficient rights. <br>
                        <a href="index.php"><input type="button" name="cancel" value="Back" />
                    </div>
                    <?php
                } 
                if ($_GET['mode'] == 'stock') {
                    //Show form to delete stock						
                    if (isset($_GET['id']) && !empty($_GET['id'])) {
                        $show = "null";
                        $stockId = $_GET['id'];
                        //Show form to edit stock records					
                        ?>
                        <div class="content">
                            <fieldset>
                                <form action="index.php" method="post">
                                    <table align="center">
                                        <tr><th>Delete Record?</th></tr>
                                        <tr></tr>
                        <?php
                        $sql = "SELECT * FROM stocks WHERE stockId = " . $stockId;
                        $result = mysql_query($sql);
                        while ($row = mysql_fetch_array($result)) {
                            echo '<tr><td>Stock Name</td><td><input type="text" name="stockName" disabled="disabled" value="' . $row['stockName'] . '"/></td></tr>';
                            echo '<tr><td>Stock Quantity</td><td><input type="text" name="quantity" disabled="disabled" value="' . $row['quantity'] . '"/></td></tr>';
                            echo '<tr><td>Stock price per unit</td><td><input type="text" name="price" disabled="disabled" value="' . $row['price'] . '" /></td></tr>';
                            echo '<tr><td><input type="hidden" name="stockId" value="' . $row['stockId'] . '" />';
                        }
                        ?>
                                        <tr><td><input type="hidden" name="action" value="deleteStock" /><a href="index.php"><input type="submit" value="Cancel" /></a></td>
                                            <td><input type="submit" name = "submit" value="Delete"/></td></tr>
                                    </table>
                                </form>
                            </fieldset>
                        </div>
                                        <?php
                                    }
                                }
								
                                if ($_GET['mode'] == 'category') {
                                    //Show form to delete stock						
                                    if (isset($_GET['id']) && !empty($_GET['id'])) {
                                        $show = "null";
                                        $categoryId = $_GET['id'];
                                        //Show form to edit stock records					
                                        ?>
                        <div class="content">
                            <fieldset>
                                <form action="index.php" method="post">
                                    <table align="center">
                                        <tr><th>Delete Record?</th></tr>
                                        <tr></tr>
                        <?php
                        $sql = "SELECT * FROM categories WHERE categoryId = " . $categoryId;
                        $result = mysql_query($sql);
                        while ($row = mysql_fetch_array($result)) {
                            echo '<tr><td>Category Name</td><td><input type="text" name="categoryId" disabled="disabled" value="' . $row['categoryName'] . '"/></td></tr>';
                            echo '<input type="hidden" name="categoryId" value="' . $row['categoryId'] . '"/>';
                        }
                        ?>
                                        <tr><td><input type="hidden" name="action" value="deleteCategory" /><a href="index.php"><input type="submit" value="Cancel" /></a></td>
                                            <td><input type="submit" name = "submit" value="Delete"/></td></tr>
                                    </table>
                                </form>
                            </fieldset>
                        </div>
                                        <?php
                                    }
                                }
								
								if ($_GET['mode'] == 'users') {
								                        if ($accessLevel == 1) {
                                    //Show form to delete users					
                                    if (isset($_GET['id']) && !empty($_GET['id'])) {
                                        $show = "null";
                                        $userId = $_GET['id'];
                                        //Show form to edit stock records					
                                        ?>
                        <div class="content">
                            <fieldset>
                                <form action="index.php" method="post">
                                <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
									<tr><th>&nbsp;</th><th>Delete this record?</th></tr>
									<tr><th>&nbsp;</th><th>&nbsp;</th></tr>
                                        <?php $sql = "SELECT * FROM users WHERE userId = " . $userId;
										$result = mysql_query($sql);
										while ($row = mysql_fetch_array($result)) {

                            echo '<tr><th>Username </th><td><input disabled="disabled" name="username" type="text" class="textfield" id="username" value="' . $row['username'] . '"/></td></tr>';
							echo '<tr><th>&nbsp;</th><td><input name="userId" type="hidden" class="textfield" id="userId" value="' . $row['userId'] . '"/></td></tr>';
                         }
                    ?>
				
                                    <tr>
                                    <tr><td><input type="hidden" name="action" value="deleteUser" /><a href="index.php"><input type="submit" value="Cancel" /></a></td>
                                        <td><input type="submit" name = "submit" value="Delete"/></td></tr>
                                    </tr>

                                </table>
                            </form>
                        </fieldset>
                        </div>
                                        <?php
                                    }
                                }
								
								
                            }
							}
                        }
                    }
                }
                if ($show == "view") {
                    ?>
    <div class="content">
        <div class="TabView" id="TabView">
            <!-- ***** Tabs ************************************************************ -->

            <div class="Tabs" style="width: 452px;">
                <a class="Current">Orders</a>
                <a>Stock</a>
                <a>Stock Category</a>
    <?php
    if ($accessLevel == 1) {
        echo '<a>User Accounts</a>';
    } else {
        echo '<a>My Account</a>';
    }
    ?>
            </div>
            <!-- ***** Pages *********************************************************** -->
            <div class="Pages" style="width: 999px; height: 300px;">
                <div class="Page" style="display: block;"><div class="Pad">
                        <!-- ***** Cutomer Orders***** -->
                        Customers orders<br />
                        <table id="dataTable">
                            <tr><th>Date Ordered</th><th>Order Id</th><th>Phone Number</th><th>Items</th><th>Total Price</th><th>Order Status</th></tr>
                <?php
                $sql = "SELECT * FROM orders WHERE supermarketId = " . $supermarketId . ' ORDER BY dateOrdered DESC LIMIT 0 , 30';
                $result = mysql_query($sql);
                while ($row = mysql_fetch_array($result)) {
                    echo '<tr><td>' . $row['dateOrdered'] . '</td><td>' . $row['orderId'] . '</td><td>' . $row['phoneNumber'] . '</td><td>' . $row['items_amountOrdered'] . '</td><td>' . $row['totalPrice'] . '</td><td>' . $row['orderStatus'] . '</td></tr>';
                }
                ?> 
                        </table> 
                    </div>
                </div>
                <div class="Page" style="display:  none;"><div class="Pad">
                        <!-- ***** Existing Stocks***** -->
                        Existing stocks<br />
                        <div id="links">
                            <a href="index.php?action=add&mode=stock"><input type="submit" value="Add new record" /></a> 
                        </div>
                        <table id="dataTable">
                            <tr><th>Stock Name</th><th>Quantity Available</th><th>Price Per Unit</th><th colspan="2">Action</th></tr>		 
                            <?php
                            $sql = "SELECT * FROM stocks WHERE supermarketId = " . $supermarketId . ' ORDER BY stockName LIMIT 0 , 30';
                            $result = mysql_query($sql);
                            while ($row = mysql_fetch_array($result)) {
                                echo '<tr><td>' . $row['stockName'] . '</td><td>' . $row['quantity'] . '</td><td>' . $row['price'] . '</td><td><a href="index.php?action=edit&mode=stock&id=' . $row['stockId'] . '">Edit</a></td><td><a href="index.php?action=delete&mode=stock&id=' . $row['stockId'] . '">Delete<a></td></tr>';
                            }
                            ?> 
                        </table> 
                    </div>
                </div>
                <div class="Page" style="display:  none;"><div class="Pad">
                        <div id="links">
                            <a href="index.php?action=add&mode=category"><input type="submit" value="Add New Category" /></a> 
                        </div>
                        <table id="dataTable">
                            <tr><th>Category Id</th><th>Category Name</th><th colspan="2">Action</th></tr>
                            <?php
                            $sql = "SELECT * FROM categories WHERE supermarketId = " . $supermarketId . ' ORDER BY categoryName LIMIT 0 , 30';
                            $result = mysql_query($sql);
                            while ($row = mysql_fetch_array($result)) {

                                echo '<tr><td>' . $row['categoryId'] . '</td><td>' . $row['categoryName'] . '<td><a href="index.php?action=edit&mode=category&id=' . $row['categoryId'] . '">Edit</a></td><td><a href="index.php?action=delete&mode=category&id=' . $row['categoryId'] . '">Delete</a></td></tr>';
                            }
                            ?> 
                        </table>
                    </div>
                </div>
                            <?php
                            if ($accessLevel == 1) {
                                ?>
                    <div class="Page" style="display:  none;">
                        <div class="Pad">                   
                            <!-- ***** User accounts***** -->
                            System Users<br />
                            <div id="links">
                                <a href="add-user.php">Add user</a> 
                            </div>
                            <table id="dataTable">
                                <tr><th>Username</th><th>Supermarket</th><th>Access Level</th><th colspan="2">Action</th></tr>		 
                    <?php
                    $sql = "SELECT * FROM users ORDER BY username LIMIT 0 , 30";
                    $result = mysql_query($sql);
                    while ($row = mysql_fetch_array($result)) {
                        $sql2 = 'SELECT supermarketName FROM  supermarkets WHERE supermarketId=' . $row['supermarketId'];
                        $result2 = mysql_query($sql2);
                        while ($row2 = mysql_fetch_array($result2)) {
                            if ($row['accessLevel'] == 1) {
                                $level = 'Administrator';
                            } else {
                                $level = 'Power User';
                            }
                            echo '<tr><td><a href="index.php?action=view&mode=users&id=' . $row['userId'] . '">' . $row['username'] . '</a></td><td>' . $row2['supermarketName'] . '</td><td>' . $level . '</td><td><a href="index.php?action=edit&mode=users&id=' . $row['userId'] . '">Edit</a></td><td><a href="index.php?action=delete&mode=users&id=' . $row['userId'] . '">Delete<a></td></tr>';
                        }
                    }
                    ?> 
                            </table> 
                        </div>
                    </div>
                </div>
                                <?php
                            } else {
                                ?>

                <div class="Page" style="display:  none;">
                    <div class="Pad">
                                <?php
                                echo 'You are currently logged in as ' . $_SESSION['SESS_USER'] . '<br/>';
							
                                echo'<br/>From here you can ';
                               //echo 'View your account\'s activity log<br/>';
                                $sql = "SELECT * FROM users WHERE userId=" . $userId;
                                $result = mysql_query($sql);
                                while ($row = mysql_fetch_array($result)) {

                                    echo '<a href="index.php?action=edit&mode=users&id=' . $row['userId'] . '">Change your password<a>';
                                }
                                ?>
                    </div>
                </div>

                        <?php
                    }
                    ?>

        </div>
    </div>
    <script type="text/javascript" src="javascript/tab-view.js"></script>
    <script type="text/javascript">
        tabview_initialize('TabView');
    </script>
    </div>
</body>
</html>	
            <?php
        } mysql_close($con); 
        ?>