<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Car Parts Store</title>
</head>
<body>

        <form action="http://students.cs.niu.edu/~z1886085/administrator.php" method=POST>
                <input type="submit" name="view_orders" value="View Orders"> </form>

        &nbsp;

        <form action="http://students.cs.niu.edu/~z1886085/administrator.php" method=POST>
                <input type="submit" name="view_weights" value="View Weight Brackets"> </form>


<?php
        include 'secrets.php';

	// Start the session to keep track of session variables (i.e)
	session_start();

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


	if(!isset($_POST['view_weights']) and !isset($_POST['view_details'])) {
		echo '<h1>Orders</h1>';
		echo '<table border=2>';
			echo '<tr>';
				echo '<th>Order_ID</th>';
				echo '<th>Cust. Name</th>';
				echo '<th>Cust. Address</th>';
				echo '<th>Cust. Email</th>';
				echo '<th>Total Price</th>';
				echo '<th>Total Weight</th>';
				echo '<th>Order Date</th>';
				echo '<th>Order Status</th>';
				echo '<th>More Details</th>';
			echo '</tr>';


		$sql = 'SELECT * FROM Order_Info;';
		foreach($pdo2->query($sql) as $item) {
			echo '<tr><td>' . $item[0] . '</td>';
			echo '<td>' . $item[1] . '</td>';
			echo '<td>' . $item[2] . '</td>';
			echo '<td>' . $item[3] . '</td>';
			echo '<td>' . $item[4] . '</td>';
			echo '<td>' . $item[7] . '</td>';
			echo '<td>' . $item[6] . '</td>';
			echo '<td>' . $item[5] . '</td>';
			echo '<td><form action="' . $_SERVER['PHP_SELF'] . '" method="POST">';
			echo "<input type=\"hidden\" name=\"order_id\" value=\"$item[0]\">";
			echo '<input type="submit" name="view_details" value="View Details"></form></td></tr>';
		}
		echo '</table>';
	}

	if(isset($_POST['view_details'])) {
		$order_id = $_POST['order_id'];

		echo '<h1>Order Details</h1>';

		$sql = 'SELECT * FROM Order_Parts WHERE Order_ID = ?;';
		$prepared = $pdo->prepare($sql);
		$prepared->execute(array($order_id));

		$sql = 'SELECT * FROM parts WHERE number = ?;';
		$prepared2 = $pdo2->prepare($sql);
                $prepared2->execute(array($order_id));
		$info = $prepared2->fetch();

		echo '<table border=2>';
                        echo '<tr>';
                                echo '<th>Order ID</th>';
                                echo '<th>Product ID/th>';
                                echo '<th>Quantity</th>';
                                echo '<th>Product Price</th>';
                                echo '<th>Total Product Price</th>';
                                echo '<th>Total Weight</th>';
                                echo '<th>Order Date</th>';
                                echo '<th>Order Status</th>';
                                echo '<th>More Details</th>';
                        echo '</tr>';

		while($part = $prepared->fetch()) {

		}
	}

	if(isset($_POST['view_weights'])) {

	}

?>
