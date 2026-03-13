<?php
require_once __DIR__ . '/partials.php';
render_head('Welcome to T4SC');
?>

<div class="login-shell">
  <main class="login-card">
    <h1>Welcome to T4SC</h1>
    <p class="login-subtitle">
      Stay on top of tasks, classes, and deadlines with a focused workspace designed for students.
    </p>

    <form class="login-form" method="post" action="home.php">
      <div class="login-field">
        <label class="login-label">Username</label>
        <input id="username" name="username" type="text" placeholder="Enter your username" required>
      </div>

      <div class="login-field">
        <label class="login-label">Password</label>
        <input id="password" name="password" type="password" placeholder="Enter your password" required >
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
