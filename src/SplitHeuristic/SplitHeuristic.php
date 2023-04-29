<?php

namespace Padam87\BinPacker\SplitHeuristic;

use Padam87\BinPacker\Enum\SplitDirection;
use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;

interface SplitHeuristic
{
    public function __invoke(Node $node, Block $block): SplitDirection;

    public function getName(): string;
}
