<?php

namespace App\Controller;

use App\Mocks\FakeRepository;
use App\Mocks\Quality;
use Doctrine\ORM\EntityNotFoundException;
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
    public function indexAction(Request $request, FakeRepository $repo)
    {
        $filename = $request->query->get('file');
        $qualityKey = $request->query->get('quality');
        $directory = $request->query->get('directory');
        $type = $request->query->get('type');

        $logger = $this->get('logger');
        $success = true;
        try {
            if ($type === 'image') {
                $logger->debug('Request is of type: image.');

                $firstUnderscore = strpos($filename, '_');
                $sU = strpos($filename, '.', $firstUnderscore + 1);
                $id = (int) substr($filename, $firstUnderscore + 1, $sU - $firstUnderscore - 1);
                $image = $repo->getImageById($id);

                $image->setDirectory($directory);
                $image->setIsDistributed(true);
                $repo->save($image);

                $success = true;
            } elseif ($type === 'video') {
                $logger->debug('Request is of type: video.');

                $firstUnderscore = strpos($filename, '_');
                $secondUnderscore = strpos($filename, '_', $firstUnderscore + 1);
                $id = (int) substr($filename, $firstUnderscore + 1, $secondUnderscore - $firstUnderscore - 1);

                $video = $repo->getVideoById($id);

                $video->setDirectory($directory);
                $video->setIsDistributed(true);

                $quality = $repo->getQualityByKey($qualityKey);
                $video->setQuality($quality);
                $repo->save($video);

                $success = true;
            }
        } catch (EntityNotFoundException $notFoundHttpException) {
            $success = false;
        }

        if ($success) {
            return new JsonResponse(['status' => 'ok'], 200);
        }

        return new JsonResponse(['status' => 'error', 500]);
    }
}
