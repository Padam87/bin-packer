<?php

namespace Padam87\BinPacker;

use Padam87\BinPacker\Enum\SplitDirection;
use Padam87\BinPacker\FitHeuristic\FitHeuristic;
use Padam87\BinPacker\Model\Bin;
use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;
use Padam87\BinPacker\SplitHeuristic\SplitHeuristic;

class BinPacker
{
    public function __construct(private FitHeuristic $fitHeuristic, private SplitHeuristic $splitHeuristic)
    {
    }

    public function pack(Bin $bin, array $blocks, ?callable $stepCallback = null, ?State $state = null): State
    {
        $root = new Node(0, 0, $bin->getWidth(), $bin->getHeight());

        if ($state === null) {
            $state = new State($root);
        }

        $bin->setNode($root);

        /** @var Block $block */
        foreach ($blocks as $block) {
            if ($block->getNode()) {
                continue;
            }

            $node = $this->findBestNodeForBlock($state, $block);

            if ($node !== null) {
                $this->splitNode($state, $node, $block);

                $block->setNode($node);
            }

            if ($stepCallback) {
                $stepCallback($bin, $state, $node, $block);
            }
        }

        return $state;
    }

    private function findBestNodeForBlock(State $state, Block $block): ?Node
    {
        $rotated = $block->getRotatedClone();

        $bestNode = null;
        $bestScore = PHP_INT_MAX;
        $shouldRotate = false;

        foreach ($state->getFreeNodes() as $freeNode) {

            if ($freeNode->canContain($block)) {
                $score = ($this->fitHeuristic)($freeNode, $block);

                if ($score < $bestScore) {
                    $bestNode = $freeNode;
                    $bestScore = $score;
                    $shouldRotate = false;
                }
            }

            if ($block->isRotatable() && $freeNode->canContain($rotated)) {
                $score = ($this->fitHeuristic)($freeNode, $rotated);

                if ($score < $bestScore) {
                    $bestNode = $freeNode;
                    $bestScore = $score;
                    $shouldRotate = true;
                }
            }
        }

        if ($shouldRotate) {
            $block->rotate();
        }

        return $bestNode;
    }

    private function splitNode(State $state, Node $node, Block $block)
    {   $w = $block->getWidth();
        $h = $block->getHeight();

        $direction = ($this->splitHeuristic)($node, $block);

        if ($direction === SplitDirection::Horizontal) {
            $down = new Node($node->getX(), $node->getY() + $h, $node->getWidth(), $node->getHeight() - $h);
            $right = new Node($node->getX() + $w, $node->getY(), $node->getWidth() - $w, $h);
        } elseif ($direction === SplitDirection::Vertical) {
            $down = new Node($node->getX(), $node->getY() + $h, $w, $node->getHeight() - $h);
            $right = new Node($node->getX() + $w, $node->getY(), $node->getWidth() - $w, $node->getHeight());
        }

        $node->setUsed(true);
        $node->setWidth($w);
        $node->setHeight($h);
        $node->setBlock($block);

        if ($right->isValid()) {
            $state->addNode($right);
        }

        if ($down->isValid()) {
            $state->addNode($down);
        }
    }
}
