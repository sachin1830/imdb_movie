<?php
namespace App\EventListener\UserEventListener;

use App\Entity\MovieMedia;
use App\Entity\User;
use App\Entity\UserLog;
use App\EventListener\UserEvent\CastEvent;
use App\EventListener\UserEvent\CompanyEvent;
use App\EventListener\UserEvent\DirecterEvent;
use App\EventListener\UserEvent\LoginEvent;
use App\EventListener\UserEvent\MovieEvent;
use App\EventListener\UserEvent\MovieMediaEvent;
use App\EventListener\UserEvent\ReviewEvent;
use App\EventListener\UserEvent\WriterEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserHelper
     */
    private $helper;
    function __construct(EntityManagerInterface $entityManager,
                            UserHelper $helper)
    {
        $this->entityManager = $entityManager;
        $this->helper = $helper;
    }
    public static function getSubscribedEvents()
    {
        return [
            MovieEvent::class => 'onMovieClick',
            CastEvent::class => 'onCastClick',
            CompanyEvent::class => 'onCompanyClick',
            DirecterEvent::class => 'onDirecterClick',
            LoginEvent::class => 'onLoginClick',
            MovieMediaEvent::class => 'onMovieMediaClick',
            WriterEvent::class => 'onWriterClick',
            ReviewEvent::class => 'onMovieReview'
        ];

    }

    public function onMovieClick(MovieEvent $movieEvent)
    {
       $user = $movieEvent->getUser();
       $movie = $movieEvent->getMovie();
       $path = $movieEvent->getPath();
       $userlog = $this->helper->setUserLog($user,$path);

       $userlog->setActivity($movie->getTitle()." Movie having id "
       .$movie->getId()." user clicked");

       $this->entityManager->persist($userlog);
       $this->entityManager->flush();

    }

    public function onCastClick(CastEvent $castEvent)
    {
        
        $user = $castEvent->getUser();
       $cast = $castEvent->getCast();
       $path = $castEvent->getPath();
       $userlog = $this->helper->setUserLog($user,$path);

       $userlog->setActivity($cast->getname()." Cast having id "
       .$cast->getId()." user clicked");

       $this->entityManager->persist($userlog);
       $this->entityManager->flush();
    }

    public function onCompanyClick(CompanyEvent $companyEvent)
    {
        $user = $companyEvent->getUser();
       $company = $companyEvent->getCompany();
       $path = $companyEvent->getPath();
       $userlog = $this->helper->setUserLog($user,$path);

       $userlog->setActivity($company->getname()." production company having id "
       .$company->getId()." user clicked");

       $this->entityManager->persist($userlog);
       $this->entityManager->flush();
    }

    public function onDirecterClick(DirecterEvent $directerEvent)
    {
        $user = $directerEvent->getUser();
       $directer = $directerEvent->getDirecter();
       $path = $directerEvent->getPath();
       $userlog = $this->helper->setUserLog($user,$path);

       $userlog->setActivity($directer->getname()." Directer having id "
       .$directer->getId()." user clicked");

       $this->entityManager->persist($userlog);
       $this->entityManager->flush();

    }

    public function onLoginClick(LoginEvent $loginEvent)
    {
        $user = $loginEvent->getUser();
        $path = $loginEvent->getPath();
        $userlog = $this->helper->setUserLog($user,$path);
        $userlog->setActivity("user login successfully");

        $this->entityManager->persist($userlog);
        $this->entityManager->flush();

    }

    public function onMovieMediaClick(MovieMediaEvent $movieMediaEvent)
    {
        $user = $movieMediaEvent->getUser();
       $movie = $movieMediaEvent->getMovie();
       $path = $movieMediaEvent->getPath();
       $userlog = $this->helper->setUserLog($user,$path);

       $userlog->setActivity($movie->getTitle()." Movie having id "
       .$movie->getId()." user watch trailer and photo");

       $this->entityManager->persist($userlog);
       $this->entityManager->flush();
    }

    public function onWriterClick(WriterEvent $writerEvent)
    {
        $user = $writerEvent->getUser();
        $writer = $writerEvent->getWriter();
        $path = $writerEvent->getPath();
        $userlog = $this->helper->setUserLog($user,$path);
 
        $userlog->setActivity($writer->getname()." Writer having id "
        .$writer->getId()." user clicked");
 
        $this->entityManager->persist($userlog);
        $this->entityManager->flush();
    }

    public function onMovieReview(ReviewEvent $reviewEvent)
    {
        $user = $reviewEvent->getUser();
        $rating = $reviewEvent->getReview();
        $path = $reviewEvent->getPath();
        $movie = $reviewEvent->getMovie();

        $userlog = $this->helper->setUserLog($user,$path);
 
        $userlog->setActivity("User given ".$rating." on "
                        .$movie->getTitle()." movie having id ".$movie->getId());
 
        $this->entityManager->persist($userlog);
        $this->entityManager->flush();
    }
}

?>