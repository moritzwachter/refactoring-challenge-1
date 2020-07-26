<?php

namespace App\Controller;

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
        $filename = $request->query->get('file');
        $qualityKey = $request->query->get('quality');
        $directory = $request->query->get('directory');
        $type = $request->query->get('type');

        $success = false;
        try {
            if ($type === 'image') {
                $success = $this->handleImage($logger, $filename, $repo, $directory);
            } elseif ($type === 'video') {
                $success = $this->handleVideo($logger, $filename, $repo, $directory, $qualityKey);
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
     * @param $filename
     * @param FakeRepository $repo
     * @param $directory
     * @return bool
     * @throws EntityNotFoundException
     */
    public function handleImage(LoggerInterface $logger, $filename, FakeRepository $repo, $directory): bool
    {
        $logger->debug('Request is of type: image.');

        $firstUnderscore = strpos($filename, '_');
        $sU = strpos($filename, '.', $firstUnderscore + 1);
        $id = (int)substr($filename, $firstUnderscore + 1, $sU - $firstUnderscore - 1);
        $image = $repo->getImageById($id);

        $image->setDirectory($directory);
        $image->setIsDistributed(true);
        $repo->save($image);

        return true;
    }

    /**
     * @param LoggerInterface $logger
     * @param $filename
     * @param FakeRepository $repo
     * @param $directory
     * @param $qualityKey
     * @return bool
     * @throws EntityNotFoundException
     */
    public function handleVideo(LoggerInterface $logger, $filename, FakeRepository $repo, $directory, $qualityKey): bool
    {
        $logger->debug('Request is of type: video.');

        $firstUnderscore = strpos($filename, '_');
        $secondUnderscore = strpos($filename, '_', $firstUnderscore + 1);
        $id = (int)substr($filename, $firstUnderscore + 1, $secondUnderscore - $firstUnderscore - 1);

        $video = $repo->getVideoById($id);

        $video->setDirectory($directory);
        $video->setIsDistributed(true);

        $quality = $repo->getQualityByKey($qualityKey);
        $video->setQuality($quality);
        $repo->save($video);

        return true;
    }
}
