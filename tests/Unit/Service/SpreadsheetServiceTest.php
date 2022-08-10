<?php

namespace App\Tests\Unit\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SpreadsheetServiceTest extends KernelTestCase
{
    private $service;

    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->service = $container->get('spreadsheetService');
        parent::setUp();
    }

    public function testNotEnoughArgumentsonRow(): void
    {
        $this->expectException(\LogicException::class);
        $this->service->generateMachineEntity(['Only', '3', 'elements']);
    }

    public function testRamInputWrong(): void
    {
        $this->expectException(\LogicException::class);
        $this->service->generateMachineEntity(['Server 1', '16Gb', '2x2TBSATA2']);
    }

    public function testHddInputWrong(): void
    {
        $this->expectException(\LogicException::class);
        $this->service->generateMachineEntity(['Server 1', '16GBDDR4', '2x2TSATA2']);
    }
}