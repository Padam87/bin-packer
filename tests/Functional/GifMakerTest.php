<?php

namespace Padam87\BinPacker\Tests;

use Padam87\BinPacker\BinPacker;
use Padam87\BinPacker\GifMaker;
use Padam87\BinPacker\Model\Bin;
use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Visualizer;
use PHPUnit\Framework\TestCase;

class GifMakerTest extends TestCase
{
    public function testStepByStep()
    {
        $bin = new Bin(300, 300, true);

        $blocks = [];

        for ($i = 1; $i <= 5; $i++) {
            $w = rand(50, 250);
            $h = rand(50, 250);

            for ($j = 1; $j <= 5; $j++) {
                $blocks[] = new Block($w, $h, false, $i);
            }
        }

        $packer = new BinPacker();
        $gifMaker = new GifMaker(new Visualizer());

        $blocks = $packer->pack($bin, $blocks, $gifMaker);

        $gif = $gifMaker->create();

        //$gif->writeImages('bin.gif', true);

        $this->assertCount(count($blocks), $gifMaker->getImages());
        $this->assertInstanceOf(\Imagick::class, $gif);
    }
}
