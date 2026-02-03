<?php
// Login Controller
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fake login logic (replace with DAO check)
    $_SESSION['user'] = ['firstName' => 'User', 'email' => $email];
    $_SESSION['user_id'] = 1;
    $_SESSION['flash_message'] = ['text' => 'Welcome back!', 'type' => 'success'];
    header('Location: home');
    exit;
}

$page_title = 'Login - EasyCart';
$page_css = 'login.css';

loadView('login', [
    'page_title' => $page_title,
    'page_css' => $page_css
]);
