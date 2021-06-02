<?php
namespace App\Helper;

use App\Entity\Movies;
use App\Services\Cast\CastService;
use App\Services\Category\CategoryService;
use App\Services\Country\CountryService;
use App\Services\Directer\DirecterService;
use App\Services\Language\LanguageService;
use App\Services\ProductionCompany\ProductionCompnayService;
use App\Services\Writer\WriterService;
use Exception;

class MovieHelper
{
    /**
     * @var CastService
     */
    private $castService;

    /**
     * @var DirecterService
     */
    private $directerService;
    /**
     * @var WriterService
     */
    private $writerService;
    /**
     * @var LanguageService
     */
    private $languageService;
    /**
     * @var CategoryService
     */
    private $categoryService;
    /**
     * @var CountryService
     */
    private $countryService;
    /**
     * @var ProductionCompnayService
     */
    private $productionCompnayService;
    function __construct(CastService $castService, DirecterService $directerService
    ,WriterService $writerService, LanguageService $languageService,
    CategoryService $categoryService, CountryService $countryService, 
    ProductionCompnayService $productionCompnayService)
    {
        $this->castService = $castService;
        $this->directerService = $directerService;
        $this->writerService = $writerService;
        $this->languageService = $languageService;
        $this->categoryService = $categoryService;
        $this->countryService = $countryService;
        $this->productionCompnayService = $productionCompnayService;
    }

    public function setCasts(Movies $movie,$movieCasts): Movies
    {
        foreach($movie->getCasts() as $cast)
        {
            $fetchCast = $this->castService->getCast($cast->getId());
            $movie->removeCast($fetchCast);
        }

        foreach ($movieCasts as $id) {

            $fetchCast = $this->castService->getCast($id);
            if (!$fetchCast) {
                throw new Exception("Invalid cast id");
            }
             $movie->addCast($fetchCast);

        }
        return $movie;
    }

    public function setDirecters(Movies $movie , $movieDirecters): Movies
    {
        foreach($movie->getDirecters() as $directer)
        {
            $fetchDirecter=$this->directerService
                                ->getDirecter($directer->getId());
            $movie->removeDirecter($fetchDirecter);
        }

        foreach ($movieDirecters as $id) {
            $fetchDirecter = $this->directerService->getDirecter($id);

            if (!$fetchDirecter) {
                throw new Exception("Invalid directer id");
            }
             $movie->addDirecter($fetchDirecter);
         }
         return $movie;
    }

    public function setWriters(Movies $movie , $movieWriters): Movies
    { 
        foreach($movie->getWriters() as $writer)
        {
            $fetchWriter=$this->writerService
                            ->getWriter($writer->getId());
            $movie->removeWriters($fetchWriter);
        }

        foreach ($movieWriters as $id) {
            $fetchWriter = $this->writerService->getWriter($id);

            if (!$fetchWriter) {
                throw new Exception("Invalid writer id");
            }
            $movie->addWriter($fetchWriter);

        }
     return $movie;

    }

    public function setLanguages(Movies $movie , $movieLanguages): Movies
    { 
        foreach($movie->getLanguages() as $language)
        {
            $fetchLanguage=$this->languageService
                                ->getLanguage($language->getId());
            
            $movie->removeLanguage($fetchLanguage);
        }

        foreach ($movieLanguages as $id) {
            $fetchLanguage = $this->languageService->getLanguage($id);

            if (!$fetchLanguage) {
                throw new Exception("Invalid language id");
            }
             $movie->addLanguage($fetchLanguage);

         }
         return $movie;
     }

     public function setCategorys(Movies $movie , $movieCategory): Movies
    { 
        foreach($movie->getCategores() as $category)
        {
            $fetchCategory = $this->categoryService
                                   -> getCategory($category->getId());
                                    
            $movie->removeCategores($fetchCategory);
        }

        foreach ($movieCategory as $id) {
            $fetchCategory = $this->categoryService->getCategory($id);

            if (!$fetchCategory) {
                throw new Exception("Invalid category id");
            }
             $movie->addCategores($fetchCategory);

         }
         return $movie;
     }

     public function setCountrys(Movies $movie , $movieCountry): Movies
    { 

        foreach($movie->getCountrys() as $country)
        {
            $fetchCountry = $this->countryService
                                ->getCountry($country->getId());
                                    
            $movie->removeCountry($fetchCountry);
        }

        foreach ($movieCountry as $id) {
            $fetchCountry = $this->countryService->getCountry($id);

            if (!$fetchCountry) {
                throw new Exception("Invalid country id");
            }
             $movie->addCountry($fetchCountry);

         }
         return $movie;
     }
     public function setCompanys(Movies $movie , $movieCompany): Movies
     { 
        foreach($movie->getProdutionCompanys() as $company)
        {
            $fetchCompany = $this->productionCompnayService
                                ->getCompany($company->getId());
                                    
            $movie->removeProdutionCompany($fetchCompany);
        }

        foreach ($movieCompany as $id) {
            $fetchCompnay = $this->productionCompnayService->getCompany($id);

            if (!$fetchCompnay) {
                throw new Exception("Invalid production id");
            }
             $movie->addProdutionCompany($fetchCompnay);

         }
         return $movie;
      }

}
?>