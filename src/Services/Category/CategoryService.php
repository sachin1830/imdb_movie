<?php
namespace App\Services\Category;

use App\Entity\Categores;
use App\Repository\CategoryRepository;
use Exception;

class CategoryService implements CategoryServiceInterface
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function getCategory(int $id): ?Categores
    {
        try {
            $category = $this->categoryRepository->find($id);

            if(!$category)
            {
                throw new Exception("Invalid id, category not found");
                
            }
        } catch (Exception $e) {
            echo"Error : ".$e->getMessage();
        }
      
       return $category;
    }

    public function getAllCategory():array
    {
        $categoryes = $this->categoryRepository->findAll();
        return $categoryes;
    }
}

?>