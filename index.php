<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === '' || $password === '') {
    $errors[] = 'Please enter your username and password.';
  } else {
    $stmt = $pdo->prepare('SELECT user_id, user_name, user_password FROM user WHERE user_name = :username LIMIT 1');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['user_password'])) {
      $errors[] = 'Invalid username or password.';
    } else {
      $_SESSION['user_id'] = (int) $user['user_id'];
      $_SESSION['user_name'] = $user['user_name'];
      header('Location: home.php');
      exit;
    }
  }
}

render_head('Welcome to T4SC');
?>

<div class="login-shell">
  <main class="login-card">
    <h1>Welcome to T4SC</h1>
    <p class="login-subtitle">
      Stay on top of tasks, classes, and deadlines with a focused workspace designed for students.
    </p>

    <?php if (!empty($errors)): ?>
      <p style="margin-bottom:16px; color:#b91c1c; text-align:left; font-size:14px;">
        <?php echo htmlspecialchars(implode(' ', $errors)); ?>
      </p>
    <?php endif; ?>

    <form class="login-form" method="post" action="index.php">
      <div class="login-field">
        <label class="login-label">Username</label>
        <input
          id="username"
          name="username"
          type="text"
          placeholder="Enter your username"
          value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>"
          required
        >
      </div>

      <div class="login-field">
        <label class="login-label">Password</label>
        <input id="password" name="password" type="password" placeholder="Enter your password" required>
      </div>

      <button type="submit" class="login-submit">
        Log In
      </button>
    </form>

    <p class="login-footer">
      New here?
      <a class="signup-link" href="create-account.php">Create an account</a>
    </p>
  </main>
</div>
