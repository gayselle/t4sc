<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';
require_login();

render_head('Settings | T4SC');
render_topbar();
render_shell_open();
render_sidebar('settings', $courses);
render_sidebar_toggle();
?>
<main class="main">
  <h2>Settings</h2>

  <div class="card mt-4" style="max-width: 520px;">
    <div class="card-body">
      <h5 class="card-title">Account</h5>
      <p class="text-muted mb-3">
        Signed in as <strong class="text-dark"><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
      </p>
      <a href="logout.php" class="btn btn-outline-danger btn-sm">Log Out</a>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>
