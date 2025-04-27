<?php
include('../includes/db.php');  // Include the database connection
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Successful login
        $_SESSION['user_id'] = $user['id']; // Store user ID in session
        header("Location: ../index.php"); // Redirect to the main page
        exit();
    } else {
        // Invalid login
        $error_message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #89f7fe, #66a6ff);
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .login-container {
        background-color: #ffffff;
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    h2 {
        color: #333;
        margin-bottom: 25px;
        font-size: 28px;
        font-weight: 700;
        text-align: center;
    }
    form {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 15px; /* Nice gap between inputs and button */
    }
    label {
        font-size: 1rem;
        color: #555;
        margin-bottom: 5px;
    }
    input[type="email"],
    input[type="password"],
    button {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
        box-sizing: border-box; /* very important to make width consistent */
    }
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #66a6ff;
        background-color: #fff;
        outline: none;
        box-shadow: 0 0 8px rgba(102, 166, 255, 0.6);
    }
    button {
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        color: white;
        font-size: 1.1rem;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    button:hover {
        background: linear-gradient(135deg, #0072ff, #00c6ff);
    }
    .error-message {
        color: #e74c3c;
        font-size: 0.95rem;
        text-align: center;
        margin-top: 15px;
        background: #ffe6e6;
        padding: 8px;
        border-radius: 6px;
    }
</style>


</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <p style="margin-top: 20px; font-size: 0.95rem; color: #555;">
        Don't have an account? 
        <a href="register.php" style="color: #0072ff; text-decoration: none; font-weight: bold;">Register here</a>
    </p>
</div>

</body>
</html>