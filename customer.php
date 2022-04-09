<?php

include 'secrets.php';

session_start();

if(!(isset($_SESSION['cart']))) {
	$_SESSION['cart'];
}

// boiler plate html
echo "<html><head><title>Product System</title></head><body>";


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

//-*************************************************************************
// This will add chosen quantities to the cart

if(isset($_POST['addToCart'])) {

	$prod = $_POST['prod'];
	$quant = $_POST['quantity'];
//	print_r($quant);

	echo $prod;
	//echo $quant;

	// checking for invalid input
	if($quant > 0 and is_numeric($quant)) {
		if(isset($_SESSION['cart'][$prod])) {
			$_SESSION['cart'][$prod] += $quant;
		}
		else {
			$_SESSION['cart'][$prod] = $quant;
		}
	}
	else {
		echo "bad input";
	}



	print_r($_SESSION['cart']);

	session_destroy();
}


//*************************************************************************/
// This will query all of the products in order to display each available
	$sql = "SELECT * FROM parts;";

	echo "<h1> Products </h1><table border=2>";
		echo "<tr><th>Product</th><th>Description</th><th>Price</th><th>Weight</th><th>Available</th><th></th>";

//	while($item = $prepared->fetch())
        echo "<form action=\"http://students.cs.niu.edu/~z1886085/customer.php\" method=\"POST\">";

	foreach($pdo->query($sql) as $item)
	{
		$sql2 = "INSERT INTO Inventory(Num) VALUES(?);";
		$prepared2 = $pdo2->prepare($sql2);
		$prepared2->execute(array($item[0]));

		$sql2 = "SELECT * FROM Inventory WHERE Num = ?;";
		$prepared2 = $pdo2->prepare($sql2);
		$prepared2->execute(array($item[0]));
		$prod = $prepared2->fetch();
		echo "<tr><td><img src=\"" . $item[4] . "\"></td><td>". $item[1] . "</td><td>$" . $item[2] . "</td><td>" . $item[3] . "lbs.</td><td>" . $prod[1] . "</td>";

                echo "<td><input type=\"hidden\" name=\"prod\" value=\"$item[0]\" />Quantity:&nbsp;<input type=\"text\" name=\"quantity\"/><input type=\"submit\" name=\"addToCart\" value=\"Add to Cart\"/></td></tr>";
	}

	echo "</form></table>";

//}
echo "</body>"

?>