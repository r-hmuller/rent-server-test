<?php

namespace App\Controller;

use App\Service\DatabaseServerService;
use App\Service\InMemoryServerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServerController extends AbstractController
{
    #[Route('/servers', name: 'server_list')]
    public function list(Request $request, DatabaseServerService $serverService): JsonResponse
    {
        $queryParams = $request->query->all();
        $servers = $serverService->getServersByFilters($queryParams);
        return $this->json($servers);
    }
    #[Route('/servers-inmemory', name: 'server_list_in_memory')]
    public function listInMemory(Request $request, InMemoryServerService $serverService): JsonResponse
    {
        $queryParams = $request->query->all();
        $servers = $serverService->getServers($queryParams);
        return $this->json($servers);
    }
}