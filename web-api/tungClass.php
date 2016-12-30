<?php

require 'connect.php';

/**
 * Description of tungClass
 *
 * @author Tarrus Njue
 */
class tungClass {

    public function getCategories($supermarketId) {
        $result = mysql_query("SELECT * FROM categories WHERE supermarketID=" . $supermarketId . " ORDER BY categoryName ASC");
        while ($row = mysql_fetch_array($result)) {
            if (isset($row) && !empty($row)) {
                echo $row['categoryName'] . ',';
            } else {
                echo 'Database not availabe';
            }
        }
    }

    public function getStock($supermarketId, $categoryName) {
        $result = mysql_query("SELECT categoryId FROM categories WHERE categoryName = '" . $categoryName . "' AND supermarketId = " . $supermarketId);
        //TO DO...Count number of rows affecgted. if greater than 1 continue else error
//$query="SELECT categoryId FROM categories WHERE categoryName = '" . $categoryName . "' AND supermarketId = " .$supermarketId;
 $count = mysql_num_rows($result);
        if ($count > 0) {


        while ($row = mysql_fetch_array($result)) {
            $categoryId = $row['categoryId'];
        }

        $result2 = mysql_query("SELECT * FROM stocks WHERE categoryId =$categoryId AND supermarketId = $supermarketId");

        $counter = mysql_num_rows($result2);
        if ($counter > 0) {
            while ($row2 = mysql_fetch_array($result2)) {
                echo $row2['stockName'] . " - " . $row2['price'] . ",";
            }
        } else {
            echo 'Stock not available';
        }
    }else {
            echo 'Stock not available';
        }
    }

}

?>
