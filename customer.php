<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<style>
h1{
        color: black;
        font-family: verdana;
        font-size: 300%;
        text-align: center
}
tr, th{
        text-align: center;
        vertical-align: center;
        border: 1px solid black;
        background: white
        
}
th{
        background-color: #104b78;
        color: white
}
.button{
        color: BLACK;
        background-color: #3175a8; 
        padding: 15px 32px;
        border: none;
        display: inline-block;
        margin: 4px 2px;
        border-radius: 12px;
        font-family: Fantasy;
        text-decoration: none;
}
a.button:hover, a.button:active{
        color: BLACK;
        background-color: #419ade;
}
body{
        background-image: linear-gradient(#304352, #d7d2cc);

}

</style>
<head>
        <?php include 'header.php';?>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product System</title>
        <a href="./checkout.php" class="button">CHECKOUT 🛒</a>
</head>
<body>
        
        <form action="http://students.cs.niu.edu/~z1892587/467-Product-System/customer.php" method=POST>
                <input type="submit" name="view_products" value="View Products"> </form>

        &nbsp;

        <form action="http://students.cs.niu.edu/~z1892587/467-Product-System/customer.php" method=POST>
                <input type="submit" name="view_cart" value="View Cart"> </form>

<?php
    include 'secrets.php';

	// Set the shopping cart to empty array if null
	if(!(isset($_SESSION['shopping_cart']))) {
		$_SESSION['shopping_cart'] = array();
	}

	//-**********************************************************************
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
	try {
		$dsn2 = "mysql:host=courses;dbname=".$username2;
		$pdo2 = new PDO($dsn2, $username2, $password2);
        }
        catch(PDOexception $e)
        {
                echo "Connection to database failed: " . $e->getMessage();
        }

	//-**********************************************************************
	// This takes the quantity and item_id of the product that was chosen to
	// add to cart and adds it to an associative array that holds all
	// shopping cart info in session variables
	//***********************************************************************/
	if(isset($_POST['addToCart']))
        {
		// Sets the shopping cart with new item if not empty
                if(isset($_SESSION['shopping_cart'][0]))
                {
                        // check item added to cart is already in the session
			//	if not it just sets the quantity to the amount in the
			//	written in the text box
                        $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
                        if(!in_array($_POST["prod_hidden"], $item_array_id))
                        {
				// finds the count of the next open position in shopping cart array
				// and add new item
                                $count = count($_SESSION["shopping_cart"]);
                                $item_array = array(
                                        'item_id' => $_POST["prod_hidden"],
                                        'item_quantity' => $_POST["quantity"]
                                );
                                $_SESSION["shopping_cart"][$count] = $item_array;   // store after previous items
                        }
			// If item already is already stored add to the current quantity
                        else{   // we know item is already stored in the session
				$count = array_search($_POST["prod_hidden"], $item_array_id);
				$item_quantity = $_SESSION["shopping_cart"][$count]["item_quantity"] + $_POST["quantity"];
				$item_array = array (
					'item_id' => $_POST["prod_hidden"],
					'item_quantity' => $item_quantity
				);
				$_SESSION["shopping_cart"][$count] = $item_array;
                        }
                }
		// If shopping cart is empty add item to first position in array
                else
                {
                        $item_array = array(
                                'item_id' => $_POST["prod_hidden"],
                                'item_quantity' => $_POST["quantity"]
                        );
                        $_SESSION["shopping_cart"][0] = $item_array;
                }

        } // add to cart

        //-**********************************************************************
	// This displays the number of items in the cart, the actual items, and
	// the info needed to check out and order the items
	//-**********************************************************************
	if(isset($_POST['view_cart']))
        {
		// variables to hold details regarding cart totals
		$amount = 0;
		$weight = 0;

		// Displays items in the cart
		$num_items = count($_SESSION["shopping_cart"]);
		echo "<h3>You have " . $num_items . " part(s) in your cart</h3>";
		echo '&nbsp;';
		echo '<form action="http://students.cs.niu.edu/~z1892587/467-Product-System/customer.php" method=POST>';
                echo '<input type="submit" name="clear_cart" value="Clear Cart"> </form>';
		echo '<h1>Shopping Cart</h1>';
                echo '<table border=2>';
                        echo '<tr>';
                                echo '<th>Product</th>';
                                echo '<th>Description</th>';
                                echo '<th>Price</th>';
                                echo '<th>Weight</th>';
                                echo '<th>Quantity</th>';
                        echo '</tr>';

		// Query the info for the products
		$sql1 = "SELECT * FROM parts WHERE number = ?;";
		$sql2 = "SELECT * FROM Inventory WHERE number = ?;";

		$products = array();
		$quantity = array();

		foreach($_SESSION['shopping_cart'] as $item)
		{
			$prepared = $pdo->prepare($sql1);
			$prepared->execute(array($item["item_id"]));
			$prod = $prepared->fetch();

			array_push($products, $prod[0]);
			array_push($quantity, $item["item_quantity"]);

			// Display all the products in the shopping cart
			echo "<tr>";
				echo "<td><img src=\"" . $prod[4] . "\"></td>";
				echo "<td>". $prod[1] . "</td>";
				echo "<td>$" . $prod[2] . "</td>";
				echo "<td>" . $prod[3] . "lbs.</td>";
				echo "<td>" . $item["item_quantity"] . "</td>";
				echo "<td><form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"POST\">";
					echo "<input type=\"hidden\" name=\"prod_hidden\" value=\"$prod[0]\" />";
					echo "<input type=\"submit\" name=\"remove\" value=\"Remove\"/>";
					echo "</form></td>";
			echo "</tr>";

			// Add product price and weight to totals
			$amount += ($prod[2] * $item["item_quantity"]);
			$weight += ($prod[3] * $item["item_quantity"]);
		}
		echo "</table>";

		$shipping = 0;
		$total = $amount + $shipping;
		// Print billing information and allow for checkout
		echo "<h4>Billing Information</h4>";
		echo "<p>&emsp;&ensp;Amount: $" . $amount . "<br/>";
		echo "&emsp;&emsp;Weight: " . $weight . "lbs.<br/>";
		echo "&emsp;&emsp;&ensp;&nbspTotal: $" . $total . "</p>";

		$count = 0;

		// Prints form for customer info to order table
		echo "<form action\"" . $_SERVER['PHP_SELF'] . "\" method=\"POST\">";
			echo "<input type=\"hidden\" name=\"total_price\" value=\"" . $total . "\">";
			echo "<input type=\"hidden\" name=\"total_weight\" value=\"" . $weight . "\">";
			echo "&emsp;&emsp;&ensp;Name:&nbsp<input type=\"text\" name=\"name\"><br/>";
			echo "&emsp;&emsp;&ensp;Email:&nbsp<input type=\"text\" name=\"email\"><br/>";
			echo "&emsp;&ensp;&nbsp;Address:&nbsp<input type=\"text\" name=\"address\"><br/>";
			echo "&emsp;&emsp;&emsp;&ensp;&nbspCC:&nbsp<input type=\"text\" name=\"cc\"><br/>";
			echo "&emsp;&emsp;&emsp;&nbsp;Exp.:&nbsp<input type=\"text\" name=\"exp\"><br/><br/>";
			echo "&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp<input type=\"submit\" name=\"order\" value=\"Check Out\">";
		echo "</form>";

	} // view  cart

	// This clears the cart and displays message
	if(isset($_POST['clear_cart'])) {
		$count = 0;
                while(isset($_SESSION['shopping_cart'][$count])) {
	                unset($_SESSION['shopping_cart'][$count]);
        	        $count++;
		}

		echo '<h4>CART CLEARED</h4>';
	}

	// This adds an order to the order tables in the database, sends the cc info to the cc
	// processing system
	if(isset($_POST['order']))
        {
		// Gets all the customer info
		$total_weight = $_POST['total_weight'];
		$total_price = $_POST['total_price'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$address = $_POST['address'];
		$cc = $_POST['cc'];
		$exp = $_POST['exp'];

		// connects to the credit card processing system
		$url = 'http://blitz.cs.niu.edu/CreditCard/';
		$data = array(
			'vendor' => 'VE001-99',
			'trans' => '907-987654321-296',
			'cc' => $cc,
			'name' => $name,
			'exp' => $exp,
			'amount' => $total_price);

		$options = array(
		    'http' => array(
		        'header' => array('Content-type: application/json', 'Accept: application/json'),
		        'method' => 'POST',
		        'content'=> json_encode($data)
		    )
		);

		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		echo $result;

		// Checks for error in the result
		if(strpos($result, 'error') !== false)
		{
			echo "<h3>Order unsuccesful: payment did not go through</h3>";
		}
		// Inserts the order into the database
		else
		{
			$sql = "INSERT INTO Order_Info(cust_name,cust_addr,cust_email,total_price,status,total_weight) VALUES(?,?,?,?,?,?);";
		        $prepared = $pdo2->prepare($sql);
 		        $prepared->execute(array($name, $address, $email, $total_price, 'Pending', $total_weight));

			$sql = "SELECT Order_ID FROM Order_Info ORDER BY Order_ID DESC LIMIT 1;";
			$prepared = $pdo2->prepare($sql);
			$prepared->execute();
                        $id = $prepared->fetch();

			// Inserts each part in the order into the order products
			foreach($_SESSION['shopping_cart'] as $item) {
				$sql = "SELECT price FROM parts WHERE number = ?;";
	                        $prepared = $pdo->prepare($sql);
        	                $prepared->execute(array($item['item_id']));
                	        $price = $prepared->fetch();

				$sql = "INSERT INTO Order_Prod(Order_ID,prod_ID,amount,price) VALUES(?,?,?,?);";
				$prepared = $pdo2->prepare($sql);
	                        $prepared->execute(array($id[0], $item['item_id'], $item['item_quantity'], $price['price']));
			}

			// Print that order was succesful
			echo '<h3>' . $name . ', your order has been succesfully placed.</h3>';

			$count = 0;
			while(isset($_SESSION['shopping_cart'][$count])) {
				unset($_SESSION['shopping_cart'][$count]);
				$count++;
			}
		}
	} // order

	// This removes an item from the shopping_cart
	if(isset($_POST['remove']))
	{
		// Finds which item needs to be removed
		$prod = $_POST['prod_hidden'];

		// Finds location if item in array
		$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
		$count = array_search($_POST["prod_hidden"], $item_array_id);

		// unsets item from shopping cart
		unset($_SESSION['shopping_cart'][$count]);

		echo "<h3>Item successfully removed from cart</h3>";
	}

        //*************************************************************************/
        // This will query all of the products in order to display each available product
	//*************************************************************************/
	if(!isset($_POST['view_cart']) and !isset($_POST['search_button']) and !isset($_POST['order']) and !isset($_POST['remove'])) {
		// provides a search bar to limit
		echo '<form action"' . $_SERVER['PHP_SELF'] . '" method="POST">';
		echo '<p>Search:&nbsp;<input type="text" name="search">&nbsp;';
		echo '<input type="submit" name="search_button" value="Search"></p></form>';

		// Creates table
		echo '<h1>Products</h1>';
		echo '<table border=2>';
			echo '<tr>';
				echo '<th>Product</th>';
				echo '<th>Description</th>';
				echo '<th>Price</th>';
				echo '<th>Weight</th>';
				echo '<th>Available</th>';
			echo '</tr>';

        	$sql = "SELECT * FROM parts;";
		// Queries inventory quantity and displays all product info
		foreach($pdo->query($sql) as $item)
		{
			//----------------------------------------------------------------
			// May need to be removed
//			$sql2 = "INSERT INTO Inventory(Num) VALUES(?);";
//			$prepared2 = $pdo2->prepare($sql2);
//			$prepared2->execute(array($item[0]));   // item number from legacy_DB
			//----------------------------------------------------------------

			$sql2 = "SELECT * FROM Inventory WHERE Num = ?;";
			$prepared2 = $pdo2->prepare($sql2);
			$prepared2->execute(array($item[0]));   // item number from new_DB
			$prod = $prepared2->fetch();

			echo "<tr><td><img src=\"" . $item[4] . "\"></td><td>". $item[1] . "</td><td>$" . $item[2] . "</td><td>" . $item[3] . "lbs.</td><td>" . $prod[1] . "</td>";

  	                echo "<td><form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"POST\"><input type=\"hidden\" name=\"prod_hidden\" value=\"$item[0]\" />Quantity:&nbsp;<input type=\"text\" name=\"quantity\"/><input type=\"submit\" name=\"addToCart\" value=\"Add to Cart\"/></td></form></tr>";
		}

		echo "</table>";
	}

	// If the search button is pressed, this displays the limited results. If the text box is
	// empty then all products will be displayed
	if(isset($_POST['search_button'])) {
		// Search menu to limit search
                echo '<form action"' . $_SERVER['PHP_SELF'] . '" method="POST">';
                echo '<p>Search:&nbsp;<input type="text" name="search">&nbsp;';
                echo '<input type="submit" name="search_button" value="Search"></p></form>';

		// Finds keyword for searching
		$search = $_POST['search'];

		echo '<h1>Products</h1>';
                echo '<table border=2>';
                        echo '<tr>';
                                echo '<th>Product</th>';
                                echo '<th>Description</th>';
                                echo '<th>Price</th>';
                                echo '<th>Weight</th>';
                                echo '<th>Available</th>';
                        echo '</tr>';

		// Queries all parts and only prints those that contain the keyword
                $sql = "SELECT * FROM parts;";
                foreach($pdo->query($sql) as $item)
                {
			//may need to remove
			//-----------------------------------------------------------------
//                        $sql2 = "INSERT INTO Inventory(Num) VALUES(?);";
//                        $prepared2 = $pdo2->prepare($sql2);
//                        $prepared2->execute(array($item[0]));   // item number from legacy_DB
			//----------------------------------------------------------------

			// Finds inventory and prints all product info
                        $sql2 = "SELECT * FROM Inventory WHERE Num = ?;";
                        $prepared2 = $pdo2->prepare($sql2);
                        $prepared2->execute(array($item[0]));   // item number from new_DB
                        $prod = $prepared2->fetch();

			if(empty($search) or strpos($item[1], $search) !== false)
			{
	                        echo "<tr><td><img src=\"" . $item[4] . "\"></td><td>". $item[1] . "</td><td>$" . $item[2] . "</td><td>" . $item[3] . "lbs.</td><td>" . $prod[1] . "</td>";

        	                echo "<td><form action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"POST\"><input type=\"hidden\" name=\"prod_hidden\" value=\"$item[0]\" />Quantity:&nbsp;<input type=\"text\" name=\"quantity\"/><input type=\"submit\" name=\"addToCart\" value=\"Add to Cart\"/></td></form></tr>";
			}
		}
                echo "</table>";
	}
?>


</body>
</html>