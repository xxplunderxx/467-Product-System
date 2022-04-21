<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product System</title>
        <a href="./checkout.php"><button>Checkout</button></a>
</head>
<body>
<?php
        include 'secrets.php';

        if(isset($_POST['addToCart']))
        {       
                if(isset($_SESSION['shopping_cart'][0]))
                {
                        // check item added to cart is already in the session
                        $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
                        if(!in_array($_POST["prod_hidden"], $item_array_id))    
                        {
                                $count = count($_SESSION["shopping_cart"]);
                                $item_array = array(
                                        'item_id' => $_POST["prod_hidden"],
                                        'item_quantity' => $_POST["quantity"]
                                );
                                $_SESSION["shopping_cart"][$count] = $item_array;   // store after previous items
                        }
                        else{   // we know item is already stored in the session
                                echo'Error item has already been added';
                        }
                }
                else    // we need to get data from the form
                {
                        echo "added shopping cart session";
                        $item_array = array(
                                'item_id' => $_POST["prod_hidden"],
                                'item_quantity' => $_POST["quantity"]
                        );
                        $_SESSION["shopping_cart"][0] = $item_array;
                } 
        }

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
    <h1>Products</h1>
        <table border=2>
                <tr>
                        <th>Product</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Weight</th>
                        <th>Available</th>
                </tr>

<?php
        //*************************************************************************/
        // This will query all of the products in order to display each available
        $sql = "SELECT * FROM parts;";
	foreach($pdo->query($sql) as $item)
	{
                $initialized = false;
                if(!$initialized)       // only initialize once
                {
                        // initialize inventory table
                        $sql2 = "INSERT INTO Inventory(Num,quantity) VALUES(?,?);";
                        $prepared2 = $pdo2->prepare($sql2);
                        $prepared2->execute(array($item[0],10));   // item number from legacy_DB
			
			$initialized = true;
                }
                

                //get data from inventory table
		$sql2 = "SELECT * FROM Inventory WHERE Num = ?;";
		$prepared2 = $pdo2->prepare($sql2);
		$prepared2->execute(array($item[0]));   // item number from new_DB
		$prod = $prepared2->fetch();       
                
                echo "<tr>";
                        echo "<td><img src=\"" . $item[4] . "\"></td>";
                        echo "<td>". $item[1] . "</td>";
                        echo "<td>$" . $item[2] . "</td>";
                        echo "<td>" . $item[3] . "lbs.</td>";
                        echo "<td>" . $prod[1] . "</td>";
                        echo "<td><form action=\"http://students.cs.niu.edu/~z1892587/467-Product-System/customer.php\" method=\"POST\">";
                                echo "<input type=\"hidden\" name=\"prod_hidden\" value=\"$item[0]\" />Quantity:&nbsp;";
                                echo "<input type=\"text\" name=\"quantity\" required/>";
                                echo "<input type=\"submit\" name=\"addToCart\" value=\"Add to Cart\"/>";
                        echo "</td></form>";
                echo "</tr>";
	}
        // closes the table that we started above php block
        echo "</table>";
?>
</body>
</html>