<?php

namespace App\Distribution;

use App\DTO\DistributionDTO;
use App\Mocks\FakeRepository;
use Psr\Log\LoggerInterface;

class VideoDistributionStrategy implements DistributionStrategyInterface
{
    /** @var FakeRepository */
    private $repository;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(FakeRepository $fakeRepository, LoggerInterface $logger)
    {
        $this->repository = $fakeRepository;
        $this->logger = $logger;
    }

    public function handleDistribution(DistributionDTO $distributionDTO): void
    {
        $this->logger->debug('Request is of type: video.');

        $id = FilenameDataExtractor::getIdFromFilename(
            $distributionDTO->getFilename(),
            DistributionDTO::REGEX_VIDEO_FILENAME
        );

        $video = $this->repository->getVideoById($id);

        $video->setDirectory($distributionDTO->getDirectory());
        $video->setIsDistributed(true);

        $quality = $this->repository->getQualityByKey($distributionDTO->getQualityKey());
        $video->setQuality($quality);
        $this->repository->save($video);
    }
}
