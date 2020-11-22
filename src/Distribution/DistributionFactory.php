<?php

namespace App\Distribution;

use App\DTO\DistributionDTO;

class DistributionFactory
{
    /** @var ImageDistributionStrategy */
    private ImageDistributionStrategy $imageStrategy;
    /** @var VideoDistributionStrategy */
    private VideoDistributionStrategy $videoStrategy;

    public function __construct(
        ImageDistributionStrategy $imageDistributionStrategy,
        VideoDistributionStrategy $videoDistributionStrategy
    ) {
        $this->imageStrategy = $imageDistributionStrategy;
        $this->videoStrategy = $videoDistributionStrategy;
    }

    /**
     * @param DistributionDTO $distributionData
     * @return DistributionStrategyInterface
     * @throws \InvalidArgumentException
     */
    public function getDistributionStrategy(DistributionDTO $distributionData): DistributionStrategyInterface
    {
        if ($distributionData->isImageType()) {
            return $this->imageStrategy;
        }

        if ($distributionData->isVideoType()) {
            return $this->videoStrategy;
        }

        throw new \InvalidArgumentException('Invalid type');
    }
}
