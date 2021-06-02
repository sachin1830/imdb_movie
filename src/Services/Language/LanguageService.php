<?php
namespace App\Services\Language;

use App\Entity\Languages;
use App\Repository\LanguageRepository;
use Exception;

class LanguageService implements LanguageServiceInterface
{
    /**
     * @var LanguageRepository
     */
    private $languageRepository;
    function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function getAllLanguage():array
    {
       $languages = $this->languageRepository->findAll();

       return $languages;
    }

    public function getLanguage(int $id):Languages
    {
        try {
            $language = $this->languageRepository->find($id);

            if(!$language)
            {
                throw new Exception("Invalid id, language not found");
                
            }
        } catch (Exception $e) {
            echo"Error : ".$e->getMessage();
        }
       

       return $language;
    }
}
?>