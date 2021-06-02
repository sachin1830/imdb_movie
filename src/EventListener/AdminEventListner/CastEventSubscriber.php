<?php
namespace App\EventListener\AdminEventListner;

use App\EventListener\AdminEvent\CastEvents\AddCastEvent;
use App\EventListener\AdminEvent\CastEvents\DeleteCastEvent;
use App\EventListener\AdminEvent\CastEvents\UpdateCastEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CastEventSubscriber implements EventSubscriberInterface
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
           AddCastEvent::class =>'onCastAdd',
           UpdateCastEvent::class =>'onUpdateCast',
           DeleteCastEvent::class =>'onDeleteCast'
        ];
    }

    public function onCastAdd(AddCastEvent $addCastEvent)
    {
        $cast = $addCastEvent->getCast();
        $admin = $addCastEvent->getAdmin();

        $adminLog = $this->eventHelper->addlog($admin);
        $adminLog->setLog($cast->getName() ." cast having id ".$cast->getId().
                        " is added by admin ");
        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();
    }

    public function onUpdateCast(UpdateCastEvent $updateCastEvent)
    {
        $cast = $updateCastEvent->getCast();
        $admin = $updateCastEvent->getAdmin();
        $method = $updateCastEvent->getMethod();
        $path = $updateCastEvent->getPath();

        $adminLog = $this->eventHelper->addlog($admin);
        $adminLog->setLog($cast->getName() ." cast having id ".$cast->getId().
                        " is updated by admin ");
        $adminLog->setPath($path);
        $adminLog->setMethod($method);
        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();

    }

    public function onDeleteCast(DeleteCastEvent $deleteCastEvent)
    {
        $castId = $deleteCastEvent->getCastID();
        $castName =$deleteCastEvent->getCastName();
        $admin = $deleteCastEvent->getAdmin();

        $adminLog = $this->eventHelper->addlog($admin);
        $adminLog->setLog($castName ." cast having id ".$castId.
                        " is deleted by admin ");
        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();
    }
}
?>