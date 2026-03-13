<?php
//this is just a sample data so that i can see how it looks like
$demoUser = [
    'name' => 'Person',
    'greeting' => 'Good day',
];

$courses = [
    [
        'id' => 1,
        'name' => 'CompSci 36',
        'description' => 'Web Systems and Technology for full-stack development. A comprehensive exploration of the technologies used to design, build, and deploy modern web applications.',
    ],
    [
        'id' => 2,
        'name' => 'CompSci 38',
        'description' => 'Software Engineering for Software Development Life Cycle (SDLC) and project management. This course emphasizes the methodologies required to manage large-scale software projects within a team environment.',
    ],
    [
        'id' => 3,
        'name' => 'CS E1',
        'description' => 'Professional Elective for statistical computing. This elective introduces students to the power of data-driven decision-making. It bridges the gap between raw data and actionable intelligence using modern computational tools.',
    ],
    [
        'id' => 4,
        'name' => 'CompSci 32',
        'description' => 'Programming Langauges. This course dives into the "why" behind the syntax. It examines the formal properties, design goals, and implementation of various programming paradigms.',
    ],
];

$tasks = [
    [
        'id' => 101,
        'name' => 'Create a Profile Page',
        'course_id' => 1,
        'deadline' => '2026-03-14',
        'priority' => 'High',
        'status' => 'Completed',
        'description' => 'Build a simple personal webpage using HTML and CSS that looks good on both a phone and a laptop.',
    ],
    [
        'id' => 102,
        'name' => 'Fetch Data',
        'course_id' => 1,
        'deadline' => '2026-03-15',
        'priority' => 'Medium',
        'status' => 'Not Completed',
        'description' => 'Write a small script that pulls a random quote or weather update from a public website and displays it on your page.',
    ],
    [
        'id' => 103,
        'name' => 'Write a User Story',
        'course_id' => 2,
        'deadline' => '2026-03-16',
        'priority' => 'High',
        'status' => 'Completed',
        'description' => 'Pick an app you use (like Spotify) and write three "As a user, I want to..." sentences for its features.',
    ],
    [
        'id' => 104,
        'name' => 'Make a Chart',
        'course_id' => 3,
        'deadline' => '2026-03-13',
        'priority' => 'Low',
        'status' => 'Not Completed',
        'description' => 'Turn a small table of data into a clear bar graph or pie chart to show a specific trend.',
    ],
    [
        'id' => 105,
        'name' => 'Spot the difference',
        'course_id' => 4,
        'deadline' => '2026-03-13',
        'priority' => 'Medium',
        'status' => 'Not Completed',
        'description' => 'Compare how a "Loop" looks in two different languages and list two things that are different about the syntax.',
    ],
];

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
