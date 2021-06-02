<?php
namespace App\Services\MovieReview;

use App\Entity\MovieReview;
use App\Repository\MovieReviewRepository;
use Exception;

class MovieReviewService implements MovieReviewInterface
{
    /**
     * @var MovieReviewRepository
     */
    private $movieReviewRepository;
    function __construct(MovieReviewRepository $movieReviewRepository)
    {
        $this->movieReviewRepository = $movieReviewRepository;
    }

    public function getReview(int $id): ?MovieReview
    {
        try {
                $review = $this->movieReviewRepository->find($id);

                if(!$review)
                {
                    throw new Exception("Invalid id, review not found !!");   
                }
             } catch (Exception $e) {
                 
            echo "Error : ".$e->getMessage();
        }  
        return $review;
    }
    public function getAllReview():array 
    {
       $reviews = $this->movieReviewRepository->findAll();
       return $reviews;
    }
}
?>