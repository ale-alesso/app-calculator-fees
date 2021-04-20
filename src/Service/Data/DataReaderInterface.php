<?php

namespace App\Service\Data;

use App\Entity\Transaction;
use Generator;

interface DataReaderInterface
{
    /**
     * @param string $source
     * @return Generator|Transaction[]
     */
    public function read(string $source): Generator;
}
