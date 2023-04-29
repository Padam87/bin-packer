<?php

namespace Padam87\BinPacker\SplitHeuristic;

use Padam87\BinPacker\Enum\SplitDirection;
use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;

class ShorterAxisSplit implements SplitHeuristic
{
    public function __invoke(Node $node, Block $block): SplitDirection
    {
        return $node->getWidth() <= $node->getHeight() ? SplitDirection::Horizontal : SplitDirection::Vertical;
    }

    public function getName(): string
    {
        return 'SAS';
    }
}
