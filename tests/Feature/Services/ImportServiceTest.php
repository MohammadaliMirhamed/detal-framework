
<?php

use App\Foundations\Providers\FileReader\JsonFileReader;
use PHPUnit\Framework\TestCase;
use App\Services\ImportService;
use App\Models\User;
use App\Configs\Env;

class ImportServiceTest extends TestCase
{
    public function setup(): void
    {
        (new Env(realpath(".") . '/.env'))->load();
    }

    public function testImportUsers()
    {
        $firstCountOfUser = (new User())->rowsCount();

        $filePath = __DIR__ . '/vendor/source.json';

        $importResult = (new ImportService())->users(new JsonFileReader($filePath));

        $secondCountOfUser = (new User())->rowsCount();
                        
        $this->assertEquals($secondCountOfUser, $firstCountOfUser + $importResult['statistic']['imported']);
    }
}