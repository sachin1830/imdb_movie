<?php
namespace App\Services\Writer;

use App\Entity\Writers;
use App\Repository\WritersRepository;
use Exception;

class WriterService implements WriterServiceInterface
{
    /**
     * @var WritersRepository
     */
    private $writersRepository;

    function __construct(WritersRepository $writersRepository)
    {
        $this->writersRepository = $writersRepository;
    }

    public function getWriter(int $id): ?Writers
    {
        $writer = $this->writersRepository->find($id);
        if(!$writer)
        {
            throw new Exception("Invalid id, writer not found !!");   
        }
        return $writer;
    }

    public function getAllWriters():array
    {
       $writers = $this->writersRepository->findAll();
       return $writers;
    }

    public function getAllWritersDESC():array
    {
      $writersdesc = $this->writersRepository->findByDESC();
      return $writersdesc;
    }
}

?>