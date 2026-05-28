<?php

namespace App\Repository;

use App\Contract\UserInterface;
use App\Repository\BaseRepository;
use PDO;

class UserRepository extends BaseRepository implements UserInterface
{
    public function __construct(PDO $db)
    {
        parent::__construct($db, 'users', 'user_id');
    }

    public function findByUsername(string $username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function usernameExists(string $username): bool
{
    $sql = "SELECT user_id FROM users WHERE username = :username LIMIT 1";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        'username' => $username
    ]);

    return (bool) $stmt->fetch();
}
   
    public function emailExists(string $email): bool
{
    $sql = "SELECT user_id FROM users WHERE email = :email LIMIT 1";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        'email' => $email
    ]);

    return (bool) $stmt->fetch();
}

    public function create(array $data): bool
    {
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }
}
