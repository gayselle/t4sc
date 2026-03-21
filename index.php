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

<div class="login-bg d-flex align-items-center justify-content-center py-5">
  <div class="card shadow-lg" style="width: min(480px, 100%)">
    <div class="card-body p-5 text-center">
      <h1 class="h3 mb-2">Welcome to T4SC</h1>
      <p class="text-muted mb-4">
        Stay on top of tasks, classes, and deadlines with a focused workspace designed for students.
      </p>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger text-start py-2" role="alert">
          <?php echo htmlspecialchars(implode(' ', $errors)); ?>
        </div>
      <?php endif; ?>

      <form class="text-start" method="post" action="index.php">
        <div class="mb-3">
          <label class="form-label fw-medium" for="username">Username</label>
          <input
            class="form-control"
            id="username"
            name="username"
            type="text"
            placeholder="Enter your username"
            value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label fw-medium" for="password">Password</label>
          <input class="form-control" id="password" name="password" type="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 mt-1">
          Log In
        </button>
      </form>

      <p class="mt-4 text-muted mb-0">
        New here?
        <a class="fw-medium" href="create-account.php">Create an account</a>
      </p>
    </div>
  </div>
</div>
