<?php
namespace App\EventListener\AdminEventListner;

use App\Entity\Admin;
use App\Entity\AdminLog;

class EventHelper 
{
    public function addlog(Admin $admin): AdminLog
    {
        $adminLog = new AdminLog();
        $adminLog->setEmail($admin->getEmail());
        $date =date("d/m/y : h:i:sa");
        $adminLog->setDateTime((string)$date);

        return $adminLog;
    }
}
?>