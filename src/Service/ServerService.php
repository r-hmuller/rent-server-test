<?php

namespace App\Service;

/*
 * I decided to use this as abstract class to allow using DatabaseServerService and InMemoryService at the same time.
 * (As described on the README.md)
 * Another option would be converting this abstract class to an Interface (and moving this logic to another place -
 * maybe using Trait), and using the Dependency Injection to inject one of the services on the Controller.
 */
abstract class ServerService
{
    private array $allowedFilters = ['ram', 'hardDiskCapacity', 'location', 'hardDiskType'];

    protected function getCurrencySymbol (string $currency)
    {
        return match ($currency) {
            'euro' => '€',
            'Singapore dollar' => 'S$',
            default => '$',
        };
    }

    protected function sanitizeFilters(array $filters)
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