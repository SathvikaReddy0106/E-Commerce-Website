<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        $new_quantity = $cart_item['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$new_quantity, $user_id, $product_id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
    }
}

// Handle Remove
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
}

// Handle Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = max(1, (int)$_POST['quantity']); // Minimum 1
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$quantity, $user_id, $product_id]);
}

// Fetch cart items
$stmt = $conn->prepare("SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_cost = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f1f1f1;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ccc;
        }
        .cart-item img {
            width: 100px;
            height: auto;
            border-radius: 5px;
            margin-right: 20px;
        }
        .item-details {
            flex: 1;
        }
        .item-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .item-price {
            color: #555;
            margin-bottom: 8px;
        }
        .item-actions {
            display: flex;
            align-items: center;
        }
        .quantity {
            width: 60px;
            padding: 5px;
            margin-right: 10px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 14px;
            margin-left: 8px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .cart-actions {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .cart-actions a {
            background-color: #28a745;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .cart-actions a:hover {
            background-color: #218838;
        }
        .total-cost {
            text-align: center;
            margin-top: 20px;
            font-size: 22px;
            font-weight: bold;
        }
        .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Cart</h2>
        <?php if (empty($cart_items)) : ?>
            <p class="empty-cart">Your cart is empty.</p>
        <?php else: ?>
            <?php foreach ($cart_items as $item): 
                $subtotal = $item['price'] * $item['quantity'];
                $total_cost += $subtotal;
            ?>
                <div class="cart-item">
                    <img src="../images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    <div class="item-details">
                        <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="item-price">$<?= number_format($item['price'], 2) ?> x <?= $item['quantity'] ?> = <strong>$<?= number_format($subtotal, 2) ?></strong></div>
                    </div>
                    <div class="item-actions">
                        <form method="POST" style="display: flex; align-items: center;">
                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" class="quantity" min="1">
                            <button type="submit" name="update_quantity">Update</button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                            <button type="submit" name="remove_from_cart">Remove</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="total-cost">
                Total: $<?= number_format($total_cost, 2) ?>
            </div>
        <?php endif; ?>
        <div class="cart-actions">
            <a href="../index.php">← Continue Shopping</a>
            <?php if (!empty($cart_items)): ?>
                <a href="checkout.php">Proceed to Checkout →</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
