<?php

namespace App\Console\Commands;

use App\Models\House;
use Illuminate\Console\Command;

class ImportData extends Command
{
    const NAME = 0;
    const PRICE = 1;
    const BEDROOMS = 2;
    const BATHROOMS = 3;
    const STOREYS = 4;
    const GARAGES = 5;

    protected $signature = 'import-data {file=data.csv} {--W|without-header}';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $fileName = $this->argument('file');

        if (!is_file($filePath = storage_path("app/import/$fileName"))) {
            $this->comment("Invalid file '$fileName'.");
        } else {
            $this->comment("Import data from file '$filePath'");
            $total = 0;
            $added = 0;
            $file = fopen($filePath, 'r');
            $header = !$this->option('without-header');

            while ($line = fgetcsv($file, 0, ',')) {
                if ($header) {
                    $header = false;
                } else {
                    if (strtolower($line[self::NAME]) === 'name') {
                        continue;
                    }

                    if (House::where('name', $line[self::NAME])->exists()) {
                        continue;
                    }

                    $house = new House([
                        'name' => (string) $line[self::NAME],
                        'price' => (int) $line[self::PRICE],
                        'bedrooms' => (int) $line[self::BEDROOMS],
                        'bathrooms' => (int) $line[self::BATHROOMS],
                        'storeys' => (int) $line[self::STOREYS],
                        'garages' => (int) $line[self::GARAGES],
                    ]);

                    if ($house->save()) {
                        ++$added;
                        echo '+';
                    } else {
                        echo '.';
                    }

                    ++$total;

                    if (!($total % 100)) {
                        echo $total.PHP_EOL;
                    }
                }
            }

            echo PHP_EOL;
            $this->comment("Added $added houses from $total.");
        }
    }
}
