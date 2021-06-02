<?php
namespace App\EventListener\AdminEventListner;

use App\EventListener\AdminEvent\WriterEvents\AddWriterEvent;
use App\EventListener\AdminEvent\WriterEvents\DeleteWriterEvent;
use App\EventListener\AdminEvent\WriterEvents\UpdateWriterEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WriterEventSubscriber implements EventSubscriberInterface
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
           AddWriterEvent::class =>'onWriterAdd',
           UpdateWriterEvent::class =>'onWriterUpdate',
           DeleteWriterEvent::class =>'onWriterDelete'
        ];
    }

    public function onWriterAdd(AddWriterEvent $addWriterEvent)
    {
        $writer = $addWriterEvent->getWriter();
        $admin = $addWriterEvent->getAdmin();

        $adminLog = $this->eventHelper->addlog($admin);
        $adminLog->setLog($writer->getName() ." writer having id ".$writer->getId().
                        " is added by admin ");
        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();
    }

    public function onWriterUpdate(UpdateWriterEvent $updateWriterEvent)
    {
        $writer = $updateWriterEvent->getWriter();
        $admin = $updateWriterEvent->getAdmin();

        $adminLog = $this->eventHelper->addlog($admin);
        $adminLog->setLog($writer->getName() ."writer having id ".$writer->getId().
                        " is updated by admin ");
        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();

    }

    public function onWriterDelete(DeleteWriterEvent $deleteWriterEvent)
    {
        $writerId = $deleteWriterEvent->getWriterId();
        $writerName = $deleteWriterEvent->getWriterName();
        $admin = $deleteWriterEvent->getAdmin();

        $adminLog = $this->eventHelper->addlog($admin);
        $adminLog->setLog($writerName ."writer having id ".$writerId.
                        " is delete by admin ");
        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();
    }
}
?>