<?php

namespace Padam87\BinPacker\Tests;

use Padam87\BinPacker\BinPacker;
use Padam87\BinPacker\FitHeuristic\BestAreaFit;
use Padam87\BinPacker\GifMaker;
use Padam87\BinPacker\Model\Bin;
use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\SplitHeuristic\MaximizeAreaSplit;
use Padam87\BinPacker\Visualizer;
use PHPUnit\Framework\TestCase;

class GifMakerTest extends TestCase
{
    public function testStepByStep()
    {
        $bin = new Bin(500, 500);

        $blocks = [];

        for ($i = 1; $i <= 5; $i++) {
            $w = rand(50, 250);
            $h = rand(50, 250);

            for ($j = 1; $j <= 5; $j++) {
                $blocks[] = new Block($w, $h, false, $i);
            }
        }

        $packer = new BinPacker(new BestAreaFit(), new MaximizeAreaSplit());
        $gifMaker = new GifMaker(new Visualizer());

        $packer->pack($bin, $blocks, $gifMaker);

        $gif = $gifMaker->create();

        $this->assertEquals(count($blocks), $gif->getNumberImages());
        $this->assertInstanceOf(\Imagick::class, $gif);
    }
}
