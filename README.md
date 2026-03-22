# TASKS 4 SCHOOL

A comprehensive task management web application designed specifically for students to stay organized with their courses, assignments, and deadlines.

## Features

### User Management
- **Secure Authentication**: User registration and login with password hashing
- **Profile Management**: Update display name and account settings
- **Session Management**: Secure logout functionality

### Course Management
- **Create Courses**: Add new courses with name and description
- **Edit Courses**: Modify course details
- **Delete Courses**: Remove courses with confirmation
- **Course Overview**: View all tasks within a specific course

### Task Management
- **Create Tasks**: Add tasks with name, description, course assignment, deadline, priority, and status
- **Edit Tasks**: Modify existing task details
- **Delete Tasks**: Remove tasks with confirmation
- **Task Details**: Comprehensive view of task information
- **Status Toggle**: Quick completion status updates via checkboxes

### Dashboard & Organization
- **Personalized Dashboard**: Welcome message with task summaries
- **Due Today View**: Highlight tasks due on the current date
- **Overdue Alerts**: Visual indicators for past-due tasks
- **Completed Tasks**: Dedicated view for finished tasks
- **Pending Tasks**: View of all incomplete tasks

### Filtering & Sorting
- **Course Filtering**: Filter tasks by specific courses or view all
- **Status Filtering**: Filter by completed/not completed tasks
- **Priority Sorting**: Sort tasks by priority level (High → Medium → Low → None)
- **Date Sorting**: Sort tasks by deadline (chronological)

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: Bootstrap 5.3.3, Vanilla JavaScript
- **Styling**: Custom CSS with Inter/Poppins fonts
- **Session Management**: PHP Sessions
- **Security**: Password hashing with PASSWORD_DEFAULT (bcrypt)

## Installation & Setup

### Prerequisites
- XAMPP (or similar Apache/MySQL/PHP stack)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web browser

### Database Setup
1. Start XAMPP and ensure Apache and MySQL services are running
2. Create a new database in phpMyAdmin (or MySQL command line)
3. Create the following tables:

```sql
-- Users table
CREATE TABLE user (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(255) NOT NULL UNIQUE,
    user_email VARCHAR(255) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL
);

-- Courses table
CREATE TABLE course (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_name VARCHAR(255) NOT NULL,
    course_desc TEXT,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

-- Tasks table
CREATE TABLE task (
    task_id INT PRIMARY KEY AUTO_INCREMENT,
    task_name VARCHAR(255) NOT NULL,
    task_desc TEXT,
    task_due DATE,
    task_priority ENUM('None', 'Low', 'Medium', 'High') DEFAULT 'None',
    task_status ENUM('Not Completed', 'Completed') DEFAULT 'Not Completed',
    course_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (course_id) REFERENCES course(course_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);
```

### Configuration
1. Clone or download the project files to your XAMPP htdocs directory
2. Create a `db.env` file in the project root with your database credentials:

```
DB_HOST=localhost
DB_PORT=3306
DB_NAME=your_database_name
DB_USER=your_db_username
DB_PASSWORD=your_db_password
```

3. Ensure the web server can access the project directory

### Running the Application
1. Start XAMPP (Apache and MySQL)
2. Open your browser and navigate to `http://localhost/your-project-directory`
3. Register a new account or login with existing credentials

## Usage Guide

### Getting Started
1. **Register**: Create a new account with username, email, and password
2. **Login**: Access your account with credentials
3. **Setup Courses**: Create your courses first before adding tasks

### Managing Courses
1. Click the "+ New" dropdown in the sidebar
2. Select "New Course"
3. Fill in course name (required) and description (optional)
4. Save to create the course

### Managing Tasks
1. From the "+ New" dropdown, select "New Task"
2. Fill in all required fields:
   - Task Name (required)
   - Course selection (required)
   - Due Date (required)
   - Description (optional)
   - Priority (None/Low/Medium/High)
   - Status (Not Completed/Completed)

### Using the Dashboard
- **Home**: View personalized dashboard with due today and overdue counts
- **Not Completed**: See all pending tasks
- **Completed**: View finished tasks
- **Course Links**: Click course names in sidebar to view course-specific tasks

### Filtering Tasks
- Use the "Course" dropdown to filter by specific courses
- Use "Sort" dropdown for priority or date sorting
- Use "Status" dropdown to show completed or pending tasks only

### Task Operations
- **Complete Tasks**: Click the checkbox next to any task to toggle completion
- **Edit Tasks**: Click task names to view details, then use "Edit Task" button
- **Delete Tasks**: Use "Delete Task" button with confirmation dialog

## Security Features

- **Password Hashing**: Uses PHP's PASSWORD_DEFAULT for secure storage
- **SQL Injection Prevention**: Prepared statements throughout
- **Session Security**: Proper session management and logout
- **Input Validation**: Server-side validation for all forms
- **User Isolation**: All data queries filtered by authenticated user ID

## File Structure

```
t4sc/
├── index.php              # Login page
├── create-account.php     # User registration
├── logout.php            # Session termination
├── settings.php          # Profile management
├── home.php              # Main dashboard
├── completed.php         # Completed tasks view
├── not-completed.php     # Pending tasks view
├── course.php            # Course overview
├── course-new.php        # Create course
├── course-edit.php       # Edit course
├── task.php              # Task details
├── task-new.php          # Create task
├── task-edit.php         # Edit task
├── task-toggle.php       # Status toggle handler
├── db.php                # Database connection
├── data.php              # Data loading utilities
├── partials.php          # HTML rendering functions
├── db.env                # Database configuration (create this)
└── assets/
    └── style.css         # Custom styling
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is open source and available under the [MIT License](LICENSE).

## Support

For questions or issues, please open an issue on GitHub or contact the development team.

---

**TASKS 4 SCHOOL** - Stay organized, meet deadlines, succeed academically.</content>
<parameter name="filePath">c:\xampp\htdocs\rosell\t4sc\README.md