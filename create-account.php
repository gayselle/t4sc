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

<div class="login-shell">
  <main class="login-card">
    <h1>Create your account</h1>
    <p class="login-subtitle">
      Set up your T4SC workspace to keep track of classes, tasks, and deadlines in one place.
    </p>

    <?php if (!empty($errors)): ?>
      <p style="margin-bottom:16px; color:#b91c1c; text-align:left; font-size:14px;">
        <?php echo htmlspecialchars(implode(' ', $errors)); ?>
      </p>
    <?php endif; ?>

    <form class="login-form" method="post" action="create-account.php">
      <div class="login-field">
        <label class="login-label" for="signup-username">Username</label>
        <input
          id="signup-username"
          name="username"
          type="text"
          placeholder="Choose a username"
          value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>"
          required
        >
      </div>

      <div class="login-field">
        <label class="login-label" for="signup-email">Email</label>
        <input
          id="signup-email"
          name="email"
          type="email"
          placeholder="you@example.com"
          value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
          required
        >
      </div>

      <div class="login-field">
        <label class="login-label" for="signup-password">Password</label>
        <input
          id="signup-password"
          name="password"
          type="password"
          placeholder="Create a password"
          required
        >
      </div>

      <button type="submit" class="login-submit">
        Create Account
      </button>
    </form>

    <p class="login-footer">
      Already have an account?
      <a class="signup-link" href="index.php">Log in</a>
    </p>
  </main>
</div>

