<?php
namespace App\Services\Language;

use App\Entity\Languages;

interface LanguageServiceInterface
{
    public function getAllLanguage():array;
    public function getLanguage(int $id): ?Languages;
}

?>