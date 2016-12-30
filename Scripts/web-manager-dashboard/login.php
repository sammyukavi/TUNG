<?php require('includes/header.php'); ?>
<div id="loginDiv">
    <form name="loginform" method="post" action="check-login.php" id="loginform">
        <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
			 <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
            <tr>
                <td width="112"><b>Username</b></td>
                <td width="188"><input name="login" type="text" id="login" /></td>
            </tr>
            <tr>
                <td><b>Password</b></td>
                <td><input name="password" type="password" id="password" /></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="Submit" value="Login" /></td>
            </tr>
        </table>

    </form>
</div>