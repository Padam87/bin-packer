<?php

namespace Padam87\BinPacker\FitHeuristic;

use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;

/**
 * Minimize the length of the shorter leftover side.
 */
class BestShortSideFit implements FitHeuristic
{
    public function __invoke(Node $node, Block $block): float
    {
        return min(abs($node->getWidth() - $block->getWidth()), $node->getHeight() - $block->getHeight());
    }

    public function getName(): string
    {
        return 'BSSF';
    }
}
