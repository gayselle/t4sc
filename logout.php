<?php
/**
 * User Logout Handler
 *
 * Destroys the current user session and redirects to the login page.
 * Ensures clean session termination for security.
 */

session_start();
session_destroy();
header('Location: index.php');
exit;
