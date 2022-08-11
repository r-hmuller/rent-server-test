<?php

namespace App\Service;

use App\Entity\Machine;
use App\Repository\MachineRepository;

class DatabaseServerService extends ServerService
{
    private MachineRepository $machineRepository;

    public function __construct(MachineRepository $machineRepository)
    {
        $this->machineRepository = $machineRepository;
    }

    public function getServersByFilters(array $filters)
    {
        if (empty($filters)) {
            $entities = $this->machineRepository->findAll();
        } else {
            $sanitizedFilters = $this->sanitizeFilters($filters);
            $entities = $this->machineRepository->getByFilters($sanitizedFilters);
        }

        $customJsonResponse = [];
        foreach ($entities as $entity) {
            $formattedEntity = [
                'id' => $entity->getId(),
                'name' => $entity->getName(),
                'storage' => $entity->getHardDiskQuantity() .'X'. $entity->getHardDiskSize() . "-" . $entity->getHardDiskType(),
                'ram' => $entity->getRamQuantity() . '' . $entity->getRamType(),
                'location' => $entity->getLocation()->getName(),
                'price' => $this->getCurrencySymbol($entity->getCurrency()) . ' ' . $entity->getPrice()
            ];
            $customJsonResponse[] = $formattedEntity;
        }
        return $customJsonResponse;
    }
}