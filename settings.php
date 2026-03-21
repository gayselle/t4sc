<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';
require_login();

$errors = [];
$success = '';
$isEditMode = false;

$currentUserId = (int) ($_SESSION['user_id'] ?? 0);
$currentUserName = isset($_SESSION['user_name']) ? (string) $_SESSION['user_name'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $newUserName = trim($_POST['user_name'] ?? '');
  $isEditMode = true;

  if ($newUserName === '') {
    $errors[] = 'Display name is required.';
  } elseif ($currentUserId <= 0) {
    $errors[] = 'You must be logged in.';
  } elseif ($newUserName === $currentUserName) {
    $success = 'Display name is unchanged.';
  } else {
    // Prevent duplicate usernames.
    $stmt = $pdo->prepare(
      'SELECT user_id FROM user WHERE user_name = :user_name AND user_id != :user_id LIMIT 1'
    );
    $stmt->execute([
      ':user_name' => $newUserName,
      ':user_id' => $currentUserId,
    ]);

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
      $errors[] = 'That display name is already taken.';
    } else {
      try {
        $updateStmt = $pdo->prepare(
          'UPDATE user SET user_name = :user_name WHERE user_id = :user_id'
        );
        $updateStmt->execute([
          ':user_name' => $newUserName,
          ':user_id' => $currentUserId,
        ]);

        // Keep the session in sync so UI updates immediately.
        $_SESSION['user_name'] = $newUserName;
        header('Location: settings.php?updated=1');
        exit;
      } catch (PDOException $e) {
        error_log($e->getMessage());
        $errors[] = 'Could not update your display name. Please try again.';
      }
    }
  }
}

$updatedFlag = isset($_GET['updated']) && (string) $_GET['updated'] === '1';
if ($updatedFlag) {
  $success = ''; // Shown via Saved! badge instead of duplicate text
}

if ($updatedFlag) {
  $isEditMode = false;
}

render_head('Settings | T4SC');
render_topbar();
render_shell_open();
render_sidebar('settings', $courses);
render_sidebar_toggle();
?>
<main class="flex-grow-1 p-4 p-md-5 main-content">
  <h2 class="mb-4">Settings</h2>

  <div class="card shadow-sm" style="max-width:560px;">
    <div class="card-body p-4">
      <h3 class="h5 mb-3">Account</h3>

      <div id="display-name-read" style="<?php echo $isEditMode ? 'display:none;' : ''; ?>" class="mb-3 text-muted">
        Signed in as <strong class="text-dark"><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
        <button id="change-display-name-btn" type="button" class="btn btn-link btn-sm p-0 ms-2 text-primary">&#9998; Edit Name</button>
      </div>

      <?php if ($updatedFlag): ?>
        <div class="alert alert-success py-2 d-inline-flex align-items-center gap-2 mb-3">&#10003; Saved!</div>
      <?php elseif ($success !== ''): ?>
        <div class="alert alert-info py-2 mb-3"><?php echo htmlspecialchars($success); ?></div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-3"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
      <?php endif; ?>

      <form id="display-name-form" method="post" action="settings.php" style="<?php echo $isEditMode ? '' : 'display:none;'; ?>">
        <div class="mb-3">
          <label class="form-label fw-medium" for="display-name-input">Display Name</label>
          <input id="display-name-input" type="text" class="form-control" name="user_name"
            placeholder="Your display name" value="<?php echo htmlspecialchars($currentUserName); ?>" required>
        </div>
      </form>

      <div class="d-flex gap-2 mt-3">
        <button id="save-display-name-btn" class="btn btn-primary" type="submit" form="display-name-form"
          style="<?php echo $isEditMode ? '' : 'display:none;'; ?>">Save Changes</button>
        <a href="logout.php" class="btn btn-outline-danger">Log Out</a>
      </div>
    </div>
  </div>
</main>
<script>
  (function () {
    var readWrap = document.getElementById('display-name-read');
    var form = document.getElementById('display-name-form');
    var changeBtn = document.getElementById('change-display-name-btn');
    var input = document.getElementById('display-name-input');
    var saveBtn = document.getElementById('save-display-name-btn');
    var originalName = <?php echo json_encode($currentUserName); ?>;

    if (!form || !input || !saveBtn) return;

    function updateSaveState() {
      var changed = input.value.trim() !== '' && input.value.trim() !== originalName;
      saveBtn.disabled = !changed;
      saveBtn.style.opacity = changed ? '1' : '0.65';
      saveBtn.style.cursor = changed ? 'pointer' : 'not-allowed';
    }

    if (changeBtn && readWrap) {
      changeBtn.addEventListener('click', function () {
        readWrap.style.display = 'none';
        form.style.display = '';
        saveBtn.style.display = '';
        input.focus();
        updateSaveState();
      });
    }

    updateSaveState();
    input.addEventListener('input', updateSaveState);
  })();
</script>
<?php
render_shell_close();
?>
