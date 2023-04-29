<?php

namespace Padam87\BinPacker\FitHeuristic;

use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;

/**
 * Assumes all blocks are the same size!
 * Minimize the modulo of the node and block.
 */
class AssumeSameBlocksFit implements FitHeuristic
{
    public function __invoke(Node $node, Block $block): float
    {
        $remainderH = $node->getHeight() % $block->getHeight();
        $percentH = $remainderH / $node->getHeight() * 100;

        $remainderW = $node->getWidth() % $block->getWidth();
        $percentW = $remainderW / $node->getWidth() * 100;

        return $percentH + $percentW;
    }

    public function getName(): string
    {
        return 'ASBF';
    }
}
