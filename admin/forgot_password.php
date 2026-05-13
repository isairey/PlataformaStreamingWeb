<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);

    $db = new Database();
    $sql = "SELECT * FROM admin WHERE email = ?";
    $stmt = $db->getConnection()->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        // You would normally send a reset link or token via email here
        $message = "If this email exists, a reset link has been sent.";
    } else {
        $message = "If this email exists, a reset link has been sent.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Forgot Password | Admin</title>
  <style>
  </style>
</head>
<body class="auth-background">
  <div class="auth-overlay"></div>
  <div class="auth-container">
    <h2>Forgot Password</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Enter your email" required>
      <button type="submit">Send Reset Link</button>
    </form>

    <?php if (isset($message)): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <a href="login.php">Back to Login</a>
  </div>
</body>
</html>
<style>

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    input[type="email"] {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 5px;
      margin-bottom: 20px;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      font-size: 14px;
      outline: none;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: white;
      color: #003366;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #eee;
    }

    .message {
      margin-top: 15px;
      background: rgba(255,255,255,0.1);
      padding: 10px;
      border-radius: 5px;
      color: #ddd;
      font-size: 14px;
      text-align: center;
    }

    a {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #ccc;
      text-decoration: none;
    }

    a:hover {
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Forgot Password</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Enter your email" required>
      <button type="submit">Send Reset Link</button>
    </form>

    <?php if (isset($message)): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <a href="login.php">Back to Login</a>
  </div>
</body>
</html>
