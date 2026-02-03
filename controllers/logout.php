<?php
// Logout Controller
session_destroy();
session_start();
$_SESSION['flash_message'] = ['text' => 'Logged out successfully', 'type' => 'info'];
header('Location: home');
exit;
