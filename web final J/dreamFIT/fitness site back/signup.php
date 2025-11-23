<?php
// signup.php — Create new DreamFIT users

session_start();
require __DIR__ . '/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm'] ?? '');

    if ($email === '' || $password === '' || $confirm === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $existing = $stmt->fetch();

        if ($existing) {
            $error = 'An account with this email already exists.';
        } else {
            // Create user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                'INSERT INTO users (email, password_hash) VALUES (?, ?)'
            );

            try {
                $stmt->execute([$email, $hash]);

                // ✅ After signup, go back to login with a flag
                header('Location: index.php?registered=1');
                exit;
            } catch (PDOException $e) {
                $error = 'Could not create account. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>DreamFIT — Sign up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="page-wrap">
  <main class="card" aria-labelledby="signup-title">
    <h1 id="signup-title">Create your DreamFIT account</h1>
    <p class="subtitle">
      This account is used only to access the fitness website (frontend).
    </p>

    <?php if ($error): ?>
      <div class="alert error" role="alert">
        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <form method="post" class="form">
      <label class="field">
        <span>Email</span>
        <input type="email" name="email" required autocomplete="email"
               value="<?php echo isset($email) ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : ''; ?>">
      </label>

      <label class="field">
        <span>Password</span>
        <input type="password" name="password" required autocomplete="new-password">
      </label>

      <label class="field">
        <span>Confirm password</span>
        <input type="password" name="confirm" required autocomplete="new-password">
      </label>

      <button class="btn primary" type="submit">Create account</button>
    </form>

    <p class="alt">
      Already have an account?
      <a href="index.php">Sign in instead</a>
    </p>
  </main>
</div>
</body>
</html>
