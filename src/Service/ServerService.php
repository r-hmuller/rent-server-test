<?php

namespace App\Service;

use App\Entity\Machine;
use App\Repository\MachineRepository;

class ServerService
{
    private MachineRepository $machineRepository;
    private array $allowedFilters = ['ram', 'hardDiskCapacity', 'location', 'hardDiskType'];

    public function __construct(MachineRepository $machineRepository)
    {
        $this->machineRepository = $machineRepository;
    }

    public function getServersByFilters(array $filters)
    {
        if (empty($filters)) {
            return $this->machineRepository->findAll();
        }

        $sanitizedFilters = $this->sanitizeFilters($filters);
        $entities = $this->machineRepository->getByFilters($sanitizedFilters);
        $customJsonResponse = [];
        foreach ($entities as $entity) {
            $formattedEntity = [
                'id' => $entity->getId(),
                'name' => $entity->getName(),
                'storage' => $entity->getHardDiskQuantity() .'X'. $entity->getHardDiskSize() . "-" . $entity->getHardDiskType(),
                'ram' => $entity->getRamQuantity() . '' . $entity->getRamType(),
                'location' => $entity->getLocation()->getName(),
                'price' => $this->getCurrencySymbol($entity->getCurrency()) . '' . $entity->getPrice()
            ];
            $customJsonResponse[] = $formattedEntity;
        }
        return $customJsonResponse;
    }

    private function getCurrencySymbol (string $currency)
    {
        return match ($currency) {
            'euro' => '€',
            'Singapore dollar' => 'S$',
            default => '$',
        };
    }

    private function sanitizeFilters(array $filters)
    {
        $sanitizedFilters = [];
        foreach ($filters as $filter => $value) {
            if (!in_array($filter, $this->allowedFilters)) continue;
            if ($filter === 'hardDiskCapacity') {
                $capacity = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                if (str_contains($value, 'TB')) {
                    $capacity = $capacity * 1000;
                }
                $sanitizedFilters[$filter] = $capacity;
                continue;
            }
            if ($filter === 'ram') {
                $sanitizedFilters[$filter] = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                continue;
            }
            $sanitizedFilters[$filter] = $value;
        }
        return $sanitizedFilters;
    }
}