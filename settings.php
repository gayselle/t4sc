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
<main class="main">
  <h2>Settings</h2>

  <section class="panel form-panel" style="margin-top: 24px;">
    <h3>Account</h3>
    <div id="display-name-section" style="margin-top: 8px; margin-bottom: 10px;">

      <div id="display-name-read" style="<?php echo $isEditMode ? 'display:none;' : ''; ?> margin-bottom:10px; color: var(--muted); font-size: 14.4px;">
        Signed in as <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
        <button id="change-display-name-btn" type="button" style="margin-left: 8px; color:#5b21b6; border: none;  cursor:pointer; padding:2px 10px; font-size:12px; text-decoration: underline;">&#9998; Edit Name</button>  <br />
      </div>
      
      <?php if ($updatedFlag): ?>
        <div
          role="status"
          style="display:inline-flex; align-items:center; gap:6px; margin:0 0 12px; padding:6px 12px; border-radius:999px; background:#dcfce7; color:#166534; font-size:13px; font-weight:600; border:1px solid #86efac;">
          <span style="font-size:14px; line-height:1;" aria-hidden="true">&#10003;</span>
          Saved!
        </div>
      <?php elseif ($success !== ''): ?>
        <div
          role="status"
          style="display:inline-flex; align-items:center; gap:6px; margin:0 0 12px; padding:6px 12px; border-radius:999px; background:#e0e7ff; color:#3730a3; font-size:13px; font-weight:600; border:1px solid #a5b4fc;">
          <span style="font-size:14px; line-height:1;" aria-hidden="true">&#9432;</span>
          <?php echo htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div style="margin:0 0 12px;">
          <div
            role="alert"
            style="display:inline-flex; align-items:center; gap:6px; margin:0 0 6px 0; padding:6px 12px; border-radius:999px; background:#fee2e2; color:#991b1b; font-size:13px; font-weight:600; border:1px solid #fecaca;">
            <span style="font-size:14px; line-height:1;" aria-hidden="true">&#10007;</span>
            Error! <?php echo htmlspecialchars(implode(' ', $errors)); ?>
          </div>
        </div>
      <?php endif; ?>

      <form id="display-name-form" method="post" action="settings.php" style="<?php echo $isEditMode ? '' : 'display:none;'; ?>">
        <label class="labelled">
          Display Name
          <input
            id="display-name-input"
            type="text"
            name="user_name"
            placeholder="Your display name"
            value="<?php echo htmlspecialchars($currentUserName); ?>"
            required
          >
        </label>
      </form>

      <div style="margin-top: 14px; display:flex; flex-direction:row; flex-wrap:wrap; align-items:center; justify-content:flex-start; gap:12px;">
        <button id="save-display-name-btn" class="solid" type="submit" form="display-name-form" style="<?php echo $isEditMode ? '' : 'display:none;'; ?>">Save Changes</button>
        <a href="logout.php" class="logout-btn">Log Out</a>
      </div>
    </div>
  </section>
</main>
<script>
  (function () {
    var readWrap = document.getElementById('display-name-read');
    var form = document.getElementById('display-name-form');
    var changeBtn = document.getElementById('change-display-name-btn');
    var input = document.getElementById('display-name-input');
    var saveBtn = document.getElementById('save-display-name-btn');
    var originalName = <?php echo json_encode($currentUserName); ?>;

    if (!form || !input || !saveBtn) {
      return;
    }

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
