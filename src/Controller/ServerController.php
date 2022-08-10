<?php

namespace App\Controller;

use App\Service\ServerService;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    #[Route('/servers', name: 'server_list')]
    public function list(Request $request, ServerService $serverService): JsonResponse
    {
        $servers = $serverService->getServersByFilters();
        return $this->json($servers);
    }
}