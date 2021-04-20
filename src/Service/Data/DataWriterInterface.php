<?php

namespace App\Service\Data;

interface DataWriterInterface
{
    public function write(string $text);

    public function close();
}
