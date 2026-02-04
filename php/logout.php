<?php
session_start();

// Preserve users array before destroying session
$users = $_SESSION['users'] ?? [];

// Clear all session data
session_unset();
session_destroy();

// Start a new session and restore users array
session_start();
if (!empty($users)) {
    $_SESSION['users'] = $users;
}

header('Location: login.php');
exit;

