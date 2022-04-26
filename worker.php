<html lang="en">
    <head>
    <title>Orders Page for Workers</title>
        <style>
            h1{ text-align: center; }
            h3{ text-align: center; }
            ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
            border: 1px solid #555;
        }
        li {
            float: left;
        }
        li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 8px;
            text-decoration: none;
        }
        li a:hover:not(.active) {
            background-color: #111;
            color: white;
        }
        body{
        background-image: linear-gradient(#304352, #d7d2cc);
    }
    .container {
        position: relative;
        text-align: center;
        color: black;
        font-family: verdana;
        font-size: 300%;
    }
    .centered {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
        </style>
        <div class="container">
        <img src="https://imgur.com/Ugs7BAU.png" style="width:100%"></img>
        <div class="centered"> Name</div>
    </div>
    </head>
    <body>
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="order_product.php">Order Inspection</a></li>
            <li><a href="pending.php">Pending Orders</a></li>
            <li><a href="inbound.php">Log Inbound Products</a></li>
            <li><a href="outbound.php">View Outbound Products</a></li>
            <li><a href="completed.php">Completed Orders</a></li>
        </ul>
        <h3>We apreciate our Associates</h3>
    </body>
</html>