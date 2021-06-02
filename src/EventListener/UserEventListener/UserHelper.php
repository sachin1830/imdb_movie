<?php
namespace App\EventListener\UserEventListener;

use App\Entity\User;
use App\Entity\UserLog;

class  UserHelper{

  public function setUserLog(User $user,string $path): UserLog
  {
      $userLog = new UserLog();

      $userLog->setEmail($user->getEmail());
      $userLog->setPath($path);
      
      return $userLog;
  }

}

?>