<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

class MessageDTO extends Data
{
    public function __construct(
        public int $code,
        public string $message
    ) {
    }
}
