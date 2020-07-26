<?php

namespace App\Controller;

use App\Distribution\ImageDistributionStrategy;
use App\Distribution\VideoDistributionStrategy;
use App\DTO\DistributionDTO;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param ImageDistributionStrategy $imageStrategy
     * @param VideoDistributionStrategy $videoStrategy
     * @return JsonResponse
     */
    public function indexAction(
        Request $request,
        ImageDistributionStrategy $imageStrategy,
        VideoDistributionStrategy $videoStrategy
    ) {
        $distributionData = DistributionDTO::fromRequest($request);

        $success = false;
        try {
            if ($distributionData->getType() === 'image') {
                $success = $imageStrategy->handleDistribution($distributionData);
            } elseif ($distributionData->getType() === 'video') {
                $success = $videoStrategy->handleDistribution($distributionData);
            }
        } catch (EntityNotFoundException $notFoundHttpException) {
            return new JsonResponse(['status' => 'not found'], 404);
        }

        if ($success) {
            return new JsonResponse(['status' => 'ok'], 200);
        }

        return new JsonResponse(['status' => 'error'], 500);
    }
}
