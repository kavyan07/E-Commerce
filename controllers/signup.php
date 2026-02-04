<?php
// Signup Controller
require_once ROOT_PATH . '/src/UserDAO.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userDAO = new UserDAO();

    $data = [
        'first_name' => $_POST['firstName'] ?? '',
        'last_name' => $_POST['lastName'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
        'phone' => $_POST['phone'] ?? ''
    ];

    $result = $userDAO->createUser($data);

    if ($result['success']) {
        // Auto-login after signup
        $_SESSION['user'] = [
            'firstName' => $data['first_name'],
            'email' => $data['email']
        ];
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['flash_message'] = ['text' => 'Account created! Welcome to EasyCart.', 'type' => 'success'];
        header('Location: home');
        exit;
    } else {
        $error = $result['message'];
    }
}

$page_title = 'Sign Up - EasyCart';
$page_css = 'signup.css';

loadView('signup', [
    'page_title' => $page_title,
    'page_css' => $page_css,
    'error' => $error
]);
