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
        $romPath = __DIR__ . '/../../tests/fixtures/roms/ibm_logo.ch8';

        $this->memory->loadRom($romPath);

        for ($i = 0; $i < 512; $i++) {
            $arr[$i] = 0;
        }

        $arr[512] = 0x00;
        $arr[513] = 0xe0;
        $arr[514] = 0xa2;
        $arr[515] = 0x2a;
        $arr[516] = 0x60;
        $arr[517] = 0x0c;
        $arr[518] = 0x61;
        $arr[519] = 0x08;
        $arr[520] = 0xd0;
        $arr[521] = 0x1f;
        $arr[522] = 0x70;
        $arr[523] = 0x09;
        $arr[524] = 0xa2;
        $arr[525] = 0x39;
        $arr[526] = 0xd0;
        $arr[527] = 0x1f;
        $arr[528] = 0xa2;
        $arr[529] = 0x48;
        $arr[530] = 0x70;
        $arr[531] = 0x08;
        $arr[532] = 0xd0;
        $arr[533] = 0x1f;
        $arr[534] = 0x70;
        $arr[535] = 0x04;
        $arr[536] = 0xa2;
        $arr[537] = 0x57;
        $arr[538] = 0xd0;
        $arr[539] = 0x1f;
        $arr[540] = 0x70;
        $arr[541] = 0x08;
        $arr[542] = 0xa2;
        $arr[543] = 0x66;
        $arr[544] = 0xd0;
        $arr[545] = 0x1f;
        $arr[546] = 0x70;
        $arr[547] = 0x08;
        $arr[548] = 0xa2;
        $arr[549] = 0x75;
        $arr[550] = 0xd0;
        $arr[551] = 0x1f;
        $arr[552] = 0x12;
        $arr[553] = 0x28;
        $arr[554] = 0xff;
        $arr[555] = 0x00;
        $arr[556] = 0xff;
        $arr[557] = 0x00;
        $arr[558] = 0x3c;
        $arr[559] = 0x00;
        $arr[560] = 0x3c;
        $arr[561] = 0x00;
        $arr[562] = 0x3c;
        $arr[563] = 0x00;
        $arr[564] = 0x3c;
        $arr[565] = 0x00;
        $arr[566] = 0xff;
        $arr[567] = 0x00;
        $arr[568] = 0xff;
        $arr[569] = 0xff;
        $arr[570] = 0x00;
        $arr[571] = 0xff;
        $arr[572] = 0x00;
        $arr[573] = 0x38;
        $arr[574] = 0x00;
        $arr[575] = 0x3f;
        $arr[576] = 0x00;
        $arr[577] = 0x3f;
        $arr[578] = 0x00;
        $arr[579] = 0x38;
        $arr[580] = 0x00;
        $arr[581] = 0xff;
        $arr[582] = 0x00;
        $arr[583] = 0xff;
        $arr[584] = 0x80;
        $arr[585] = 0x00;
        $arr[586] = 0xe0;
        $arr[587] = 0x00;
        $arr[588] = 0xe0;
        $arr[589] = 0x00;
        $arr[590] = 0x80;
        $arr[591] = 0x00;
        $arr[592] = 0x80;
        $arr[593] = 0x00;
        $arr[594] = 0xe0;
        $arr[595] = 0x00;
        $arr[596] = 0xe0;
        $arr[597] = 0x00;
        $arr[598] = 0x80;
        $arr[599] = 0xf8;
        $arr[600] = 0x00;
        $arr[601] = 0xfc;
        $arr[602] = 0x00;
        $arr[603] = 0x3e;
        $arr[604] = 0x00;
        $arr[605] = 0x3f;
        $arr[606] = 0x00;
        $arr[607] = 0x3b;
        $arr[608] = 0x00;
        $arr[609] = 0x39;
        $arr[610] = 0x00;
        $arr[611] = 0xf8;
        $arr[612] = 0x00;
        $arr[613] = 0xf8;
        $arr[614] = 0x03;
        $arr[615] = 0x00;
        $arr[616] = 0x07;
        $arr[617] = 0x00;
        $arr[618] = 0x0f;
        $arr[619] = 0x00;
        $arr[620] = 0xbf;
        $arr[621] = 0x00;
        $arr[622] = 0xfb;
        $arr[623] = 0x00;
        $arr[624] = 0xf3;
        $arr[625] = 0x00;
        $arr[626] = 0xe3;
        $arr[627] = 0x00;
        $arr[628] = 0x43;
        $arr[629] = 0xe0;
        $arr[630] = 0x00;
        $arr[631] = 0xe0;
        $arr[632] = 0x00;
        $arr[633] = 0x80;
        $arr[634] = 0x00;
        $arr[635] = 0x80;
        $arr[636] = 0x00;
        $arr[637] = 0x80;
        $arr[638] = 0x00;
        $arr[639] = 0x80;
        $arr[640] = 0x00;
        $arr[641] = 0xe0;
        $arr[642] = 0x00;
        $arr[643] = 0xe0;

        for ($i = 644; $i < 4096; $i++) {
            $arr[$i] = 0;
        }

        $this->assertSame($arr, $this->memory->getMemory());
    }

    public function testFont(): void
    {
        $this->memory->loadFont(__DIR__ . '/../../assets/fonts/standard.json');
        $expected = [
            0 => 0xf0,
            1 => 0x90,
            2 => 0x90,
            3 => 0x90,
            4 => 0xf0,
            5 => 0x20,
            6 => 0x60,
            7 => 0x20,
            8 => 0x20,
            9 => 0x70,
            10 => 0xf0,
            11 => 0x10,
            12 => 0xf0,
            13 => 0x80,
            14 => 0xf0,
            15 => 0xf0,
            16 => 0x10,
            17 => 0xf0,
            18 => 0x10,
            19 => 0xf0,
            20 => 0x90,
            21 => 0x90,
            22 => 0xf0,
            23 => 0x10,
            24 => 0x10,
            25 => 0xf0,
            26 => 0x80,
            27 => 0xf0,
            28 => 0x10,
            29 => 0xf0,
            30 => 0xf0,
            31 => 0x80,
            32 => 0xf0,
            33 => 0x90,
            34 => 0xf0,
            35 => 0xf0,
            36 => 0x10,
            37 => 0x20,
            38 => 0x40,
            39 => 0x40,
            40 => 0xf0,
            41 => 0x90,
            42 => 0xf0,
            43 => 0x90,
            44 => 0xf0,
            45 => 0xf0,
            46 => 0x90,
            47 => 0xf0,
            48 => 0x10,
            49 => 0xf0,
            50 => 0xf0,
            51 => 0x90,
            52 => 0xf0,
            53 => 0x90,
            54 => 0x90,
            55 => 0xe0,
            56 => 0x90,
            57 => 0xe0,
            58 => 0x90,
            59 => 0xe0,
            60 => 0xf0,
            61 => 0x80,
            62 => 0x80,
            63 => 0x80,
            64 => 0xf0,
            65 => 0xe0,
            66 => 0x90,
            67 => 0x90,
            68 => 0x90,
            69 => 0xe0,
            70 => 0xf0,
            71 => 0x80,
            72 => 0xf0,
            73 => 0x80,
            74 => 0xf0,
            75 => 0xf0,
            76 => 0x80,
            77 => 0xf0,
            78 => 0x80,
            79 => 0x80
        ];

        $this->assertSame($expected, array_slice($this->memory->getMemory(), 0, 80));
    }

    public function testGetInstruction(): void
    {
        $this->memory->loadRom(__DIR__ . '/../../tests/fixtures/roms/ibm_logo.ch8');
        $instruction = $this->memory->fetchInstruction(Memory::ROM_START);
        $this->assertSame(0, $instruction->byte1);
        $this->assertSame(0xe0, $instruction->byte2);
        $this->assertSame(0, $instruction->nibble1);
        $this->assertSame(0, $instruction->nibble2);
        $this->assertSame(0xe, $instruction->nibble3);
        $this->assertSame(0, $instruction->nibble4);
        $this->assertSame(0x0e0, $instruction->address);
    }
}
