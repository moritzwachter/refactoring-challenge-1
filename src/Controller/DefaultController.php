<?php

namespace App\Controller;

use App\DTO\DistributionDTO;
use App\Mocks\FakeRepository;
use Doctrine\ORM\EntityNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param FakeRepository $repo
     * @return JsonResponse
     */
    public function indexAction(Request $request, FakeRepository $repo, LoggerInterface $logger)
    {
        $distributionData = DistributionDTO::fromRequest($request);

        $success = false;
        try {
            if ($distributionData->getType() === 'image') {
                $success = $this->handleImage($logger, $repo, $distributionData);
            } elseif ($distributionData->getType() === 'video') {
                $success = $this->handleVideo($logger, $repo, $distributionData);
            }
        } catch (EntityNotFoundException $notFoundHttpException) {
            return new JsonResponse(['status' => 'not found'], 404);
        }

        if ($success) {
            return new JsonResponse(['status' => 'ok'], 200);
        }

        return new JsonResponse(['status' => 'error'], 500);
    }

    /**
     * @param LoggerInterface $logger
     * @param FakeRepository $repo
     * @param DistributionDTO $dto
     * @return bool
     * @throws EntityNotFoundException
     */
    public function handleImage(LoggerInterface $logger, FakeRepository $repo, DistributionDTO $dto): bool
    {
        $logger->debug('Request is of type: image.');

        $filename = $dto->getFilename();
        $firstUnderscore = strpos($filename, '_');
        $sU = strpos($filename, '.', $firstUnderscore + 1);
        $id = (int)substr($filename, $firstUnderscore + 1, $sU - $firstUnderscore - 1);
        $image = $repo->getImageById($id);

        $image->setDirectory($dto->getDirectory());
        $image->setIsDistributed(true);
        $repo->save($image);

        return true;
    }

    /**
     * @param LoggerInterface $logger
     * @param FakeRepository $repo
     * @param DistributionDTO $dto
     * @return bool
     * @throws EntityNotFoundException
     */
    public function handleVideo(LoggerInterface $logger, FakeRepository $repo, DistributionDTO $dto): bool
    {
        $logger->debug('Request is of type: video.');

        $filename = $dto->getFilename();
        $firstUnderscore = strpos($filename, '_');
        $secondUnderscore = strpos($filename, '_', $firstUnderscore + 1);
        $id = (int)substr($filename, $firstUnderscore + 1, $secondUnderscore - $firstUnderscore - 1);

        $video = $repo->getVideoById($id);

        $video->setDirectory($dto->getDirectory());
        $video->setIsDistributed(true);

        $quality = $repo->getQualityByKey($dto->getQualityKey());
        $video->setQuality($quality);
        $repo->save($video);

        return true;
    }
}
