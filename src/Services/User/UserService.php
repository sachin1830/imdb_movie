<?php
namespace App\Services\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;

class UserService implements UserServiceInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function getAllUser():array
    {
        $users = $this->userRepository->findAll();
        return $users;
    }
    public function getUsers(int $id): ?User
    {
        $user = $this->userRepository->find($id);
        if(!$user)
        {
            throw new Exception("Invalid id, user not found !!");   
        }
       return $user;
    }
    public function getUserByEmail(string $email): ?User
    {
        
        $user = $this->userRepository->findOneBy(['email' =>$email]);
        return $user;
    }
}

?>