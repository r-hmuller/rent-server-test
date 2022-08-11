<?php

namespace App\Controller;

use App\Service\ServerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    #[Route('/servers', name: 'server_list')]
    public function list(Request $request, ServerService $serverService): JsonResponse
    {
        $queryParams = $request->query->all();
        $servers = $serverService->getServersByFilters($queryParams);
        return $this->json($servers, 200);
    }
}