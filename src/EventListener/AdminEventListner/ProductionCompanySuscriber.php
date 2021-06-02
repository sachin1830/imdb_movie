<?php
namespace App\EventListener\AdminEventListner;

use App\EventListener\AdminEvent\ProductionCompanyEvents\AddCompanyEvent;
use App\EventListener\AdminEvent\ProductionCompanyEvents\DeleteCompanyEvent;
use App\EventListener\AdminEvent\ProductionCompanyEvents\UpdateCompanyEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductionCompanySuscriber implements EventSubscriberInterface
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
           AddCompanyEvent::class =>'onCompanyAdd',
           UpdateCompanyEvent::class =>'onCompanyUpdate',
           DeleteCompanyEvent::class =>'onDeleteCompany'
        ];
    }

    public function onCompanyAdd(AddCompanyEvent $addCompanyEvent)
    {
        $company = $addCompanyEvent->getCompany();
        $admin = $addCompanyEvent->getAdmin();

        $adminLog = $this->eventHelper->addlog($admin);

        $adminLog->setLog($company->getName() ." production company having id ".
                            $company->getId()." is added by admin ");

        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();
    }

    public function onCompanyUpdate(UpdateCompanyEvent $updateCompanyEvent)
    {
        $company = $updateCompanyEvent->getCompany();
        $admin = $updateCompanyEvent->getAdmin();

        $adminLog = $this->eventHelper->addlog($admin);

        $adminLog->setLog($company->getName() ."production company having id "
                            .$company->getId(). " is updated by admin ");

        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();

    }

    public function onDeleteCompany(DeleteCompanyEvent $deleteCompanyEvent)
    {
        $companyId = $deleteCompanyEvent->getCompanyId();
        $companyName = $deleteCompanyEvent->getCompanyName();
        $admin = $deleteCompanyEvent->getAdmin();

        $adminLog = $this->eventHelper->addlog($admin);

        $adminLog->setLog($companyName ."production company having id "
                            .$companyId. " is delete by admin ");

        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();

    }
}

?>