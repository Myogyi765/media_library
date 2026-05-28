<?php

namespace App\Service;

use App\Contract\UserInterface;
use App\Model\User;

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
        
       if ($this->repo->usernameExists($data['username'])) {
            $errors['username'][] = 'Username already exists.';
        }

        // Check email exists
        if ($this->repo->emailExists($data['email'])) {
            $errors['email'][] = 'Email already exists.';
        }
        
        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

      
    $user = new User(
        $username,
        $email,
        password_hash($password, PASSWORD_DEFAULT)
    );

    $this->repo->create($user->toArray());
    
        return [
            'success' => true,
            'errors' => []
        ];
    }
}
