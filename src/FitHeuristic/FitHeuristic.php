<?php

namespace Padam87\BinPacker\FitHeuristic;

use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;

interface FitHeuristic
{
    public function __invoke(Node $node, Block $block): float;

    public function getName(): string;
}
