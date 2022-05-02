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
    <title>Register Page</title>
</head>
<body>
    <h1>Register Here</h1>
    <form action="http://students.cs.niu.edu/~z1892587/467-Product-System/register.php" method="POST">
        <input type="text" name="user_name" required> User Name
        <input type="password" name="user_password" minlength="8" required> Password
        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>

<?php 
    if(isset($_POST["register"]))
    {
        $username = $_POST["user_name"];
        $password = $_POST["user_password"];
        
        //make sure user does not already exist
        $sql = "SELECT * FROM User WHERE User_name=?";
        $prepared = $pdo2->prepare($sql);
        $prepared->execute(array($username));
        $prod = $prepared->fetch(); // prod will be set on exists

        if(isset($prod["status"]))  // account exists
        {
            echo "account already exists";
        }
        else    // acount does not exist
        {
            $sql = "INSERT INTO User(User_name,password,status) Values(?,?,'default')";
            $prepared = $pdo2->prepare($sql);
            $prepared->execute(array($username,$password));   // user name and password

            echo "successfully created user: " . $username;
        }
    }
?>