<?php

namespace App\Service;

class InMemoryServerService extends ServerService
{
    private $servers;

    public function __construct(SpreadsheetService $spreadsheetService)
    {
        $this->servers = $spreadsheetService->getServersInMemory();
    }

    public function getServers(array $filters):array
    {
        $entities = $this->servers;

        if (!empty($filters)) {
            $sanitizedFilters = $this->sanitizeFilters($filters);
            $entities = $this->filterServers($sanitizedFilters, $entities);
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

    private function filterServers(array $sanitizedFilters, $entities)
    {
        $filteredServers = [];
        foreach ($entities as $entity) {
            $isValid = true;
            foreach ($sanitizedFilters as $filter => $value) {
                switch ($filter) {
                    case 'location':
                        if ($entity->getLocation()->getName() !== $value) $isValid = false;
                        break;
                    case 'ram':
                        if (!in_array($entity->getRamQuantity(), $value)) $isValid = false;
                        break;
                    case 'hardDiskType':
                        if ($entity->getHardDiskType() !== $value) $isValid = false;
                        break;
                    case 'hardDiskCapacity':
                        if ($entity->getHardDiskTotalCapacityGb() < (int) $value) $isValid = false;
                        break;
                    default:
                        break;
                }


            }
            if ($isValid) $filteredServers[] = $entity;

        }
        return $filteredServers;
    }
}