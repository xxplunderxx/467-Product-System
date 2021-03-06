<!-- CSCI 467 Group 1A -->
<!-- This is the adminsitrator page that can display all orders and weights brackets -->

<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Car Parts Store</title>
</head>
<body>
	<!-- This creates the buttons for the admin page to see all orders and to see weight brackets-->
        <form action="http://students.cs.niu.edu/~z1892587/467-Product-System/administrator.php" method=POST>
                <input type="submit" name="view_orders" value="View Orders"> </form>

        &nbsp;

        <form action="http://students.cs.niu.edu/~z1892587/467-Product-System/administrator.php" method=POST>
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


	// This displays all the orders with their general details, also provides a button to view
	// more specific details of the orders with all products
	if(!isset($_POST['view_weights']) and !isset($_POST['view_details']) and !isset($_POST['remove']) and !isset($_POST['add'])) {
		// Creates table for the orders
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

		// Queries all orders from order_info and displays in the table
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

	// Queries all the details for a specific order with all the products that were bought
	if(isset($_POST['view_details'])) {
		$order_id = $_POST['order_id'];

		echo '<h1>Order Details</h1>';

		// Queries all info from order prod and order info
		$sql = 'SELECT * FROM Order_Prod WHERE Order_ID = ?;';
		$prepared = $pdo2->prepare($sql);
		$prepared->execute(array($order_id));

                $sql = 'SELECT * FROM Order_Info WHERE Order_ID = ?;';
                $prepared3 = $pdo2->prepare($sql);
                $prepared3->execute(array($order_id));
                $order = $prepared3->fetch();

		// Disaplyes general order details
		echo '<h4>Order No. ' . $order_id . '</h4>';
		echo '<h4>Customer Name: ' . $order[1] . '</h4>';
		echo '<h4>Customer Address: ' . $order[2] .'</h4>';
		echo '<h4>Customer Email: ' . $order[3] . '</h4>';
		echo '<h4>Total Price: ' . $order[4] . '</h4>';
		echo '<h4>Total Weight: ' . $order[7] . '</h4>';
		echo '<h4>Status: ' . $order[5] . '</h4>';

		// Creates a table to display all items bought in the order
		echo '<table border=2>';
                        echo '<tr>';
                                echo '<th>Product ID</th>';
				echo '<th>Description</th>';
                                echo '<th>Quantity</th>';
                                echo '<th>Price</th>';
                        echo '</tr>';

		// Query to get all parts in the order and display info
		while($part = $prepared->fetch()) {
			$sql = 'SELECT * FROM parts WHERE number = ?;';
                	$prepared3 = $pdo->prepare($sql);
        	        $prepared3->execute(array($part[1]));
	                $info = $prepared3->fetch();

			echo '<td>' . $part[1] . '</td>';
			echo '<td>' . $info[1] . '</td>';
			echo '<td>' . $part[2] . '</td>';
			echo '<td>' . $part[3] . '</td></tr>';
		}

		echo '</table>';
	}

	// This is for the weight brackets portion of the page. This specifically remove
	// a weight bracket and adusts the price so that all possible weights have a shipping
	// cost associated with it
        if(isset($_POST['remove'])) {
                $id = $_POST['id'];

		// This queries the details for the bracket to be removes
                $sql = 'SELECT * FROM Weights WHERE id = ?;';
                $prepared = $pdo2->prepare($sql);
                $prepared->execute(array($id));
                $bracket_remove = $prepared->fetch();

		// This changes the high weight bracket for the previous weight bracket
		// to the high weight of the bracket to remove. this will accomodate
		// all weights be accounted for
		$sql = 'UPDATE Weights SET high = ? WHERE id = ?;';
                $prepared2 = $pdo2->prepare($sql);
                $prepared2->execute(array($bracket_remove[2],$id-1));

		// Delete old weight bracket
                $sql = 'DELETE FROM Weights WHERE id = ?';
                $prepared = $pdo2->prepare($sql);
                $prepared->execute(array($id));

		// Queries to get all the weight brackets
		$sql = 'SELECT * FROM Weights;';
                $prepared = $pdo2->prepare($sql);
                $prepared->execute();

		// This changes the ids for the weights brackets so that they are consecutive
		// and ascending
		while($weight = $prepared->fetch()) {
			if($weight[0] > $id) {
		                $sql = 'UPDATE Weights SET id = ? WHERE id = ?;';
		                $prepared2 = $pdo2->prepare($sql);
        		        $prepared2->execute(array($weight[0]-1,$weight[0]));
			}
		}
        }

	// This will add a weight bracket and adjusts the weights and ids
	if(isset($_POST['add'])) {
		$weight = $_POST['weight'];
		$cost = $_POST['cost'];

		// Query all the weight brackets
		$sql = 'SELECT * FROM Weights;';
                $prepared = $pdo2->prepare($sql);
                $prepared->execute();

		// Find the id number that the new bracket will be inserted
		$bracket = $prepared->fetch();
		while($bracket[1] < $weight) {
			$bracket = $prepared->fetch();
		}

		$id = $bracket[0] - 1;
//		echo $id;

		// Find the weight bracket at the same place as id and save
		// the info
                $sql = 'SELECT * FROM Weights WHERE id = ?;';
                $prepared = $pdo2->prepare($sql);
                $prepared->execute(array($id));
		$bracket = $prepared->fetch();

		$blow = $bracket[1];
		$bhigh = $bracket[2];
		$bcost = $bracket[3];

//		echo $blow;

		// Delete the old weight bracket at the id
                $sql = 'DELETE FROM Weights WHERE id = ?';
                $prepared = $pdo2->prepare($sql);
                $prepared->execute(array($id));

		// insert the new bracket with old low weight but new high weight
                $sql = 'INSERT INTO Weights VALUES(?,?,?,?);';
                $prepared = $pdo2->prepare($sql);
                $prepared->execute(array($id,$blow,$weight,$bcost));

		// Query all weight brackets
                $sql = 'SELECT * FROM Weights;';
                $prepared = $pdo2->prepare($sql);
                $prepared->execute();

		$oid = 0;
		$olow = 0;
		$ohigh = 0;
		$ocost = 0;
		$bracket = $prepared->fetch();
		$cont = TRUE;

		while($cont) {

			if(!is_bool($bracket)) {
			if($bracket[0] > $id) {
				if($bracket[0] > ($id + 1)) {
					// Sets the following brackets with same info but with
					// an id + 1. This makes sure all brackets are consecutive
					// and in order
                                        $sql = 'DELETE FROM Weights WHERE id = ?';
                                        $prepared3 = $pdo2->prepare($sql);
                                        $prepared3->execute(array($oid));

					$sql = 'INSERT INTO Weights VALUES(?,?,?,?);';
			                $prepared2 = $pdo2->prepare($sql);
			                $prepared2->execute(array($oid,$olow,$ohigh,$ocost));
				}

				// sets the bracket info
				$oid = $bracket[0] + 1;
//				echo $oid;
				$olow = $bracket[1];
				$ohigh = $bracket[2];
				$ocost = $bracket[3];

				// insert new weight bracket with the bracket that was
                                // removed to replace added one
				if($bracket[0] == ($id + 1)) {
	                                $sql = 'DELETE FROM Weights WHERE id = ?';
        	                        $prepared4 = $pdo2->prepare($sql);
                	                $prepared4->execute(array($oid-1));

					$sql = 'INSERT INTO Weights VALUES(?,?,?,?);';
                                        $prepared3 = $pdo2->prepare($sql);
                                        $prepared3->execute(array($id + 1,$weight,$bhigh,$cost));
				}
			}
			}
			// Sets last bracket to id + 1
			else {
				$cont = FALSE;
                                $sql = 'DELETE FROM Weights WHERE id = ?';
                                $prepared3 = $pdo2->prepare($sql);
                                $prepared3->execute(array($oid));

                                $sql = 'INSERT INTO Weights VALUES(?,?,?,?);';
                                $prepared2 = $pdo2->prepare($sql);
                                $prepared2->execute(array($oid,$olow,$ohigh,$ocost));
			}

			$bracket = $prepared->fetch();
		}
	}

	// This displays al; the weight brackets and the buttons to add or remove a bracket
	if(isset($_POST['view_weights']) or isset($_POST['remove']) or isset($_POST['add'])) {
		echo '<h2>Weight brackets to calculate shipping cost:</h2>';

		// Query all weight brackets
		$sql = 'SELECT * FROM Weights;';
                $prepared = $pdo2->prepare($sql);
                $prepared->execute();

		// display all weights and shipping cost
		while($weights = $prepared->fetch()) {
			echo '<p><form action"' . $_SERVER['PHP_SELF'] . '" method="POST">';
			echo '&emsp;from ' . $weights[1] . ' to ' . $weights[2] . ' lbs';
			echo '&emsp;&emsp;&emsp;&emsp;$' . $weights[3] . '&emsp;&emsp;&emsp;&emsp;';
			echo '<input type="hidden" name="id" value="' . $weights[0] . '">';
	                echo '<input type="submit" name="remove" value="Remove"></form></p>';
		}

		// display to add new weight bracket
		echo '<h3>Add new bracket</h3>';
		echo '<p><form actionn"' . $_SERVER['PHP_SELF'] . '" method="POST">Weight:&nbsp;<input type="text" name="weight">&nbsp;&nbsp;';
		echo 'Cost:&nbsp;<input type="text" name="cost">&nbsp;';
		echo '<input type="submit" name="add" value="Add"></form></p>';
	}


?>
