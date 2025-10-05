<?php

namespace App\Controller;

use App\Resource\SimulationRequest;
use App\Service\SimulationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class SimulationController extends AbstractController
{
    public function __construct(
        private readonly SimulationService $simulationService,
    ){
    }

    #[Route('api/v1/simulation', methods: Request::METHOD_GET)]
    public function simulate(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        SimulationRequest $request) : JsonResponse
    {
        $width = $request->width ?? 3;
        $height = $request->height ?? 3;
        $indexI = $request->indexI ?? 0;
        $indexJ = $request->indexJ ?? 0;
        $initial = [[$indexI, $indexJ]];
        $probab = $request->probab ?? 0.5;

        if ($indexI >= $height || $indexJ >= $width || $probab < 0 || $probab > 1) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $steps = $this->simulationService->simulate($height, $width, $probab, $initial);

        return new JsonResponse($steps, Response::HTTP_OK);
    }
}
