<?php session_start(); //start session?>
<!DOCTYPE html>
<html lang="en">
<?php include 'worker.php'; ?>
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
        try { 
            $dsn = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
            $pdo = new PDO($dsn, $username, $password);
        }
        catch(PDOexception $e) { // handle exception
                echo "Connection to database failed: " . $e->getMessage();
        }

        //-************************************************************************
        // Connecting to the new database
        try{
            $dsn2 = "mysql:host=courses;dbname=".$username2;
            $pdo2 = new PDO($dsn2, $username2, $password2);
        }
        catch(PDOexception $e) { // handle exception
            echo "Connection to database failed: " . $e->getMessage();
        }

        //check to see if item exists in legacy database
        //using product ID
        $item_exists = false;
        if (!empty($_POST["product_id"]))
        {
            $product = $_POST["product_id"];

            $sql = "SELECT number FROM parts WHERE number = ?;";
            $prepared = $pdo->prepare($sql);
            $prepared->execute(array($product));
            $rows = $prepared->fetch();

            if($rows) { // check if item exists
                $item_exists = true;
            }
        }
        //using description
        elseif (!empty($_POST["description"])) 
        {
            $description = $_POST["description"];
            
            $sql = "SELECT number FROM parts WHERE description = ?;";
            $prepared = $pdo->prepare($sql);
            $prepared->execute(array($description));
            $array = $prepared->fetch();
            $product = $array[0];
            
            if($array) { // check if item exists
                $item_exists = true;
            }
        }
        else{
            if (isset($_POST["product_id"]) || isset($_POST["description"])) { // check if user inputed data before error
                echo "Product ID or Description required";
            }
        }

        // used to update quanitity row with desk clerk's input
        // update additional quanity not just quantity
        if($item_exists)
        {
            $quantity = $_POST["quantity"];
            $sql = "UPDATE Inventory SET quantity = :quantity WHERE num = :product;";

            $prepared = $pdo2->prepare($sql,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $prepared->execute(array('quantity'=>$quantity, 'product'=>$product));
            $row = $prepared->fetch();

            echo "\n updated quantity:" . $quantity. "successfully";
            echo $item_exists;
        }
        else  
        {
            if (isset($_POST["product_id"]) || isset($_POST["description"])) { // check if user inputed data before error
                echo "item ".$product. " does not exist";
            }
        }

    ?>
</body>
</html>