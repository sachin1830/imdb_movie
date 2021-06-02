<?php

namespace App\Controller\Admin;

use App\Entity\Directers;
use App\EventListener\AdminEvent\DirecterEvents\AddDirecterEvent;
use App\EventListener\AdminEvent\DirecterEvents\DeleteDirecterEvent;
use App\EventListener\AdminEvent\DirecterEvents\UpdateDirecterEvent;
use App\EventListener\UserEvent\DirecterEvent;
use App\Services\Directer\DirecterService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DirecterController extends AbstractController
{
    /**
     * @var DirecterService
    */
    private $directerService;
     /**
     * @var PaginatorInterface
     */
    private $paginator;
      /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    function __construct(DirecterService $directerService,  
    PaginatorInterface $paginator, EntityManagerInterface $entityManager 
                        ,EventDispatcherInterface $eventDispatcher)
    {
        $this->directerService = $directerService;
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }
    /**
     *  @IsGranted("ROLE_ADMIN")
     * @Route("/admin/directer/panel",name="directer_dashboard")
     */
    public function directerAdmin(Request $request)
    {
        $fetchDirecters = $this->directerService->getAllDirecters();
        $pagination = $this->paginator->paginate(
            $fetchDirecters, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        
        return $this->render("admin_directer/directer_panel.html.twig",[
            'fetch_directers' => $pagination
        ]);
    }

    /**
     *  @IsGranted("ROLE_ADMIN")
     * @Route("/admin/directer/panel/DESC",name="directer_dashboard_desc")
     */
    public function directerAdminDESC(Request $request)
    {
        $fetchDirecter = $this->directerService->getAllDirectersDESC();

        $pagination = $this->paginator->paginate(
            $fetchDirecter, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render("admin_directer/directer_panel.html.twig",[
            'fetch_directers' => $pagination
        ]);
        
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/directer/add_directer",name="add_directer")
     */
    public function addDirecter():Response
    {
        if(isset($_POST['submit']))
        {
            $directer = new Directers();
            
            $directer = $this->setFields($directer);
            $image = $_FILES['image'];

            $filename = $image['name'];
            $filetemp = $image['tmp_name'];
            $destinationPath = $this->getParameter('kernel.project_dir')
                                        .'/public/upload/'.$filename;
            move_uploaded_file($filetemp,$destinationPath);

            $directer->setImage($filename);
            
            $this->entityManager->persist($directer);
            $this->entityManager->flush();
            $this->addFlash('directer_notice',"Directer has been successfully added");

            $admin = $this->getUser();
            $event = new AddDirecterEvent($directer,$admin);
            $this->eventDispatcher->dispatch($event,AddDirecterEvent::class);
            
            return $this->redirectToRoute("directer_dashboard");
            
        }
        return $this->render("admin_directer/add_directer.html.twig");
        
    }

     //this method seting all field
     public function setFields(Directers $directer):Directers
     {
         $name = $_POST['name'];
         $gender = $_POST['gender'];
         $height = $_POST['height'];
         $sign = $_POST['sign'];
         $description = $_POST['desc'];
 
         $directer->setName($name);
         $directer->setDescription($description);
         $directer->setGender($gender);
         $directer->setSign($sign);
         $directer->setHeight($height);
 
         return $directer;
     }

     /**
      * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/directer/update_directer/{id}",name="update_directer")
     */
    public function updateDirecter(int $id):Response
    {
        $fetchDirecter = $this->directerService->getDirecter($id);

        if(!$fetchDirecter)
        {
            throw $this->createNotFoundException(
                'No directer found'
            );
        }

        if(isset($_POST['submit']))
        {
            $fetchDirecter = $this->setFields($fetchDirecter);

            $image = $_FILES['image'];

            $filename = $image['name'];
            if($filename == "")
            {
                    $fetchDirecter->setImage($fetchDirecter->getImage());
            }
            else
            {
                $filetemp = $image['tmp_name'];
                $destinationPath = $this->getParameter('kernel.project_dir')
                                        .'/public/upload/'.$filename;
                move_uploaded_file($filetemp,$destinationPath);
                $fetchDirecter->setImage($filename);
            }
            $this->entityManager->flush();
            $this->addFlash('directer_notice',"Directer has been 
                                                successfully updated");

            $admin = $this->getUser();
            $event = new UpdateDirecterEvent($fetchDirecter,$admin);
            $this->eventDispatcher->dispatch($event,UpdateDirecterEvent::class);

            return $this->redirectToRoute("directer_dashboard");
        }
        return $this->render("admin_directer/update_directer.html.twig",[
            'fetch_directer' =>$fetchDirecter
        ]);

    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/directer/delete_directer/{id}",name="delete_directer")
     */
    public function deleteDirecter(int $id):Response
    {
        
        $fetchDirecter = $this->directerService->getDirecter($id);
        $directerId = $fetchDirecter->getId();
        $directerName = $fetchDirecter->getName();

        if($fetchDirecter)
        {
            $this->entityManager->remove($fetchDirecter);
        }
        else
        {
            echo"No data found";
        }

        $this->entityManager->flush();
        $this->addFlash('directer_notice',"Directer has been removed");

        $admin = $this->getUser();
        $event = new DeleteDirecterEvent($directerId,$directerName,$admin);
        $this->eventDispatcher->dispatch($event,DeleteDirecterEvent::class);
        
        return $this->redirectToRoute("directer_dashboard");
       
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/directer/directer_details/{id}",name="directer_details")
     */
    public function directerDetails(int $id)
    {
        $fetchDetails = $this->directerService->getDirecter($id);

        $path = "App\Controller\Admin\DirecterController::directerDetails";
        $user = $this->getUser();
        $event = new DirecterEvent($user,$fetchDetails,$path);
        $this->eventDispatcher->dispatch($event,DirecterEvent::class);

            return $this->render("admin_directer/directer_details.html.twig",[
                'fetchDetails' => $fetchDetails
            ]);
       
    }
}

?>