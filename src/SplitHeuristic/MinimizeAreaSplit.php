<?php

namespace Padam87\BinPacker\SplitHeuristic;

use Padam87\BinPacker\Enum\SplitDirection;
use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;

class MinimizeAreaSplit implements SplitHeuristic
{
    public function __invoke(Node $node, Block $block): SplitDirection
    {
        $w = $node->getWidth() - $block->getWidth();
        $h = $node->getWidth() - $block->getHeight();

        return ($block->getWidth() * $h > $w * $block->getHeight()) ? SplitDirection::Horizontal : SplitDirection::Vertical;
    }

    public function getName(): string
    {
        return 'MINAS';
    }
}
