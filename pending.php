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
<html 
    h2{
     color: black;
     font-family: verdana;
     font-size: 300%;
    } >
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Order View</title>
</head>
<body>
    <h2>Pending Orders</h2>
    <table border=2>
        <tr>
            <th>Order Num</th>
            <th>Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Price</th>
            <th>Status</th>
            <th>Date</th>
            <th>Weight</th>
        </tr>
<?php
    // show all pending orders (on the Order_info table)
    $sql = "SELECT * FROM Order_Info WHERE status = 'pending';";
    foreach($pdo2->query($sql) as $item)
    {
        echo "<tr>";
            echo "<td>". $item[0] . "</td>";
            echo "<td>". $item[1] . "</td>";
            echo "<td>". $item[2] . "</td>";
            echo "<td>". $item[3] . "</td>";
            echo "<td>". $item[4] . "</td>";
            echo "<td>". $item[5] . "</td>";
            echo "<td>". $item[6] . "</td>";
            echo "<td>". $item[7] . "</td>";
            echo "<td><form action=\"http://students.cs.niu.edu/~z1892587/467-Product-System/order_product.php\" method=\"POST\">";
                echo "<input type=\"hidden\" name=\"prod_hidden\" value=\"$item[0]\" />";
                echo "<input type=\"submit\" name=\"inspect_order\" value=\"Inspect Order\"/>";
            echo "</td></form>";
            echo "<td><form action=\"http://students.cs.niu.edu/~z1892587/467-Product-System/pending.php\" method=\"POST\">";
                echo "<input type=\"hidden\" name=\"pend_hidden\" value=\"$item[0]\" />";
                echo "<input type=\"submit\" name=\"complete_order\" value=\"Complete Order\"/>";
            echo "</td></form>";
        echo "</tr>";
    }

    // closes off html table open tag
    echo "<table/>";

    //complete a pending order
    if(isset($_POST["complete_order"]) && isset($_POST["pend_hidden"]))
    {
        $order_id = $_POST["pend_hidden"];

        // update status
        $sql2 = "UPDATE Order_Info SET status = 'completed' WHERE Order_ID = $order_id;";
        if ($pdo2->query($sql2)) {
            echo "Successfully Changed Order to Completed";
            header('Refresh: 1; url=pending.php');    // refresh the page after changing status
        }
    }
?>
</body>
</html>