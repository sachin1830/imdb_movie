<?php
namespace App\Services\Writer;

use App\Entity\Writers;

interface WriterServiceInterface
{
    public function getAllWriters():array;
    public function getWriter(int $id): ?Writers;
    public function getAllWritersDESC():array;
}

?>
