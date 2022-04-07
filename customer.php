<?php

include 'secrets.php';

echo "<html><head><title>Product System</title></head><body>";

// Connecting to th legacy databse
try { // if something goes wrong, an exception is thrown
        $dsn = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
        $pdo = new PDO($dsn, $username, $password);
}
catch(PDOexception $e) { // handle that exception
        echo "Connection to database failed: " . $e->getMessage();
}

// Connecting to the new database
try { // if something goes wrong, an exception is thrown
        $dsn2 = "mysql:host=courses;dbname=z1886085";
        $pdo2 = new PDO($dsn2, $username2, $password2);
}
catch(PDOexception $f) { // handle that exception
        echo "Connection to database failed: " . $f->getMessage();
}

/*************************************************************************/
// This will query all of the products in order to display each available

$sql = "SELECT * FROM parts;";
$prepared = $pdo->prepare($sql);
$prepared->execute();

echo "<h1> Products </h1><table border=2>";
	echo "<tr><th>Product</th><th>Description</th><th>Price</th><th>Weight</th><th>Available</th><th></th>";

while($item = $prepared->fetch())
{
	$sql = "INSERT INTO Inventory(Num) VALUES(?);";
	$prepared2 = $pdo2->prepare($sql);
	$prepared2->execute(array($item[0]));

	$sql = "SELECT * FROM Inventory WHERE Num = ?;";
	$prepared2 = $pdo2->prepare($sql);
	$prepared2->execute(array($item[0]));
	$prod = $prepared2->fetch();

	echo "<tr><td><img src=\"" . $item[4] . "\"></td><td>" . $item[1] . "</td><td>$" . $item[2] . "</td><td>" . $item[3] . "lbs.</td><td>" . $prod[1] . "</td>";
		echo "<td><form action=\"http://students.cs.niu.edu/~z1886085/customer.php\" method=\"POST\">Quantity:&nbsp;<input type=\"text\" name=\"quantity\"/><input type=\"submit\" name=\"addToCart\" value=\"Add to Cart\"/></td></tr>";
}

echo "</table>";

echo "</body>"

?>
