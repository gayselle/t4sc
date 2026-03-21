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

<div class="min-vh-100 d-flex align-items-center justify-content-center login-bg py-4">
  <main class="card shadow p-4 p-md-5" style="width:min(480px,100%)">
    <h1 class="h3 text-center fw-bold mb-2">Welcome to T4SC</h1>
    <p class="text-muted text-center mb-4" style="font-size:14px;">
      Stay on top of tasks, classes, and deadlines with a focused workspace designed for students.
    </p>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
    <?php endif; ?>

    <form method="post" action="index.php">
      <div class="mb-3">
        <label class="form-label fw-medium" for="username">Username</label>
        <input id="username" name="username" type="text" class="form-control" placeholder="Enter your username"
          value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-medium" for="password">Password</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-2 mt-1">Log In</button>
    </form>

    <p class="text-center text-muted mt-4" style="font-size:14px;">
      New here? <a class="text-primary fw-medium" href="create-account.php">Create an account</a>
    </p>
  </main>
</div>
