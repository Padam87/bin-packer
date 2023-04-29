<?php

namespace Padam87\BinPacker\Tests;

use Padam87\BinPacker\BinPacker;
use Padam87\BinPacker\FitHeuristic\BestAreaFit;
use Padam87\BinPacker\Model\Bin;
use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\SplitHeuristic\MaximizeAreaSplit;
use PHPUnit\Framework\TestCase;

class BinPackerTest extends TestCase
{
    public function testSimple()
    {
        $bin = new Bin(1000, 1000);
        $blocks = [
            new Block(100, 100),
            new Block(300, 100),
            new Block(175, 125),
            new Block(200, 75),
            new Block(200, 75),
        ];

        $packer = new BinPacker(new BestAreaFit(), new MaximizeAreaSplit());
        $packer->pack($bin, $blocks);

        foreach ($blocks as $block) {
            $this->assertTrue($block->getNode() && $block->getNode()->isUsed());
        }
    }

    public function testRotation()
    {
        $bin = new Bin(2000, 100);

        $rotatable = new Block(100, 1000);
        $nonRotatable = new Block(100, 1000, false);

        $blocks = [$rotatable, $nonRotatable];

        $packer = new BinPacker(new BestAreaFit(), new MaximizeAreaSplit());
        $packer->pack($bin, $blocks);

        $this->assertTrue($rotatable->getNode() && $rotatable->getNode()->isUsed());
        $this->assertFalse($nonRotatable->getNode() && $nonRotatable->getNode()->isUsed());
    }

    public function testOverflow()
    {
        $bin = new Bin(1000, 1000);

        $blockTemplate = new Block(100, 100);

        $blocks = [];

        for ($i = 1; $i <= 200; $i++) {
            $blocks[] = clone $blockTemplate;
        }

        $packer = new BinPacker(new BestAreaFit(), new MaximizeAreaSplit());
        $packer->pack($bin, $blocks);

        $packed = array_filter($blocks, function (Block $block) {
            return $block->getNode() && $block->getNode()->isUsed();
        });

        $this->assertCount(200, $blocks);
        $this->assertCount(100, $packed);
    }

    public function testGrowth()
    {
        $this->markTestIncomplete('Not yet implemented in the new version');

        $bin = new Bin(1000, 1000, true);

        $blockTemplate = new Block(100, 100);

        $blocks = [];

        for ($i = 1; $i <= 200; $i++) {
            $blocks[] = clone $blockTemplate;
        }

        $packer = new BinPacker(new BestAreaFit(), new MaximizeAreaSplit());
        $packer->pack($bin, $blocks);

        $packed = array_filter($blocks, function (Block $block) {
            return $block->getNode() && $block->getNode()->isUsed();
        });

        $this->assertCount(200, $blocks);
        $this->assertCount(200, $packed);

        $this->assertEquals(1500, $bin->getWidth());
        $this->assertEquals(1400, $bin->getHeight());
    }
}
