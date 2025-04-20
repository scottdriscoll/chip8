<?php

namespace App\Tests\Systems;

use App\Systems\Memory;
use PHPUnit\Framework\TestCase;

class MemoryTest extends TestCase
{
    private Memory $memory;

    protected function setUp(): void
    {
        $this->memory = new Memory();
    }

    public function testLoadRom(): void
    {
        $path = __DIR__ . '/../../tests/fixtures/roms/ibm_logo.ch8';

        $this->memory->loadRom($path);

        for ($i = 0; $i < 512; $i++) {
            $arr[$i] = null;
        }

        $arr[512] = '00';
        $arr[513] = 'e0';
        $arr[514] = 'a2';
        $arr[515] = '2a';
        $arr[516] = '60';
        $arr[517] = '0c';
        $arr[518] = '61';
        $arr[519] = '08';
        $arr[520] = 'd0';
        $arr[521] = '1f';
        $arr[522] = '70';
        $arr[523] = '09';
        $arr[524] = 'a2';
        $arr[525] = '39';
        $arr[526] = 'd0';
        $arr[527] = '1f';
        $arr[528] = 'a2';
        $arr[529] = '48';
        $arr[530] = '70';
        $arr[531] = '08';
        $arr[532] = 'd0';
        $arr[533] = '1f';
        $arr[534] = '70';
        $arr[535] = '04';
        $arr[536] = 'a2';
        $arr[537] = '57';
        $arr[538] = 'd0';
        $arr[539] = '1f';
        $arr[540] = '70';
        $arr[541] = '08';
        $arr[542] = 'a2';
        $arr[543] = '66';
        $arr[544] = 'd0';
        $arr[545] = '1f';
        $arr[546] = '70';
        $arr[547] = '08';
        $arr[548] = 'a2';
        $arr[549] = '75';
        $arr[550] = 'd0';
        $arr[551] = '1f';
        $arr[552] = '12';
        $arr[553] = '28';
        $arr[554] = 'ff';
        $arr[555] = '00';
        $arr[556] = 'ff';
        $arr[557] = '00';
        $arr[558] = '3c';
        $arr[559] = '00';
        $arr[560] = '3c';
        $arr[561] = '00';
        $arr[562] = '3c';
        $arr[563] = '00';
        $arr[564] = '3c';
        $arr[565] = '00';
        $arr[566] = 'ff';
        $arr[567] = '00';
        $arr[568] = 'ff';
        $arr[569] = 'ff';
        $arr[570] = '00';
        $arr[571] = 'ff';
        $arr[572] = '00';
        $arr[573] = '38';
        $arr[574] = '00';
        $arr[575] = '3f';
        $arr[576] = '00';
        $arr[577] = '3f';
        $arr[578] = '00';
        $arr[579] = '38';
        $arr[580] = '00';
        $arr[581] = 'ff';
        $arr[582] = '00';
        $arr[583] = 'ff';
        $arr[584] = '80';
        $arr[585] = '00';
        $arr[586] = 'e0';
        $arr[587] = '00';
        $arr[588] = 'e0';
        $arr[589] = '00';
        $arr[590] = '80';
        $arr[591] = '00';
        $arr[592] = '80';
        $arr[593] = '00';
        $arr[594] = 'e0';
        $arr[595] = '00';
        $arr[596] = 'e0';
        $arr[597] = '00';
        $arr[598] = '80';
        $arr[599] = 'f8';
        $arr[600] = '00';
        $arr[601] = 'fc';
        $arr[602] = '00';
        $arr[603] = '3e';
        $arr[604] = '00';
        $arr[605] = '3f';
        $arr[606] = '00';
        $arr[607] = '3b';
        $arr[608] = '00';
        $arr[609] = '39';
        $arr[610] = '00';
        $arr[611] = 'f8';
        $arr[612] = '00';
        $arr[613] = 'f8';
        $arr[614] = '03';
        $arr[615] = '00';
        $arr[616] = '07';
        $arr[617] = '00';
        $arr[618] = '0f';
        $arr[619] = '00';
        $arr[620] = 'bf';
        $arr[621] = '00';
        $arr[622] = 'fb';
        $arr[623] = '00';
        $arr[624] = 'f3';
        $arr[625] = '00';
        $arr[626] = 'e3';
        $arr[627] = '00';
        $arr[628] = '43';
        $arr[629] = 'e0';
        $arr[630] = '00';
        $arr[631] = 'e0';
        $arr[632] = '00';
        $arr[633] = '80';
        $arr[634] = '00';
        $arr[635] = '80';
        $arr[636] = '00';
        $arr[637] = '80';
        $arr[638] = '00';
        $arr[639] = '80';
        $arr[640] = '00';
        $arr[641] = 'e0';
        $arr[642] = '00';
        $arr[643] = 'e0';

        for ($i = 644; $i < 4096; $i++) {
            $arr[$i] = null;
        }

        $this->assertSame($arr, $this->memory->getMemory());
    }
}
