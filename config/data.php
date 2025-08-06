<?php

use Spatie\LaravelData\Mappers\SnakeCaseMapper;

return [
    'name_mapping_strategy' => [
        'input'  => SnakeCaseMapper::class,
        'output' => SnakeCaseMapper::class,
    ],
];
