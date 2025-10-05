<?php

namespace App\Resource;

class SimulationRequest
{
    public function __construct(
        public ?int   $width,
        public ?int   $height,
        public ?float $probab,
        public ?int   $indexI,
        public ?int   $indexJ,
    ) {

    }

}
