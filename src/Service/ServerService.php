<?php

namespace App\Service;

use App\Entity\Machine;
use App\Repository\MachineRepository;

class ServerService
{
    private MachineRepository $machineRepository;

    public function __construct(MachineRepository $machineRepository)
    {
        $this->machineRepository = $machineRepository;
    }

    public function getServersByFilters(array $filters)
    {
        if (empty($filters)) {
            return $this->machineRepository->findAll();
        }
        return $this->machineRepository->getByFilters($filters);
    }
}