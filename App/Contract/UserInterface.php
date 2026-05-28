<?php
namespace App\Contract;

use App\Contract\BaseInterface;

interface UserInterface extends BaseInterface
{
    public function findByUsername(string $username);
    public function findByEmail(string $email);

     public function usernameExists(string $username): bool;

    public function emailExists(string $email): bool;

    public function create(array $data): bool;
}
