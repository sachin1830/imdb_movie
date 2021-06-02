<?php
namespace App\Controller\Admin;

use App\Entity\ProdutionCompanys;
use App\Entity\User;
use App\EventListener\AdminEvent\CastEvents\AddCastEvent;
use App\EventListener\AdminEvent\ProductionCompanyEvents\AddCompanyEvent;
use App\EventListener\AdminEvent\ProductionCompanyEvents\DeleteCompanyEvent;
use App\EventListener\AdminEvent\ProductionCompanyEvents\UpdateCompanyEvent;
use App\EventListener\UserEvent\CompanyEvent;
use App\Services\ProductionCompany\ProductionCompnayService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Production_CompanyController extends AbstractController
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var ProductionCompnayService
     */
    private $productionCompnayService;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    function __construct(PaginatorInterface $paginator,
        ProductionCompnayService $productionCompnayService, 
        EntityManagerInterface $entityManager, 
        EventDispatcherInterface $eventDispatcher)
    {
        $this->paginator = $paginator;
        $this->productionCompnayService = $productionCompnayService;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }
     /**
      *@IsGranted("ROLE_ADMIN")
     * @Route("/admin/company/panel",name="production_panel")
     */
    public function productionCompany(Request $request):Response
    {
        $fetchCompanys = $this->productionCompnayService->getAllCompanyes();
        $pagination = $this->paginator->paginate(
            $fetchCompanys, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render(
            "admin_production_company/production_company_panel.html.twig",[
            'fetch_companys' =>$pagination
        ]);
        
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/producer/panel/DESC",name="company_dashboard_desc")
     */
    public function companyAdminDESC(Request $request)
    {
            $fetchCompanys = $this->productionCompnayService
                                    ->getAllCompanyesDESC();
            
            $pagination = $this->paginator->paginate(
                $fetchCompanys, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/
            );

            return $this->render(
                "admin_production_company/production_company_panel.html.twig",[
                'fetch_companys' =>$pagination
            ]);
        
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/company/add_production_company",name="add_company")
     */
    public function addCompany():Response
    {
        if(isset($_POST['submit']))
        {
            $company = new ProdutionCompanys();
            $name = $_POST['name'];
            $image = $_FILES['image'];
            $about = $_POST['about'];

            $filename = $image['name'];
            $filetemp = $image['tmp_name'];
            $destinationPath = $this->getParameter('kernel.project_dir')
                                            .'/public/upload/'.$filename;

            //This method  move the file to destination folder
            move_uploaded_file($filetemp,$destinationPath);

            $company->setName($name);
            $company->setImage($filename);
            $company->setAbout($about);

            $this->entityManager->persist($company);
            $this->entityManager->flush();
            $this->addFlash('comapny_notice',"Production company has 
                                                been successfully added"); 
                                                
            $admin = $this->getUser();
            $event = new AddCompanyEvent($company,$admin);
            $this->eventDispatcher->dispatch($event,AddCompanyEvent::class);

            return $this->redirectToRoute("production_panel");
        }
        
        return $this->render("admin_production_company/add_production_company.html.twig");
       
    }

     /**
      * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/production_company/update_company/{id}",name="update_company")
     */

    public function updateCompany(int $id):Response
    {
        
            $fetchCompany = $this->productionCompnayService
                                ->getCompany($id);
            if(!$fetchCompany)
            {
                throw $this->createNotFoundException(
                    'No directer found'
                );
            }
            if(isset($_POST['submit']))
            {
                $name = $_POST['name'];
                $image = $_FILES['image'];
                $about = $_POST['about'];

                $fetchCompany->setName($name);
                $fetchCompany->setAbout($about);

                $filename = $image['name'];
                if($filename == "")
                {
                        $fetchCompany->setImage($fetchCompany->getImage());
                }
                else
                {
                    $filetemp = $image['tmp_name'];
                    $destinationPath = $this->getParameter('kernel.project_dir')
                                                .'/public/upload/'.$filename;
                    move_uploaded_file($filetemp,$destinationPath);
                    $fetchCompany->setImage($filename);
                }
                $this->entityManager->flush();
                $this->addFlash('comapny_notice',"Production company has been 
                                                            successfully updated");
                                                            
                $admin = $this->getUser();
                $event = new UpdateCompanyEvent($fetchCompany,$admin);
                $this->eventDispatcher->dispatch($event,UpdateCompanyEvent::class);

                return $this->redirectToRoute("production_panel");
            }

            return $this->render("admin_production_company/update_company.html.twig",[
                'fetchCompany' =>$fetchCompany
            ]);
       
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/production_company/delete_company/{id}",
     * name="delete_production_company")
     */
    public function deleteCompany(int $id):Response
    {
        
        $fetchCompany = $this->productionCompnayService->getCompany($id);
        $companyId = $fetchCompany->getId();
        $companyName = $fetchCompany->getName();

        if($fetchCompany)
        {
            $this->entityManager->remove($fetchCompany);
        }
        else
        {
            echo"No data found";
        }
        $this->entityManager->flush();
        $this->addFlash('comapny_notice',"Production company 
                            has been successfully removed");

        $admin = $this->getUser();
        $event = new DeleteCompanyEvent($companyId,$companyName,$admin);
        $this->eventDispatcher->dispatch($event,DeleteCompanyEvent::class);

        return $this->redirectToRoute("production_panel");
       
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/production_company/company_details/{id}",name="company_details")
     */
    public function companyDetails(int $id)
    {
        $fetchDetails = $this->productionCompnayService->getCompany($id);

        $path = "App\Controller\Admin\Production_CompanyController::companyDetails";
        $user = $this->getUser();
        $event = new CompanyEvent($user,$fetchDetails,$path);
        $this->eventDispatcher->dispatch($event,CompanyEvent::class);
            return $this->render("admin_production_company/company_details.html.twig",[
                'fetchDetails' => $fetchDetails 
            ]);
        

    }
}
?>