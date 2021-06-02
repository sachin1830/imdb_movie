<?php
namespace App\Services\User;

use App\Entity\User;

interface UserServiceInterface
{
    public function getAllUser():array;
    public function getUsers(int $id): ?User;
    public function getUserByEmail(string $email): ?User;
}
?>