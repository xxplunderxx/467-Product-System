<html>
    <head>
        <title>Home Page</title>
    <style>
        h2{ text-align: center; }
        p { text-align: center; }
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
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
        }
        li {
            float: left;
        }
        li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        li a:hover:not(.active) {
  background-color: #111;
}
.active {
  background-color: #04AA6D;
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
        <div class="centered"> Da Store</div>
    </div>
    </head>
    <body>
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="customer.php">Secure Shopping</a></li>
            <li><a href="worker.php">Associates</a></li>
            <li style="float:right"><a class="active" href="about.html">About</a></li>
        </ul>
    </body>
</html>