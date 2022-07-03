<?php

namespace App\Service;

use App\Factory\MovieFactory;
use Exception;
use SplFileObject;

class ImportCsv
{
    private MovieFactory $movieFactory;

    public function __construct(MovieFactory $movieFactory)
    {
        $this->movieFactory = $movieFactory;
    }

    public function __invoke($filePath): void
    {
        $file = new SplFileObject($filePath, 'r');
        $file->setFlags(SplFileObject::READ_CSV);

        $head = $file->fgetcsv();
        while($file->valid()) {
            try {
                $line = $file->fgetcsv();
                $this->movieFactory->create(array_combine($head, $line));
            } catch (Exception $exception) {}
        }
    }
}