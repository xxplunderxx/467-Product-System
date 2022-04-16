<?php session_start(); //start session?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Page</title>
</head>
<body>
    <h1>Log Inbound Products</h1>
    <form action="http://students.cs.niu.edu/~z1892587/467-Product-System/inbound.php" method="POST">
        <input type="text" name="product_id">
        <input type="text" name="description">
        <input type="text" name="quantity" required>
        <input type="submit" name="log_item">
    </form>
    <?php
        include("secrets.php");

        // Connecting to the legacy databse
        try { // if something goes wrong, an exception is thrown
            $dsn = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
            $pdo = new PDO($dsn, $username, $password);
        }
        catch(PDOexception $e) { // handle that exception
                echo "Connection to database failed: " . $e->getMessage();
        }

        //-************************************************************************
        // Connecting to the new database
        try{
            $dsn2 = "mysql:host=courses;dbname=".$username2;
            $pdo2 = new PDO($dsn2, $username2, $password2);
        }
        catch(PDOexception $e)
        {
            echo "Connection to database failed: " . $e->getMessage();
        }

        //check to see if item exists in legacy database
        //using product ID
        if (!empty($_POST["product_id"]))
        {
            $product = $_POST["product_id"];

            $sql = "SELECT number FROM parts WHERE number = ?;";
            $prepared = $pdo->prepare($sql);
            $prepared->execute(array($product));
            $rows = $prepared->fetch();

            $item_exists = true;
        }
        elseif (!empty($_POST["description"])) //using description
        {
            $description = $_POST["description"];
            
            $sql = "SELECT number FROM parts WHERE description = ?;";
            $prepared = $pdo->prepare($sql);
            $prepared->execute(array($description));
            $array = $prepared->fetch();
            $product = $array[0];
            
            $item_exists = true;
        }
        else{
            echo "ERROR- Product ID or Description required";
        }

        // used to update quanitity row with desk clerk's input
        if($item_exists)
        {
            $quantity = $_POST["quantity"];
            $sql = "UPDATE Inventory SET quantity = ? WHERE num = $product;";

            $prepared = $pdo2->prepare($sql);
            $prepared->execute(array($quantity));
            $row = $prepared->fetch();

            echo "\n updated quantity:" . $quantity. "successfully";
        }

        // $sql = 'SELECT name, colour, calories
        // FROM fruit
        // WHERE calories < :calories AND colour = :colour';
        // $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // $sth->execute(array('calories' => 150, 'colour' => 'red'));
    ?>
</body>
</html>