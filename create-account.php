<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $errors[] = 'Username is required.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }
    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO user (user_name, user_email, user_password) VALUES (:username, :email, :password_hash)'
        );

        try {
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $passwordHash,
            ]);

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Could not create account. Please try a different username or email.';
        }
    }
}

render_head('Create Account | T4SC');
?>

<div class="login-bg d-flex align-items-center justify-content-center py-5">
  <div class="card shadow-lg" style="width: min(480px, 100%)">
    <div class="card-body p-5 text-center">
      <h1 class="h3 mb-2">Create your account</h1>
      <p class="text-muted mb-4">
        Set up your T4SC workspace to keep track of classes, tasks, and deadlines in one place.
      </p>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger text-start py-2" role="alert">
          <?php echo htmlspecialchars(implode(' ', $errors)); ?>
        </div>
      <?php endif; ?>

      <form class="text-start" method="post" action="create-account.php">
        <div class="mb-3">
          <label class="form-label fw-medium" for="signup-username">Username</label>
          <input
            class="form-control"
            id="signup-username"
            name="username"
            type="text"
            placeholder="Choose a username"
            value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label fw-medium" for="signup-email">Email</label>
          <input
            class="form-control"
            id="signup-email"
            name="email"
            type="email"
            placeholder="you@example.com"
            value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label fw-medium" for="signup-password">Password</label>
          <input
            class="form-control"
            id="signup-password"
            name="password"
            type="password"
            placeholder="Create a password"
            required
          >
        </div>

        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 mt-1">
          Create Account
        </button>
      </form>

      <p class="mt-4 text-muted mb-0">
        Already have an account?
        <a class="fw-medium" href="index.php">Log in</a>
      </p>
    </div>
  </div>
</div>
