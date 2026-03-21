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

  <section class="panel form-panel" style="margin-top: 24px;">
    <h3>Account</h3>
    <p style="margin-top: 8px; margin-bottom: 20px; color: var(--muted); font-size: 14.4px;">
      Signed in as <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
    </p>
    <a href="logout.php" class="logout-btn">Log Out</a>
  </section>
</main>
<?php
render_shell_close();
?>
