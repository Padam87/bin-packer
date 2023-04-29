<?php

namespace Padam87\BinPacker\FitHeuristic;

use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;

class NegateScoreFit implements FitHeuristic
{
    public function __construct(private FitHeuristic $fitHeuristic)
    {
    }

    public function __invoke(Node $node, Block $block): float
    {
        return -1 * ($this->fitHeuristic)($node, $block);
    }

    public function getName(): string
    {
        return 'NEG_' . $this->fitHeuristic->getName();
    }
}
