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

        try {
            $chosenStrategy = $factory->getDistributionStrategy($distributionData);
            $chosenStrategy->handleDistribution($distributionData);

            return new JsonResponse(['status' => 'ok'], 200);
        } catch (EntityNotFoundException $notFoundHttpException) {
            return new JsonResponse(['status' => 'not found'], 404);
        } catch (\InvalidArgumentException $invalidArgumentException) {
            return new JsonResponse(['status' => $invalidArgumentException->getMessage()], 500);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error'], 500);
        }
    }
}
