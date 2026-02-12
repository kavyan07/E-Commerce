<?php
// Login Controller
require_once ROOT_PATH . '/src/UserDAO.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $userDAO = new UserDAO();
    $result = $userDAO->login($email, $password);

    if ($result['success']) {
        $user = $result['user'];
        $_SESSION['user'] = [
            'firstName' => $user['first_name'],
            'email' => $user['email']
        ];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['flash_message'] = ['text' => 'Welcome back, ' . $user['first_name'] . '!', 'type' => 'success'];
        header('Location: home');
        exit;
    } else {
        $error = $result['message'];
    }
}

$page_title = 'Login - EasyCart';
$page_css = 'login.css';

loadView('login', [
    'page_title' => $page_title,
    'page_css' => $page_css,
    'error' => $error
]);
