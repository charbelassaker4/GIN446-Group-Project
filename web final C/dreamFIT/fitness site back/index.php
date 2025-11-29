<?php
// index.php — DreamFIT backend login (auth only)

session_start();
require __DIR__ . '/db.php';

$error = '';

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Please enter email and password.';
    } else {
        // Look up user in DB
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // ✅ Login success
            $_SESSION['user_email'] = $user['email'];

            // 👉 Send user to FRONTEND website
            header('Location: ../fitness site front/index.html');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>DreamFIT — Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="page-wrap">
  <main class="card" aria-labelledby="login-title">
    <h1 id="login-title">DreamFIT — Backend Login</h1>
    <p class="subtitle">
      Access the fitness site by signing in with your account.
    </p>

    <?php if ($error): ?>
      <div class="alert error" role="alert">
        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php elseif (isset($_GET['registered'])): ?>
      <div class="alert success" role="status">
        Account created successfully. You can now sign in.
      </div>
    <?php endif; ?>

    <form method="post" class="form">
      <label class="field">
        <span>Email</span>
        <input type="email" name="email" required autocomplete="email">
      </label>

      <label class="field">
        <span>Password</span>
        <input type="password" name="password" required autocomplete="current-password">
      </label>

      <button class="btn primary" type="submit">Sign in</button>
    </form>

    <p class="alt">
      Don’t have an account?
      <a href="signup.php">Sign up instead</a>
    </p>

    <p class="alt small">
      Frontend only (no login):<br>
      <a href="../fitness site front/index.html">Open DreamFIT frontend</a>
    </p>
  </main>
</div>
</body>
</html>
