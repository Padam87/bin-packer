<?php

namespace Padam87\BinPacker\FitHeuristic;

use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;

/**
 * Minimize the leftover area ~= picks the smallest node a block can fit in.
 */
class BestAreaFit implements FitHeuristic
{
    public function __invoke(Node $node, Block $block): float
    {
        return $node->getWidth() * $node->getHeight() - $block->getWidth() * $block->getHeight();
    }

    public function getName(): string
    {
        return 'BLSF';
    }
}
