<?php
// Signup Controller
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    // Fake signup logic
    $_SESSION['user'] = ['firstName' => $_POST['firstName'] ?? 'User', 'email' => $email];
    $_SESSION['user_id'] = 1;
    $_SESSION['flash_message'] = ['text' => 'Account created!', 'type' => 'success'];
    header('Location: home');
    exit;
}

$page_title = 'Sign Up - EasyCart';
$page_css = 'signup.css';

loadView('signup', [
    'page_title' => $page_title,
    'page_css' => $page_css
]);
