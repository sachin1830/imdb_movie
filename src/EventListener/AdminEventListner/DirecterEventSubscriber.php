<?php
namespace App\EventListener\AdminEventListner;

use App\EventListener\AdminEvent\DirecterEvents\AddDirecterEvent;
use App\EventListener\AdminEvent\DirecterEvents\DeleteDirecterEvent;
use App\EventListener\AdminEvent\DirecterEvents\UpdateDirecterEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DirecterEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventHelper
     */
    private $eventHelper;

    function __construct(EntityManagerInterface $entityManager,
                        EventHelper $eventHelper)
    {
        $this->entityManager = $entityManager;
        $this->eventHelper = $eventHelper;
    }

    public static function getSubscribedEvents()
    {
        return [
           AddDirecterEvent::class =>'onDirecterAdd',
           UpdateDirecterEvent::class =>'onDirecterUpdate',
           DeleteDirecterEvent::class => 'onDirecterDelete'
        ];
    }

    public function onDirecterAdd(AddDirecterEvent $addDirecterEvent)
    {
        $directer = $addDirecterEvent->getDirecter();
        $admin = $addDirecterEvent->getAdmin();

        $adminLog = $this->eventHelper->addlog($admin);

        $adminLog->setLog($directer->getName() ." directer having id "
                            .$directer->getId()." is added by admin ");

        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();
    }

    public function onDirecterUpdate(UpdateDirecterEvent $updateDirecterEvent)
    {
        $directer = $updateDirecterEvent->getDirecter();
        $admin = $updateDirecterEvent->getAdmin();

        $adminLog = $this->eventHelper->addlog($admin);

        $adminLog->setLog($directer->getName() ." directer having id "
                            .$directer->getId()." is updated by admin ");

        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();
    }

    public function onDirecterDelete(DeleteDirecterEvent $deleteDirecterEvent)
    {
        $directerId = $deleteDirecterEvent->getDirecterId();
        $directerName = $deleteDirecterEvent->getDirecterName();

        $admin = $deleteDirecterEvent->getAdmin();

        $adminLog = $this->eventHelper->addlog($admin);

        $adminLog->setLog($directerName ." directer having id "
                            .$directerId." is delete by admin ");

        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();


    }

}

?>