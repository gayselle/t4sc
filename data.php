<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUserId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;

// fallback demo user; name may be overridden by session in home.php
$demoUser = [
    'name' => 'Person',
    'greeting' => 'Good day',
];

// load courses from the database table `course` for the current user
$courses = [];

if ($currentUserId > 0) {
    try {
        $stmt = $pdo->prepare(
            'SELECT course_id, course_name, course_desc FROM course WHERE user_id = :user_id ORDER BY course_name'
        );
        $stmt->execute([':user_id' => $currentUserId]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = [
                'id' => (int) $row['course_id'],
                'name' => $row['course_name'],
                'description' => $row['course_desc'],
            ];
        }
    } catch (PDOException $e) {
        // if the query fails, keep $courses as an empty array
    }
}

// load tasks from the database table `task` for the current user
$tasks = [];

if ($currentUserId > 0) {
    try {
        $stmt = $pdo->prepare(
            'SELECT task_id, task_name, task_desc, task_due, task_priority, task_status, course_id
             FROM task
             WHERE user_id = :user_id'
        );
        $stmt->execute([':user_id' => $currentUserId]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tasks[] = [
                'id' => (int) $row['task_id'],
                'name' => $row['task_name'],
                'course_id' => (int) $row['course_id'],
                'deadline' => $row['task_due'],
                'priority' => $row['task_priority'],
                'status' => $row['task_status'],
                'description' => $row['task_desc'],
            ];
        }
    } catch (PDOException $e) {
        // if the query fails, keep $tasks as an empty array
    }
}

function find_course($courses, $id) {
    foreach ($courses as $course) {
        if ($course['id'] === $id) {
            return $course;
        }
    }
    return null;
}

function tasks_for_course($tasks, $courseId) {
    return array_values(array_filter($tasks, function ($task) use ($courseId) {
        return $task['course_id'] === $courseId;
    }));
}

function tasks_by_status($tasks, $status) {
    return array_values(array_filter($tasks, function ($task) use ($status) {
        return $task['status'] === $status;
    }));
}

function tasks_due_today($tasks, $today) {
    return array_values(array_filter($tasks, function ($task) use ($today) {
        return $task['deadline'] === $today;
    }));
}
