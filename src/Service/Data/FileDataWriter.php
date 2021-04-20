<?php

namespace App\Service\Data;

use App\Exception\InvalidFileException;

class FileDataWriter implements DataWriterInterface
{
    private string $fileToWrite;

    /**
     * @var resource
     */
    private $handle;

    public function __construct(string $fileToWrite)
    {
        $this->fileToWrite = $fileToWrite;
    }

    /**
     * @param string $text
     * @throws InvalidFileException
     */
    public function write(string $text)
    {
        fwrite($this->handle(), $text . PHP_EOL);
    }

    public function close()
    {
        if ($this->handle !== null) {
            fclose($this->handle);
            $this->handle = null;
        }
    }

    /**
     * @return resource
     * @throws InvalidFileException
     */
    private function handle()
    {
        if ($this->handle === null) {
            $handle = fopen($this->fileToWrite, 'w+');

            if ($handle === false) {
                throw new InvalidFileException($this->fileToWrite);
            }

            $this->handle = $handle;
        }

        return $this->handle;
    }
}
