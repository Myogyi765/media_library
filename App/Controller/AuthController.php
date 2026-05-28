<?php

namespace App\Controller;

use App\Service\UserService;
use App\Request\LoginRequest;
use App\Request\RegisterUserRequest;
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
            $loginRequest = new LoginRequest();
            $errors = $loginRequest->validateRequest($_POST);

            if (empty($errors)) {

              $usernameOrEmail = trim($_POST['username_or_email'] ?? '');
            $password = $_POST['password'] ?? '';

                $user = $this->userService->authenticate($errors['username_or_email'] ?? '', $errors['password'] ?? '');

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

         $registerRequest = new RegisterUserRequest();
            $errors = $registerRequest->validateRequest($_POST);

    
       

        $result = $this->userService->register([
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'confirm_password' => $_POST['confirm_password']
        ]);
                if ($result['success'] && empty($result['errors'])) {
                     $_SESSION['success'] = 'Your account has been created. You may now log in.';
                    $username = '';
                    $email = '';
                } else {
                    $errors = array_merge_recursive($errors, $result['errors']);
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