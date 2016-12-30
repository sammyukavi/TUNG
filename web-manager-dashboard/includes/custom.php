
<?php
if (isset($_POST['switchSupermarket'])) {
    $supermerketView = $_POST['changeTo'];

    function changeView($supermerketView) {
        if ($supermerketView == 1) {
            $_SESSION['SESS_SUPERMARKET_NAME'] = 'Tuskys';
            $_SESSION['SESS_SUPERMARKET_ID'] = 1;
        }
        if ($supermerketView == 2) {
            $_SESSION['SESS_SUPERMARKET_NAME'] = 'Uchumi';
            $_SESSION['SESS_SUPERMARKET_ID'] = 2;
        }
        if ($supermerketView == 3) {
            $_SESSION['SESS_SUPERMARKET_NAME'] = 'Nakumatt';
            $_SESSION['SESS_SUPERMARKET_ID'] = 3;
        }
        if ($supermerketView == 4) {
            $_SESSION['SESS_SUPERMARKET_NAME'] = 'Game';
            $_SESSION['SESS_SUPERMARKET_ID'] = 4;
        }
    }

    changeView($supermerketView);
}
?>

<!doctype html>
<html lang="en-us">
    <head>
        <meta charset="ISO-8859-1">
        <title>Tung App| Management</title>

        <link rel="stylesheet" type="text/css" href="./css/custom.css" />
    </head>
    <body>
        <div id="wb_Image">
            <img src="images/img0001.png" id="Image1" alt="" style="width:200px;height:250px;">
        </div>


<?php
if ($accessLevel == 1) {
    ?>
            <div id="supermarketView">
                <fieldset>
                    <form method="post" action='<?php echo $_SERVER['PHP_SELF'] ?>'>

                        <form method="post" action='<?php echo $_SERVER['PHP_SELF'] ?>'>
                            To view the database of another supermarket select it here
                            <select name="changeTo" class="textfield">
                                <option value="">Select supermarket</option>
                                <option value="1">Tuskys</option>
                                <option value="2">Uchumi</option>
                                <option value="3">Nakumatt</option>
                                <option value="4">Game</option>
                            </select>
                            <br/>
                            <input type="submit" name = "switchSupermarket" value="View"/>
                        </form>

                </fieldset>
            </form>
        </div>
<?php } ?>



    <div id="userDiv">
        You're logged in as: <?php echo $_SESSION['SESS_USER']; ?> <br/>
        Supermarket: <?php echo $_SESSION['SESS_SUPERMARKET_NAME']; ?> <br/>
        <a href="logout.php"><input type="button" value="Logout" /></a> </div>

    <div id="wb_logos">
        <div id="wb_logos2" style="position:absolute; left:14px; top:-9px; width:746px; height:86px; z-index:2; padding:0;">
            <table id="logos">
                <tr>
                    <td class="figure" style="width:154px;height:86px"><img alt="Tuskys" title="Tuskys" src="images/tn_Tuskys.png" style="width:154px;height:86px;"></a></td>
                    <td class="figure" style="width:154px;height:86px"><img alt="Uchumi" title="Uchumi" src="images/tn_uchumi.png" style="width:154px;height:86px;"></a></td>
                    <td class="figure" style="width:154px;height:86px"><img alt="Nakumatt" title="Nakumatt" src="images/tn_logo-nakumatt.png" style="width:154px;height:86px;"></a></td>
                    <td class="figure" style="width:154px;height:86px"><img alt="Game" title="Game" src="images/tn_game_billboard.png" style="width:154px;height:86px;"></a></td>
                </tr>
            </table>
        </div>


    </div>