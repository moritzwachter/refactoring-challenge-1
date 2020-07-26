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

    public function handleDistribution(DistributionDTO $distributionDTO): bool
    {
        $this->logger->debug('Request is of type: image.');

        $filename = $distributionDTO->getFilename();
        $firstUnderscore = strpos($filename, '_');
        $sU = strpos($filename, '.', $firstUnderscore + 1);
        $id = (int)substr($filename, $firstUnderscore + 1, $sU - $firstUnderscore - 1);
        $image = $this->repository->getImageById($id);

        $image->setDirectory($distributionDTO->getDirectory());
        $image->setIsDistributed(true);
        $this->repository->save($image);

        return true;
    }
}
