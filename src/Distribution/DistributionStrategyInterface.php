<?php

namespace App\Distribution;

use App\DTO\DistributionDTO;

interface DistributionStrategyInterface
{
    public function handleDistribution(DistributionDTO $distributionDTO): void;
}
