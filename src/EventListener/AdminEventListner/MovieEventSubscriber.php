<?php
namespace App\EventListener\AdminEventListner;

use App\Entity\Movies;
use App\EventListener\AdminEvent\MovieEvents\AddMovieEvent;
use App\EventListener\AdminEvent\MovieEvents\DeleteMovieEvent;
use App\EventListener\AdminEvent\MovieEvents\UpdateMovieEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class MovieEventSubscriber implements EventSubscriberInterface
{
     /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var EventHelper
     */
    private $eventHelper;
    /**
     * @var RequestEvent
     */

    function __construct(EntityManagerInterface $entityManager
                ,EventHelper $eventHelper )
    {
        $this->entityManager = $entityManager;
        $this->eventHelper = $eventHelper;
        
    }

    public static function getSubscribedEvents()
    {
        return [
            AddMovieEvent::class => 'onMovieAdd',
            UpdateMovieEvent::class => 'onMovieUpdate',
            DeleteMovieEvent::class => 'onMovieDelete'
        ];
    }

    //On movie add this evenlistener call
    public function onMovieAdd(AddMovieEvent $addMovieEvent)
    {
        /**
         * @var Movies
         */
        $movie = $addMovieEvent->getMovie();
        $admin = $addMovieEvent->getAdmin();
        $adminLog = $this->eventHelper->addlog($admin);
        $adminLog->setLog($movie->getTitle() ." movie having id ".$movie->getId().
                        " is added by admin ");
        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();
    }

    public function onMovieUpdate(UpdateMovieEvent $updateMovieEvent,RequestEvent $event)
    {
        $movie = $updateMovieEvent->getMovie();
        $admin = $updateMovieEvent->getAdmin();
       
        $adminLog = $this->eventHelper->addlog($admin);
        $adminLog->setLog($movie->getTitle()." movie having id ".$movie->getId().
                        " is updated by admin ");
        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();
    }

    public function onMovieDelete(DeleteMovieEvent $deleteMovieEvent)
    {
        $admin = $deleteMovieEvent->getAdmin();
        $adminLog = $this->eventHelper->addlog($admin);
        $movieId = $deleteMovieEvent->getMovieId();
        $movieName = $deleteMovieEvent->getMoveTitle(); 

        $adminLog->setLog($movieName." movie having id ".
                            $movieId." is deleted by admin ");

        $this->entityManager->persist($adminLog);
        $this->entityManager->flush();
    }

   
}

?>