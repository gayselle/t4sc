<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';

render_head('New Task');
render_topbar();
render_shell_open();
render_sidebar('', $courses);
render_sidebar_toggle();
?>
<main class="main">
  <a class="crumb" href="/home.php">&times; Cancel</a>
  <h2>New Task</h2>

  <section class="panel form-panel">
    <form class="form-grid">
      <label class="labelled">
        Task Name
        <input type="text" placeholder="Task Name">
      </label>

      <div class="form-row">
        <label class="labelled">
          Course
          <select>
            <?php foreach ($courses as $course): ?>
              <option> <?php echo $course['name']; ?> </option>
            <?php endforeach; ?>
          </select>
        </label>
        
        <label class="labelled">
          Status
          <select>
            <option>Not Completed</option>
            <option>Completed</option>
          </select>
        </label>
      </div>

      <div class="form-row">
        <label class="labelled">
          Deadline
          <input type="date" placeholder="mm-dd-yyyy">
        </label>
        <label class="labelled">
          Priority
          <select>
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
          </select>
        </label>
      </div>

      <label class="labelled">
        Task Description
        <textarea placeholder="Task Description"></textarea>
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
