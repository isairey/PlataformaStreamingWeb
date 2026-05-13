<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);

    $db = new Database();
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $db->getConnection()->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if ($password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            redirect('dashboard.php');
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
}

if (isLoggedIn()) {
    redirect('dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login | Cinemax</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      background: url('5d692b0375dbc61f7cb2942d069327a5.jpg') no-repeat center center fixed;
      background-size: cover;
      position: relative;
    }

    body::after {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 32, 64, 0.7);
      z-index: 0;
    }

    .login-box {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 1;
      width: 90%;
      max-width: 400px;
      color: white;
    }

    .login-box .logo {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 25px;
    }

    .login-box h2 {
      font-size: 28px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 10px;
    }

    .login-box p {
      text-align: center;
      margin-bottom: 30px;
      font-size: 14px;
      color: #ccc;
    }

    .input-box {
      position: relative;
      margin-bottom: 20px;
    }

    .input-box input {
      width: 100%;
      padding: 12px 12px 12px 40px;
      border: none;
      border-radius: 5px;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      font-size: 14px;
      outline: none;
    }

    .input-box i {
      position: absolute;
      top: 12px;
      left: 12px;
      color: #ccc;
    }

    .options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 13px;
      color: #ccc;
      margin-bottom: 20px;
    }

    .options input[type="checkbox"] {
      accent-color: white;
    }

    .login-box button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 5px;
      background-color: white;
      color: #003366;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-box button:hover {
      background-color: #ddd;
    }

    .logo img {
      height: 40px;
    }

    .error {
      background-color: rgba(255, 0, 0, 0.2);
      color: #ff8080;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 4px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <div class="logo">
      <img src="https://upload.wikimedia.org/wikipedia/en/thumb/e/e3/Glion_Institute_of_Higher_Education_logo.svg/1200px-Glion_Institute_of_Higher_Education_logo.svg.png" alt="Logo" />
      <img src="https://upload.wikimedia.org/wikipedia/commons/8/8b/Globe_icon_white.svg" alt="Globe" />
    </div>
    <h2>Welcome Back!</h2>
    <p>Please login with your admin credentials</p>

    <?php if (isset($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="input-box">
        <i class="bi bi-person"></i>
        <input type="text" name="username" placeholder="Username" required />
      </div>
      <div class="input-box">
        <i class="bi bi-lock"></i>
        <input type="password" name="password" placeholder="Password" required />
      </div>
      <div class="options">
        <label><input type="checkbox" /> Stay logged in</label>
        <a href="#" style="color: #ccc;">Forgot password?</a>
      </div>
      <button type="submit">SIGN IN</button>
    </form>
  </div>
</body>
</html>
