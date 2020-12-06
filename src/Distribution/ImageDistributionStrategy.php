<?php

namespace App\Distribution;

use App\DTO\DistributionDTO;
use App\Mocks\FakeRepository;
use Psr\Log\LoggerInterface;

class ImageDistributionStrategy implements DistributionStrategyInterface
{
    /** @var FakeRepository */
    private $repository;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param FakeRepository $repo
     * @param LoggerInterface $logger
     */
    public function __construct(FakeRepository $repo, LoggerInterface $logger)
    {
        $this->repository = $repo;
        $this->logger = $logger;
    }

    public function handleDistribution(DistributionDTO $distributionDTO): void
    {
        $this->logger->debug('Request is of type: image.');

        $id = FilenameDataExtractor::getIdFromFilename(
            $distributionDTO->getFilename(),
            DistributionDTO::REGEX_IMAGE_FILENAME
        );

        $image = $this->repository->getImageById($id);

        $image->setDirectory($distributionDTO->getDirectory());
        $image->setIsDistributed(true);
        $this->repository->save($image);
    }
}
