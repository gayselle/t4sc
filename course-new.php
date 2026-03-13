<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';

render_head('New Course');
render_topbar();
render_shell_open();
render_sidebar('', $courses);
render_sidebar_toggle();
?>
<main class="main">
  <a class="crumb" href="/home.php">&times; Cancel</a>
  <h2>New Course</h2>

  <section class="panel form-panel">
    <form class="form-grid">
      <label class="labelled">
        Course Name
        <input type="text" placeholder="Course Name">
      </label>
      <label class="labelled">
        Course Description
        <textarea placeholder="Course Description"></textarea>
      </label>
      <div class="form-actions">
        <button class="solid" type="submit">Save</button>
      </div>
    </form>
  </section>
</main>
<?php
render_shell_close();
?>
