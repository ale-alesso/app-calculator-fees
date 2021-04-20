<?php

namespace App\Service\Data;

use App\Entity\Transaction;
use App\Exception\InvalidMappingException;
use App\Exception\MissedFileException;
use App\Exception\InvalidFileException;
use App\Normalizer\TransactionNormalizer;
use Generator;

class CsvFileReader implements DataReaderInterface
{
    private TransactionNormalizer $normalizer;
    private array $keys;

    public function __construct(TransactionNormalizer $normalizer, array $keys)
    {
        $this->normalizer = $normalizer;
        $this->keys = $keys;
    }

    /**
     * @param string $source
     * @return Generator|Transaction[]
     * @throws InvalidFileException
     * @throws MissedFileException
     * @throws InvalidMappingException
     */
    public function read(string $source): Generator
    {
        if (!is_file($source) || !file_exists($source)) {
            throw new MissedFileException($source);
        }

        $handle = fopen($source, 'r');

        if (!is_resource($handle)) {
            throw new InvalidFileException($source);
        }

        try {
            while ($line = fgets($handle)) {
                $row = explode(',', trim($line));
                $data = array_combine($this->keys, $row);

                yield $this->normalizer->toObject($data);
            }
        } finally {
            fclose($handle);
        }
    }
}
