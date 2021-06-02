<?php
namespace App\Services\Country;

use App\Entity\Countrys;
use App\Repository\CountryRepository;
use Exception;

class CountryService implements CountryServiceInterface
{
    /**
     * @var CountryRepository
     */
    private $countryRepository;
    function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function getCountry(int $id): ?Countrys
    {
        try {
            $country = $this->countryRepository->find($id);

            if(!$country)
            {
                throw new Exception("Invalid id country onot found");
                
            }
        } catch (Exception $e) {
            echo"Error : ".$e->getMessage();
        }
        

        return $country;
    }

    public function getAllCountry():array
    {
        $countryes = $this->countryRepository->findAll();
        return $countryes;
    }
}

?>