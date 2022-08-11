<?php

namespace App\Service;

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