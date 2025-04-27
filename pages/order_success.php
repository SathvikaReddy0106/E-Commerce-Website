<?php
session_start();
if (!isset($_SESSION['order_success'])) {
    header("Location: cart.php");
    exit();
}
unset($_SESSION['order_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .success-message {
            max-width: 500px;
            margin: 100px auto;
            background: #ffffff;
            padding: 30px;
            text-align: center;
            border: 1px solid #d4d4d4;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #28a745;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            color: #555;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="success-message">
        <h1>Success!</h1>
        <p>Your order has been placed successfully.</p>
        <a href="../index.php">Back to Shop</a>
    </div>
</body>
</html>
