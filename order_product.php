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
        if(isset($_POST["inspect_order"]) && isset($_POST["prod_hidden"]))
        {
            $order_id = $_POST["prod_hidden"];

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

    ?>

</body>
</html>