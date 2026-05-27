<?php

namespace App\Service;

use App\Contract\UserInterface;

class UserService
{
    private UserInterface $repo;

    public function __construct(?UserInterface $repo = null)
    {
        $this->repo = $repo;
    }

    public function authenticate(string $usernameOrEmail, string $password): ?array
    {
        $user = $this->repo->findByUsername($usernameOrEmail);

        if ($user === false || $user === null) {
            $user = $this->repo->findByEmail($usernameOrEmail);
        }

        if (empty($user)) {
            return null;
        }

        if (!password_verify($password, $user['password'] ?? '')) {
            return null;
        }

        unset($user['password']);

        return $user;
    }

    public function register(array $data): array
    {
        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        // $confirmPassword = $data['confirm_password'] ?? '';

        $errors = [];

        if ($username === '') {
            $errors['username'][] = 'Username is required.';
        } elseif (strlen($username) < 3) {
            $errors['username'][] = 'Username must be at least 3 characters.';
        }

        if ($email === '') {
            $errors['email'][] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = 'Email is not valid.';
        }

        if ($password === '') {
            $errors['password'][] = 'Password is required.';
        } elseif (strlen($password) < 6) {
            $errors['password'][] = 'Password must be at least 6 characters.';
        }

        if (($data['confirm_password'] ?? '') === '') {
            $errors['confirm_password'][] = 'Confirm Password is required.';
        } elseif ($password !== $data['confirm_password']) {
            $errors['confirm_password'][] = 'Passwords do not match.';
        }

        if (!empty($username) && !empty($this->repo->findByUsername($username))) {
            $errors['username'][] = 'Username is already taken.';
        }

        if (!empty($email) && !empty($this->repo->findByEmail($email))) {
            $errors['email'][] = 'Email is already registered.';
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        $this->repo->create([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        return [
            'success' => true,
            'errors' => []
        ];
    }
}
