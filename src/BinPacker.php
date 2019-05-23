<?php

namespace Padam87\BinPacker;

use Padam87\BinPacker\Model\Bin;
use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;

class BinPacker
{
    /**
     * @param Bin $bin
     * @param Block[] $blocks
     * @param callable|null $stepCallback
     *
     * @return Block[]
     */
    public function pack(Bin $bin, array $blocks, ?callable $stepCallback = null): array
    {
        $blocks = $this->sort($blocks);

        $root = new Node(0, 0, $bin->getWidth(), $bin->getHeight());

        $bin->setNode($root);

        /** @var Block $block */
        foreach ($blocks as $block) {
            $node = $this->findNodeWithRotation($root, $block);

            if ($node === null && $bin->isGrowthAllowed()) {
                $this->grow($bin, $block->getWidth(), $block->getHeight());

                $node = $this->findNodeWithRotation($root, $block);
            }

            if ($node !== null) {
                $block->setNode($this->splitNode($node, $block->getWidth(), $block->getHeight()));
            }

            if ($stepCallback) {
                $stepCallback($bin, $blocks, $block);
            }
        }

        return $blocks;
    }

    private function findNodeWithRotation(Node $root, Block $block)
    {
        if (null === $node = $this->findNode($root, $block->getWidth(), $block->getHeight())) {
            if ($block->isRotatable()) {
                $block->rotate();

                if (null === $node = $this->findNode($root, $block->getWidth(), $block->getHeight())) {
                    $block->rotate(); // if it still won't fit, rotate it back -> prefer original if growth is enabled
                }
            }
        }

        return $node;
    }

    private function findNode(Node $node, $w, $h): ?Node
    {
        if ($node->isUsed()) {
            return $this->findNode($node->getRight(), $w, $h) ?: $this->findNode($node->getDown(), $w, $h);
        } elseif ($w <= $node->getWidth() && $h <= $node->getHeight()) {
            return $node;
        }

        return null;
    }

    private function splitNode(Node $node, $w, $h)
    {
        $node->setUsed(true);
        $node->setDown(new Node($node->getX(), $node->getY() + $h, $node->getWidth(), $node->getHeight() - $h));
        $node->setRight(new Node($node->getX() + $w, $node->getY(), $node->getWidth() - $w, $h));
        $node->setWidth($w);
        $node->setHeight($h);

        return $node;
    }

    private function grow(Bin $bin, $w, $h)
    {
        $canGrowRight = false;
        $this->canGrowRight($bin->getNode(), $w, $h, $canGrowRight);

        $shouldGrowRight = !($bin->getWidth() >= $bin->getHeight() + $h);

        if ($canGrowRight && $shouldGrowRight) {
            $bin->setWidth($bin->getWidth() + $w);

            $this->growRight($bin->getNode(), $w, $h);
        } else {
            $bin->setHeight($bin->getHeight() + $h);

            $this->growDown($bin->getNode(), $w, $h);
        }
    }

    public function canGrowRight(Node $node, $w, $h, &$can)
    {
        if (!$node->isUsed() && $node->getRight() === null) {
            if ($node->getHeight() >= $h) {
                $can = true;
            }
        }

        if ($node->getRight()) {
            $this->canGrowRight($node->getRight(), $w, $h, $can);
        }

        if ($node->getDown()) {
            $this->canGrowRight($node->getDown(), $w, $h, $can);
        }
    }

    public function growRight(Node $node, $w, $h)
    {
        if (!$node->isUsed() && $node->getRight() === null) {
            $node->setWidth($node->getWidth() + $w);
        }

        if ($node->getRight()) {
            $this->growRight($node->getRight(), $w, $h);
        }

        if ($node->getDown()) {
            $this->growRight($node->getDown(), $w, $h);
        }
    }

    public function growDown(Node $node, $w, $h)
    {
        if ($node->getDown()) {
            $this->growDown($node->getDown(), $w, $h);
        } else {
            $node->setHeight($node->getHeight() + $h);
        }
    }

    private function sort($blocks)
    {
        usort($blocks, function (Block $a, Block $b) {
            return $a->getHeight() < $b->getHeight() ;
        });

        return $blocks;
    }
}
