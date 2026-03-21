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

<div class="min-vh-100 d-flex align-items-center justify-content-center login-bg py-4">
  <main class="card shadow p-4 p-md-5" style="width:min(480px,100%)">
    <h1 class="h3 text-center fw-bold mb-2">Create your account</h1>
    <p class="text-muted text-center mb-4" style="font-size:14px;">
      Set up your T4SC workspace to keep track of classes, tasks, and deadlines in one place.
    </p>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
    <?php endif; ?>

    <form method="post" action="create-account.php">
      <div class="mb-3">
        <label class="form-label fw-medium" for="signup-username">Username</label>
        <input id="signup-username" name="username" type="text" class="form-control" placeholder="Choose a username"
          value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-medium" for="signup-email">Email</label>
        <input id="signup-email" name="email" type="email" class="form-control" placeholder="you@example.com"
          value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-medium" for="signup-password">Password</label>
        <input id="signup-password" name="password" type="password" class="form-control" placeholder="Create a password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-2 mt-1">Create Account</button>
    </form>

    <p class="text-center text-muted mt-4" style="font-size:14px;">
      Already have an account? <a class="text-primary fw-medium" href="index.php">Log in</a>
    </p>
  </main>
</div>

