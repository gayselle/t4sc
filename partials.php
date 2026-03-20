<?php
//render recurring functions so that we don't have to write and edit those everytime for each of the pages
function render_head($title = 'T4SC') {
    echo "<!doctype html>\n";
    echo "<html lang='en;>\n";
    echo "<head>\n";
    echo "  <meta charset='utf-8'>\n";
    echo "  <meta name='viewport' content='width=device-width, initial-scale=1'>\n";
    echo "  <title>" . $title . "</title>\n";
    echo "  <link rel='stylesheet' href='assets/style.css?v=2'>\n";
    echo "</head>\n";
    echo "<body>\n";
}

function render_topbar() {
    echo "<header class='topbar'>\n";
    echo "  <div class='logo'>LOGO</div>\n";
    echo "  <div class='search'>\n";
    echo "    <input type='search' placeholder='Search Tasks...'>\n";
    echo "  </div>\n";
    echo "  <nav class='top-actions'>\n";
    echo "    <div class='ghost'>Profile</div>\n";
    echo "    <div class='ghost'>Settings</div>\n";
    echo "  </nav>\n";
    echo "</header>\n";
}

function render_sidebar($active = 'home', $courses = []) {
    $navItems = [
        'home' => ['label' => 'Home', 'href' => 'home.php'],
        'not-completed' => ['label' => 'Not Completed', 'href' => 'not-completed.php'],
        'completed' => ['label' => 'Completed', 'href' => 'completed.php'],
    ];

    echo "<aside class='sidebar'>\n";
    echo "  <div class='sidebar-block'>\n";
    echo "    <p class='sidebar-label'>Dashboard</p>\n";
    echo "    <div class='sidebar-new'>\n";
    echo "      <button class='solid icon sidebar-new-toggle' type='button'><span>+</span> New</button>\n";
    echo "      <div class='sidebar-new-menu' data-open='false'>\n";
    echo "        <a href='course-new.php'>New Course</a>\n";
    echo "        <a href='task-new.php'>New Task</a>\n";
    echo "      </div>\n";
    echo "    </div>\n";

    foreach ($navItems as $key => $item) {
        $isActive = $active === $key ? 'active' : '';
        echo "    <a class='nav-link $isActive' href='{$item['href']}'>{$item['label']}</a>\n";
    }

    echo "  </div>\n";
    echo "  <div class='sidebar-block'>\n";
    echo "    <p class='sidebar-label'>Courses</p>\n";

    foreach ($courses as $course) {
        $name = $course['name'];
        echo "    <a class='pill' href='course.php?id={$course['id']}'>$name</a>\n";
    }

    echo "  </div>\n";
    echo "  <a href='logout.php' class='sidebar-logout'>Log Out</a>\n";
    echo "</aside>\n";
}

function render_shell_open() {
    echo "<div class='app-shell'>\n";
}

function render_shell_close() {
    echo "</div>\n";
}

function render_sidebar_toggle() {
    echo "<script>\n";
    echo "  var toggle = document.querySelector('.sidebar-new-toggle');\n";
    echo "  var menu = document.querySelector('.sidebar-new-menu');\n";
    echo "  if (toggle && menu) {\n";
    echo "    toggle.addEventListener('click', function () {\n";
    echo "      var currentlyOpen = menu.getAttribute('data-open') === 'true';\n";
    echo "      menu.setAttribute('data-open', currentlyOpen ? 'false' : 'true');\n";
    echo "    });\n";
    echo "    document.addEventListener('click', function (event) {\n";
    echo "      var clickedToggle = event.target === toggle;\n";
    echo "      var clickedInsideMenu = menu.contains(event.target);\n";
    echo "      if (!clickedToggle && !clickedInsideMenu) {\n";
    echo "        menu.setAttribute('data-open', 'false');\n";
    echo "      }\n";
    echo "    });\n";
    echo "  }\n";
    echo "</script>\n";
}

function render_task_row($task, $courseName = '') {
    $name = $task['name'];
    $deadline = $task['deadline'];
    $priority = $task['priority'];
    $status = $task['status'];
    $course = $courseName;
    $taskId = (int) $task['id'];

    $priorityClass = '';
    if ($priority === 'Low') {
        $priorityClass = ' priority-low';
    } elseif ($priority === 'Medium') {
        $priorityClass = ' priority-medium';
    } elseif ($priority === 'High') {
        $priorityClass = ' priority-high';
    }

    $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'home.php', ENT_QUOTES);

    echo "<div class='task-row'>\n";
    echo "  <div class='chip' style='text-align:left;'><a href='task.php?id=$taskId'>" . htmlspecialchars($name) . "</a></div>\n";
    if ($courseName !== '') {
        echo "  <div class='chip muted'>" . htmlspecialchars($course) . "</div>\n";
    } else {
        echo "  <div class='chip muted'>" . htmlspecialchars($course) . "</div>\n";
    }
    echo "  <div class='chip muted'>" . htmlspecialchars($deadline) . "</div>\n";
    echo "  <div class='chip$priorityClass'>" . htmlspecialchars($priority) . "</div>\n";
    $checked = $status === 'Completed' ? "checked='checked'" : '';
    echo "  <div class='chip task-status-toggle'>\n";
    echo "    <form method='post' action='task-toggle.php' class='task-status-form'>\n";
    echo "      <input type='hidden' name='task_id' value='$taskId'>\n";
    echo "      <input type='hidden' name='redirect' value=\"$currentUrl\">\n";
    echo "      <input type='checkbox' name='completed' value='1' $checked onchange='this.form.submit()'>\n";
    echo "      <span>" . htmlspecialchars($status) . "</span>\n";
    echo "    </form>\n";
    echo "  </div>\n";
    echo "</div>\n";
}
?>
