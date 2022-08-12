<?php

namespace App\Service;

use App\Entity\Machine;
use App\Repository\LocationRepository;
use App\Repository\MachineRepository;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use Psr\Log\LoggerInterface;

class SpreadsheetService
{
    private LoggerInterface $logger;
    private LocationRepository $locationRepository;
    private MachineRepository $machineRepository;
    private string $projectDir;

    public function __construct(LoggerInterface $logger, LocationRepository $locationRepository, MachineRepository $machineRepository, string $projectDir)
    {
        $this->logger = $logger;
        $this->locationRepository = $locationRepository;
        $this->machineRepository = $machineRepository;
        $this->projectDir = $projectDir;
    }

    public function storeOnDatabase():void
    {
        $lines = $this->readXlsFile('servers.xlsx');
        foreach ($lines as $line) {
            try {
                $machine = $this->generateMachineEntity($line);
                $this->machineRepository->add($machine, true);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $row = implode(",", $line);
                $this->logger->error("Exception {$e->getMessage()} occured when processing line \"$row\" ");
            }
        }
    }

    public function getServersInMemory():array
    {
        $servers = [];
        $lines = $this->readXlsFile('servers.xlsx');
        foreach ($lines as $line) {
            try {
                 $servers[] = $this->generateMachineEntity($line);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $row = implode(",", $line);
                $this->logger->error("Exception {$e->getMessage()} occured when processing line \"$row\" ");
            }
        }
        return $servers;
    }

    public function readXlsFile($fileName):array
    {
        $lines = [];
        $xlsFile = "$this->projectDir/public/$fileName";
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($xlsFile);

        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            if ($row->getRowIndex() > 1) {
                $line = [];
                foreach ($cellIterator as $cell) {
                    $line[] = $cell->getValue();
                }
                if (!is_null($line[0])) $lines[] = $line;
            }
        }

        return $lines;
    }

    public function generateMachineEntity($row):Machine
    {
        if (count($row) < 4) {
            throw new \LogicException("The row must have 4 cells to be valid. Please check the entry");
        }

        $model = $row[0];
        $ram = $this->generateRamData($row[1]);
        $hdd = $this->generateHddData($row[2]);
        $location = $row[3];
        $price = $this->generatePriceData($row[4]);

        $machine = new Machine();
        $machine->setName($model);
        $machine->setRamQuantity($ram['quantity']);
        $machine->setRamType($ram['type']);
        $machine->setHardDiskQuantity($hdd['quantity']);
        $machine->setHardDiskSize($hdd['size']);
        $machine->setHardDiskType($hdd['type']);
        $machine->setHardDiskTotalCapacityGb($hdd['capacity']);
        $machine->setPrice((string) $price['value']);
        $machine->setCurrency($price['currency']);
        $machine->setLocation($this->locationRepository->getOrCreateLocationByName($location));

        return $machine;
    }

    private function generatePriceData(string $priceInput):array
    {
        $price = [];
        if (str_starts_with($priceInput, '$')) {
            $price['currency'] = 'dollar';
        } else if (str_starts_with($priceInput, '€')) {
            $price['currency'] = 'euro';
        } else if (str_starts_with($priceInput, 'S$')) {
            $price['currency'] = 'singapore dollar';
        }
        $price['value'] = filter_var($priceInput, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
        return $price;
    }

    /**
     * The expected format of the $ramImput is "16GBDDR4". Using explode,
     * the first element of the array is the RAM quantity, and the second element
     * is the type of the RAM (DDR3 or DDR4).
     *
     * @param string $ramInput
     * @return array
     */
    private function generateRamData(string $ramInput):array
    {
        $ramExploded = explode('GB', $ramInput);
        if (count($ramExploded) < 2) {
            throw new \LogicException("Couldn't split the value. Please check the RAM input: $ramInput");
        }
        return [
            'quantity' => $ramExploded[0],
            'type' => $ramExploded[1]
        ];
    }

    private function generateHddData(string $rawInput): array
    {
        $separator = str_contains($rawInput, 'GB') ? 'GB' : 'TB';
        $hddExploded = explode($separator, $rawInput);
        if (count($hddExploded) < 2) {
            throw new \LogicException("Couldn't split the value. Please check the HDD input");
        }
        $hddInfos = [
            'type' => $hddExploded[1]
        ];

        $hddSizeExploded = explode('x', strtolower($hddExploded[0]));
        $hddInfos['quantity'] = (int)$hddSizeExploded[0];
        $hddInfos['size'] = (int)$hddSizeExploded[1];

        $capacity = $hddInfos['quantity'] * $hddInfos['size'];
        if ($separator === 'TB') {
            $capacity *= 1000;
            $hddInfos['size'] *= 1000;
        }

        $hddInfos['capacity'] = $capacity;
        return $hddInfos;
    }
}