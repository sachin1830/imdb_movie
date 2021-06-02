<?php
namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Writers;
use App\EventListener\AdminEvent\WriterEvents\AddWriterEvent;
use App\EventListener\AdminEvent\WriterEvents\DeleteWriterEvent;
use App\EventListener\AdminEvent\WriterEvents\UpdateWriterEvent;
use App\EventListener\UserEvent\WriterEvent;
use App\Services\Writer\WriterService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class WriterController extends AbstractController
{
    /**
     * @var WriterService
     */
    private $writerService;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    function __construct(WriterService $writerService , 
    EntityManagerInterface $entityManager, PaginatorInterface $paginator,
    EventDispatcherInterface $eventDispatcher)
    {
        $this->writerService = $writerService;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->eventDispatcher = $eventDispatcher;
    }

     /**
      *@IsGranted("ROLE_ADMIN")
     * @Route("/admin/writer/panel",name="writer_dashboard")
     */
    public function writerAdmin(Request $request):Response
    {
        //Here we are fetching the data using writer service
        $fetchWriters = $this->writerService->getAllWriters();
        $pagination = $this->paginator->paginate(
            $fetchWriters, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render("admin_writer/writer_panel.html.twig",[
            'fetchWriter' =>$pagination
        ]);
       
    }
    /**
     *@IsGranted("ROLE_ADMIN")
     * @Route("/admin/write/panel/desc",name="writer_panel_desc")
     */
    public function writerPanelDesc(Request $request)
    {
        $fetchWriters = $this->writerService->getAllWritersDESC();

        $pagination = $this->paginator->paginate(
            $fetchWriters, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render("admin_writer/writer_panel.html.twig",[
            'fetchWriter' =>$pagination
        ]);
    }

     /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/writer/add_writer",name="writer_add")
     */
    public function addWriter():Response
    {   
        if(isset($_POST['submit']))
        {
            $writer = new Writers();
            //calling this method for setting the value
            $writer = $this->setFields($writer);
            $image = $_FILES['image'];

            $filename = $image['name'];
            $filetemp = $image['tmp_name'];
            $destinationPath = $this->getParameter('kernel.project_dir')
                                        .'/public/upload/'.$filename;
            move_uploaded_file($filetemp,$destinationPath);

            $writer->setImage($filename);
            $this->entityManager->persist($writer);
            $this->entityManager->flush();
            $this->addFlash('writer_notice',"Writer has been successfully added");

            $admin = $this->getUser();
            $event = new AddWriterEvent($writer,$admin);
            $this->eventDispatcher->dispatch($event,AddWriterEvent::class);
            
            return $this->redirectToRoute("writer_dashboard");
        }

        return $this->render('admin_writer/add_writer.html.twig');
       
    }

    //this method seting all field
    public function setFields(Writers $writer):Writers
    {
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $height = $_POST['height'];
        $sign = $_POST['sign'];
        $description = $_POST['desc'];

        $writer->setName($name);
        $writer->setDescription($description);
        $writer->setGender($gender);
        $writer->setSign($sign);
        $writer->setHeight($height);

        return $writer;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/writer/update_writer/{id}",name="writer_update")
     */
    public function updateWriter(int $id):Response
    {
        $fetchWriter = $this->writerService->getWriter($id);
        if(!$fetchWriter)
        {
            throw $this->createNotFoundException(
                'No directer found'
            );
        }
        if(isset($_POST['submit']))
        {
            //caling this method for setting the filed
            $fetchWriter = $this->setFields($fetchWriter);
            $image = $_FILES['image'];

            $filename = $image['name'];
            if($filename == "")
            {
                    $fetchWriter->setImage($fetchWriter->getImage());
            }
            else
            {
                $filetemp = $image['tmp_name'];
                $destinationPath = $this->getParameter('kernel.project_dir')
                                            .'/public/upload/'.$filename;
                move_uploaded_file($filetemp,$destinationPath);
                $fetchWriter->setImage($filename);
            }
            $this->addFlash('writer_notice',"Writer has been successfully updated");
            $this->entityManager->flush();

            $admin = $this->getUser();
            $event = new UpdateWriterEvent($fetchWriter,$admin);
            $this->eventDispatcher->dispatch($event,UpdateWriterEvent::class);

            return $this->redirectToRoute("writer_dashboard");
        }

        return $this->render("admin_writer/update_writer.html.twig",[
            'fetch_writer' =>$fetchWriter
        ]);
       
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/writer/delete_writer/{id}",name="delete_writer")
     */
    public function deleteWriter(int $id):Response
    {
        $fetchWriter = $this->writerService->getWriter($id);
        $writerId = $fetchWriter->getId();
        $writerName =$fetchWriter->getName();

        if($fetchWriter)
        {
            $this->entityManager->remove($fetchWriter);
        }
        else
        {
            echo"No data found";
        }
        $this->entityManager->flush();
        $this->addFlash('writer_notice',"Writer has been removed");

        $admin = $this->getUser();
        $event = new DeleteWriterEvent($writerId,$writerName,$admin);
        $this->eventDispatcher->dispatch($event,DeleteWriterEvent::class);

        return $this->redirectToRoute("writer_dashboard");

    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/writer/writer_details/{id}",name="writer_details")
     */
    public function writerDetails(int $id)
    {
        $fetchDetails=$this->writerService->getWriter($id);

        $path = "App\Controller\Admin\WriterController::writerDetails";
        $user = $this->getUser();
        $event = new WriterEvent($user,$fetchDetails,$path);
        $this->eventDispatcher->dispatch($event,WriterEvent::class);
            return $this->render("admin_writer/writer_details.html.twig",[
                'fetchDetails' => $fetchDetails
                
            ]);
        
       
    }

}

?>