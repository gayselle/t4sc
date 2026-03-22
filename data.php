<?php
/**
 * Data Loading and Utility Functions
 *
 * This file loads user-specific courses and tasks from the database and provides
 * utility functions for filtering and searching the data arrays. It also includes
 * authentication helpers and demo user fallbacks.
 *
 * Global Variables:
 * - $courses: Array of user's courses with id, name, description
 * - $tasks: Array of user's tasks with id, name, course_id, deadline, priority, status, description
 * - $demoUser: Fallback user data for display purposes
 */

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
    /**
     * Find a course by its ID in the courses array
     *
     * @param array $courses Array of course objects
     * @param int $id Course ID to search for
     * @return array|null Course object if found, null otherwise
     */
    foreach ($courses as $course) {
        if ($course['id'] === $id) {
            return $course;
        }
    }
    return null;
}

function tasks_for_course($tasks, $courseId) {
    /**
     * Filter tasks by course ID
     *
     * @param array $tasks Array of task objects
     * @param int $courseId Course ID to filter by
     * @return array Array of tasks belonging to the specified course
     */
    return array_values(array_filter($tasks, function ($task) use ($courseId) {
        return $task['course_id'] === $courseId;
    }));
}

function tasks_by_status($tasks, $status) {
    /**
     * Filter tasks by completion status
     *
     * @param array $tasks Array of task objects
     * @param string $status Status to filter by ('Completed' or 'Not Completed')
     * @return array Array of tasks with the specified status
     */
    return array_values(array_filter($tasks, function ($task) use ($status) {
        return $task['status'] === $status;
    }));
}

function tasks_due_today($tasks, $today) {
    /**
     * Filter tasks that are due today
     *
     * @param array $tasks Array of task objects
     * @param string $today Today's date in YYYY-MM-DD format
     * @return array Array of tasks with deadline matching today
     */
    return array_values(array_filter($tasks, function ($task) use ($today) {
        return $task['deadline'] === $today;
    }));
}

function require_login() {
    /**
     * Require user authentication - redirect to login if not logged in
     *
     * Checks if user_id is set in session. If not, redirects to index.php
     * and terminates script execution.
     */
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
}
