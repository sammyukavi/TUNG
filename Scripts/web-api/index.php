<?php

require 'tungClass.php';
if (isset($_GET) && !empty($_GET)) {
    if (isset($_GET['action']) && !empty($_GET['action'])) {
        if ($_GET['action'] == 'showCat') {
            if (isset($_GET['supId']) && !empty($_GET['supId'])) {
                $supermarketId = $_GET['supId'];
                $tungclassObject = new tungClass();
                $tungclassObject->getCategories($supermarketId);
            }
        } else
        if ($_GET['action'] == 'showStock') {
            if (isset($_GET['supId']) && !empty($_GET['supId'])) {
                if (isset($_GET['cat']) && !empty($_GET['cat'])) {
                    $supermarketId = $_GET['supId'];
                    $categoryName = $_GET['cat'];
                    $tungclassObject = new tungClass();
                    $tungclassObject->getStock($supermarketId, $categoryName);
                }
            }
        }
    }
} else {
    echo "It works!tung-api-version 1.0 is online!";
}
?>
