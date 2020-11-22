<?php

namespace App\Controller;

use App\Distribution\DistributionFactory;
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
     * @param DistributionFactory $factory
     * @return JsonResponse
     */
    public function indexAction(
        Request $request,
        DistributionFactory $factory
    ) {
        $distributionData = DistributionDTO::fromRequest($request);

        $success = false;
        try {
            $chosenStrategy = $factory->getDistributionStrategy($distributionData);

            $success = $chosenStrategy->handleDistribution($distributionData);
        } catch (EntityNotFoundException $notFoundHttpException) {
            return new JsonResponse(['status' => 'not found'], 404);
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $success = false;
        }

        if ($success) {
            return new JsonResponse(['status' => 'ok'], 200);
        }

        return new JsonResponse(['status' => 'error'], 500);
    }
}
