<?php
namespace App\Controller\Admin;

use App\Entity\Casts;
use App\Entity\User;
use App\EventListener\AdminEvent\CastEvents\AddCastEvent;
use App\EventListener\AdminEvent\CastEvents\DeleteCastEvent;
use App\EventListener\AdminEvent\CastEvents\UpdateCastEvent;
use App\EventListener\AdminEvent\MovieEvents\DeleteMovieEvent;
use App\EventListener\UserEvent\CastEvent;
use App\Services\Cast\CastService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CastController extends AbstractController
{
    /**
     * @var CastService
     */
    private $castService;
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    function __construct(CastService $castService,
             PaginatorInterface $paginator, EntityManagerInterface $entityManager,
             EventDispatcherInterface $eventDispatcher) 
    {
      $this->castService = $castService;
      $this->paginator = $paginator;
      $this->entityManager = $entityManager;
      $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/cast/panel",name="cast_dashboard")
     */
    public function castAdmin(Request $request):Response
    {
        //Here i am fetching the data using service class
        $fetchCast = $this->castService->getAllCasts();

        $pagination = $this->paginator->paginate(
            $fetchCast, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        
        return $this->render("admin_cast/cast_panel.html.twig",[
            'fetch_casts' => $pagination
        ]);

    }

     /**
      * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/cast/panel/DESC",name="cast_dashboard_desc")
     */
    public function castAdminDESC(Request $request)
    {
        $fetchCasts = $this->castService->getAllCastDESC();

        $pagination = $this->paginator->paginate(
            $fetchCasts, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render("admin_cast/cast_panel.html.twig",[
            'fetch_casts' => $pagination
        ]);
    
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/cast/add_cast",name="add_cast")
     */
    public function addCast():Response
    {
        if(isset($_POST['submit']))
        {
            $cast = new Casts();
            
            $cast=$this->setFields($cast);

            $image = $_FILES['image'];
            
            $filename = $image['name'];
            $filetemp = $image['tmp_name'];
            $destinationPath = $this->getParameter('kernel.project_dir')
            .'/public/upload/'.$filename;
            move_uploaded_file($filetemp,$destinationPath);

            
            $cast->setImage($filename);
            
            $this->entityManager->persist($cast);
            $this->entityManager->flush();
            $this->addFlash('castnotice','Cast has been successfully added .');

            $admin = $this->getUser();
            $event = new AddCastEvent($cast,$admin);
            $this->eventDispatcher->dispatch($event,AddCastEvent::class);

            return $this->redirectToRoute("cast_dashboard");
        
        }
        return $this->render('admin_cast/add_cast.html.twig');
       
    }

    //this method seting all field
    public function setFields(Casts $cast):Casts
    {
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $height = $_POST['height'];
        $sign = $_POST['sign'];
        $description = $_POST['desc'];

        $cast->setName($name);
        $cast->setDescription($description);
        $cast->setGender($gender);
        $cast->setSign($sign);
        $cast->setHeight($height);

        return $cast;
    }

     /**
     * @Route("/admin/cast/update_cast/{id}",name="update_cast")
     * @IsGranted("ROLE_ADMIN")
     */
    public function updateCast(int $id):Response
    {
        $fetchCast = $this->castService->getCast($id);
        if(!$fetchCast)
        {
            throw $this->createNotFoundException(
                'No cast found'
            );
        }

        if(isset($_POST['submit']))
        {
            $image = $_FILES['image'];
            
            $fetchCast = $this->setFields($fetchCast);

            $filename = $image['name'];
            if($filename == "")
            {
                $fetchCast->setImage($fetchCast->getImage());
            }
            else
            {
                $filetemp = $image['tmp_name'];
                $destinationPath = $this->getParameter('kernel.project_dir')
                                            .'/public/upload/'.$filename;
                move_uploaded_file($filetemp,$destinationPath);
                $fetchCast->setImage($filename);
            }
            $this->entityManager->flush();
            $this->addFlash('castnotice','Cast has been successfully updated .');

            $admin = $this->getUser();
            $path = "App\Controller\Admin\CastController::updateCast";
            $event = new UpdateCastEvent($fetchCast,$admin,"PUT",$path);
            $this->eventDispatcher->dispatch($event,UpdateCastEvent::class);
            
            return $this->redirectToRoute("cast_dashboard");
        }
        
        return $this->render("admin_cast/update_cast.html.twig",[
            'fetch_cast' => $fetchCast
        ]);
    
    }

     /**
      * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/cast/delete_cast/{id}",name="delete_cast")
     */
    public function deleteCast(int $id):Response
    {
        $fetchCast = $this->castService->getCast($id);
        $castId = $fetchCast->getId();
        $castName = $fetchCast->getName();

        if($fetchCast)
        {
            $this->entityManager->remove($fetchCast);
        }
        else
        {
            echo"No data found";
        }
        $this->entityManager->flush();
        $this->addFlash('castnotice','Cast has been deleted .');

        $admin = $this->getUser();
        $event = new DeleteCastEvent($castId,$castName,$admin);
        $this->eventDispatcher->dispatch($event,DeleteCastEvent::class);

        return $this->redirectToRoute('cast_dashboard');
    }

     /**
      * @IsGranted("ROLE_USER")
     * @Route("/cast/cast_details/{id}",name="cast_details")
     */
    public function castDetails(int $id)
    {
        $fetchDetails = $this->castService->getCast($id);  

        $path = "App\Controller\Admin\CastController::castDetails";
        $user = $this->getUser();
        $event = new CastEvent($user,$fetchDetails,$path);
        $this->eventDispatcher->dispatch($event,CastEvent::class);
        
        return $this->render("admin_cast/cast_details.html.twig",[
            'fetchDetails' => $fetchDetails 
        ]);
    }

}


?>