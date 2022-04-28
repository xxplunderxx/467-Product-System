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
<?php include 'worker.php'; ?>
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

    // end table from above
    echo "</table>";

        // START OF INVOICE
        echo "<h2>Invoice</h2>";
            echo "<table border=2>";
                echo "<tr>";    
                    echo "<th>QTY</th>";
                    echo "<th>Description</th>";
                    echo "<th>Unit Price</th>";
                    echo "<th>Charges</th>";
                echo "</tr>";

        //complete a pending order
        if(isset($_POST["complete_order"]) && isset($_POST["ord_hidden"]))
        {
            $order_id = $_POST["ord_hidden"];

            // query all products held in Order
            $sql = "SELECT * FROM Order_Prod WHERE Order_ID = $order_id;";
            foreach($pdo2->query($sql) as $row) {
                $product_id = $row[1];
                $amount = $row[2];

                // get old quantity
                $sql = "SELECT * FROM Inventory WHERE Num = $product_id;";
                $result = $pdo2->query($sql);
                $quantity = $result->fetch(PDO::FETCH_ASSOC);
                
                $new_quantity = $quantity["quantity"] - $amount;

                // update inventory with new quantity
                $sql = "UPDATE Inventory SET quantity = $new_quantity WHERE Num = $product_id;";
                if($pdo2->query($sql)) {
                    echo "Invenotry was updated successfully- ";
                }
                else {
                    echo "error in updating inventory";
                }
            }
                
            // update status
            $sql3 = "UPDATE Order_Info SET status = 'completed' WHERE Order_ID = $order_id;";
            if ($pdo2->query($sql3)) {
                echo "Successfully Changed Order to Completed<br/>";

                // send fake email
                // query orders table
                $sql = "SELECT * FROM Order_Info WHERE Order_ID = $order_id;";
                $result = $pdo2->query($sql);
                $order = $result->fetch(PDO::FETCH_ASSOC);

                echo "email was sent to adress: ". $order["cust_email"];
            }
        }
    // make shipping labels and send email
        // qet information from tables for labels
        $sql = "SELECT * FROM Order_Info WHERE Order_ID = $order_id;";
        $result = $pdo2->query($sql);
        $order = $result->fetch(PDO::FETCH_ASSOC);

        $sql1 = "SELECT * FROM Order_Prod WHERE Order_ID = $order_id;";
        foreach($pdo2->query($sql1) as $prod) {
            $sql2 = 'SELECT * FROM parts WHERE number = ?;';
            $prepared = $pdo->prepare($sql2);
            $prepared->execute(array($prod["prod_ID"]));
            $parts = $prepared->fetch(PDO::FETCH_ASSOC);

            //make Invoice continued
            echo "<tr>";
                echo "<td>".$prod["amount"]."</td>";
                echo "<td>".$parts["description"]."</td>";
                echo "<td>".$prod["price"]."</td>";
                echo "<td>$0.00</td>";
            echo "</tr>"; 
            echo "<tr>";
                echo "<td>TOTAL</td>";
                echo "<td>".$order["total_price"]."</td>";
            echo "</tr>";
        }

        // end invoice table
        echo "</table>";

    ?>

    <h2>Shpping Label</h2>
    <table border=2>
        <tr>
            <th>Prority 2 day shipping</th>
        </tr>
        <tr>
            <td>219 Lycoming St Stockton CA 95206</td>
        </tr>
        <tr>
            <td>SHIP TO:</td>
        </tr>
        <tr>
    <?php   echo "<td>". $order["cust_addr"]. "</td>"
    ?>
        </tr>
    </table>
</body>
</html>