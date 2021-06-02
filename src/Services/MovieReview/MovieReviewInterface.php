<?php
namespace App\Services\MovieReview;

use App\Entity\MovieReview;

interface MovieReviewInterface
{
    public function getAllReview():array;
    public function getReview(int $id): ?MovieReview;
}

?>s