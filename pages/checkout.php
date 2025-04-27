<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Fetch cart items
$stmt = $conn->prepare("SELECT c.*, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

// Calculate total
$total_cost = 0;
foreach ($cart_items as $item) {
    $total_cost += $item['price'] * $item['quantity'];
}

// Handle order placing
if (isset($_POST['place_order'])) {
    // In real app: save order to database, clear cart, etc.
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $_SESSION['order_success'] = true;
    header("Location: order_success.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .order-summary {
            margin-bottom: 30px;
        }
        .order-summary div {
            margin: 10px 0;
            font-size: 18px;
            color: #555;
        }
        .total {
            font-size: 22px;
            font-weight: bold;
            color: #000;
            margin-top: 20px;
        }
        form {
            text-align: center;
        }
        button {
            background: #28a745;
            padding: 12px 30px;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #218838;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            transition: 0.3s;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Checkout</h2>
        <div class="order-summary">
            <?php foreach ($cart_items as $item): ?>
                <div><?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?> = $<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
            <?php endforeach; ?>
            <div class="total">Total: $<?= number_format($total_cost, 2) ?></div>
        </div>
        <form method="POST">
            <button type="submit" name="place_order">Place Order</button>
        </form>
        <a href="cart.php" class="back-link">← Back to Cart</a>
    </div>
</body>
</html>
