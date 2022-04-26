<?php 
    session_start(); //start session

    include('secrets.php');
    
    // Connecting to th legacy databse
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Product View</title>
</head>
<body>
    <h1>Order Inspection</h1>
    <table border=2>
        <tr>
            <th>Order Num</th>
            <th>Product Num</th>
            <th>Amount</th>
            <th>Price</th>
        </tr>
    <?php
        if(isset($_POST["inspect_pending"]) && isset($_POST["pending_hidden"]))    //pending order inspection
        {
            $order_id = $_POST["pending_hidden"];

            // link back to pending order page
            echo "<a href=\"./pending.php\"><button>Back</button></a>";

            // print out all the products associated with the inspected order
            $sql = "SELECT * FROM Order_Prod WHERE Order_ID = $order_id;";
            foreach($pdo2->query($sql) as $item)
            {
                echo "<tr>";
                    echo "<td>". $item[0] . "</td>";
                    echo "<td>". $item[1] . "</td>";
                    echo "<td>". $item[2] . "</td>";
                    echo "<td>". $item[3] . "</td>";
                    echo "<td><form action=\"http://students.cs.niu.edu/~z1892587/467-Product-System/order_product.php\" method=\"POST\">";
                        echo "<input type=\"hidden\" name=\"ord_hidden\" value=\"$item[0]\" />";
                        echo "<input type=\"submit\" name=\"complete_order\" value=\"Complete Order\"/>";
                    echo "</td></form>";
                echo "</tr>";                
            }
        }
        elseif(isset($_POST["inspect_completed"]) && isset($_POST["completed_hidden"]))    //completed order inspection
        {
            $order_id = $_POST["completed_hidden"];

            // link back to completed order page
            echo "<a href=\"./completed.php\"><button>Back</button></a>";

            // print out all the products associated with the inspected order
            $sql = "SELECT * FROM Order_Prod WHERE Order_ID = $order_id;";
            foreach($pdo2->query($sql) as $item)
            {
                echo "<tr>";
                    echo "<td>". $item[0] . "</td>";
                    echo "<td>". $item[1] . "</td>";
                    echo "<td>". $item[2] . "</td>";
                    echo "<td>". $item[3] . "</td>";
                echo "</tr>";                
            }
        }
        else {
            echo "ERROR - FORM HAS NOT BEEN SENT";
        }

        //complete a pending order
        if(isset($_POST["complete_order"]) && isset($_POST["ord_hidden"]))
        {
            $order_id = $_POST["ord_hidden"];

            // query all products held in Order
            $sql = "SELECT * FROM Order_Prod WHERE Order_ID = $order_id;";
            $result = $pdo2->query($sql);
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "\nOrder ID: ". $row["Order_ID"] . "Product ID: ". $row["prod_ID"]. "amount: ". $row["amount"];
                $product_id = $row["prod_ID"];
                $amount = $row["amount"];

                // get old quantity
                $sql = "SELECT * FROM Inventory WHERE Num = $product_id;";
                $result = $pdo2->query($sql);
                $quantity = $result->fetch(PDO::FETCH_ASSOC);
                
                $new_quantity = $quantity["quantity"] - $amount;

                //update inventory with new quantity
                $sql = "UPDATE Inventory SET quantity = $new_quantity WHERE Num = $product_id;";
                if($pdo2->query($sql)) {
                    echo "Invenotry was updated successfully";
                }
                else {
                    echo "error in updating inventory";
                }
            }

            // update status
            // $sql3 = "UPDATE Order_Info SET status = 'completed' WHERE Order_ID = $order_id;";
            // if ($pdo2->query($sql3)) {
            //     header('Refresh: 1; url=pending.php');    // refresh the page after changing status
            //     echo "Successfully Changed Order to Completed";
            // }
        }
    ?>
</body>
</html>