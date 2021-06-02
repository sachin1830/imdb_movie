<?php
namespace App\EventListener\AdminEventListner;

use App\Entity\Admin;
use App\Entity\AdminLog;
use App\EventListener\AdminEvent\AdminLoginEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class AdminEventSubcriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public static function getSubscribedEvents()
    {
        return [
            AdminLoginEvent::NAME => 'onAdminLogin'
        ];
    }

    public function onAdminLogin(AdminLoginEvent $adminLoginEvent)
    {
        /**
         * @var Admin
         */
        $admin = $adminLoginEvent->getAdmin();

        $adminLog = new AdminLog();

        $adminLog->setEmail($admin->getEmail());
        $date =date("d/m/y : h:i:sa");
        
        $adminLog->setDateTime((string)$date);

        $adminLog->setLog("Admin login now ") ;
        
        $this->entityManager->persist($adminLog);

        $this->entityManager->flush();
    }
}
?>