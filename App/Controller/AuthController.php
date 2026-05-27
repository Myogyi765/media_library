<?php

namespace App\Controller;

use App\Service\UserService;

class AuthController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(): void
    {
        $pageTitle = 'Login';
        $section = 'login';
        $hideSearch = true;

        $usernameOrEmail = '';
        $errorMessage = null;
         $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usernameOrEmail = trim($_POST['username_or_email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($usernameOrEmail === '') {
                $errors['username_or_email'][] = 'Username or Email is required.';
            }

            if ($password === '') {
                $errors['password'][] = 'Password is required.';
            }

            if (empty($errors)) {
                $user = $this->userService->authenticate($usernameOrEmail, $password);

                if ($user !== null) {
                    $_SESSION['user'] = $user;
                    $_SESSION['success'] = ' Successfully Logged In.';
                    header('Location: ' . BASE_URL . '/Public/index.php?page=index');
                    exit;
                }

                $errorMessage = 'Invalid login credentials.';
            }
        }

        require BASE_PATH . '/view/login.php';
    }

    public function register(): void
    {
        $pageTitle = 'Register';
        $section = 'register';
        $hideSearch = true;

        $username = '';
        $email = '';
        $successMessage = null;
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // if ($password !== $confirmPassword) {
            //     $errors[] = 'Passwords do not match.';
            // }

            if (empty($errors)) {
                $result = $this->userService->register([
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'confirm_password' => $confirmPassword
                ]);

                if ($result['success']) {
                     $_SESSION['success'] = 'Your account has been created. You may now log in.';
                    $username = '';
                    $email = '';
                } else {
                    $errors = array_merge($errors, $result['errors']);
                }
            }
        }

        require BASE_PATH . '/view/register.php';
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        header('Location: ' . BASE_URL . '/Public/index.php?page=index');
        exit;
    }
}