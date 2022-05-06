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

    // tell user if they are logged in without correct credentials
    if($_SESSION["login"][0] == "worker")
    {
        echo '<p>You were successfully logged in</p>';
    }
    elseif($_SESSION["login"][0] == "default")
    {
        echo '<p>ERROR invalid access contact an admin to be granted access to worker view</p>';
    }
    
    else {
        echo '<p>you are not logged in</p>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h1>Login Here</h1>
    <form action="http://students.cs.niu.edu/~z1892587/467-Product-System/login.php" method="POST">
        <input type="text" name="user_name" required> User Name
        <input type="password" name="user_password" required> Password
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>

<?php 
    if(isset($_POST["login"]))
    {
        $username = $_POST["user_name"];
        $password = $_POST["user_password"];    // user input password
        
        $sql = "SELECT User_name,password,status FROM User WHERE User_name=?";
        $prepared = $pdo2->prepare($sql);
        $prepared->execute(array($username));   // input user name
        $row = $prepared->fetch();

        // get values from the database
        $db_username = $row["User_name"];
        $hash = $row["password"];   // password from DB
        $status = $row["status"];

        // check encrypted values
        $pass_match = password_verify($password, $hash);
        // check if logged in successfully
        if($username == $db_username && $pass_match)
        {
            $_SESSION["login"][0] = $status;
            header("Location: http://students.cs.niu.edu/~z1892587/467-Product-System/worker.php");
            exit(); // redirect browser
        }
        else {
            echo "incorrect credentials";
        }
    }
?>
