<?php

namespace Padam87\BinPacker;

use Padam87\BinPacker\Model\Bin;
use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;

class Visualizer
{
    public function visualize(Bin $bin, array $blocks)
    {
        $image = new \Imagick();
        $image->newImage($bin->getWidth(), $bin->getHeight(), new \ImagickPixel('white'));

        $draw = new \ImagickDraw();

        $draw->setFillColor(new \ImagickPixel('whitesmoke'));
        $draw->setStrokeColor(new \ImagickPixel('black'));

        $this->markFreeSpace($draw, $bin->getNode());

        $draw->setFillColor(new \ImagickPixel('white'));

        /** @var Block $block */
        foreach ($blocks as $block) {
            $node = $block->getNode();

            if ($node == null || !$node->isUsed()) {
                continue;
            }

            $draw->rectangle($node->getX(), $node->getY(), $node->getX() + $block->getWidth(), $node->getY() + $block->getHeight());
            $draw->annotation($node->getX() + 10, $node->getY() + 20, $block->getId());
            $draw->annotation($node->getX() + 10, $node->getY() + 40, sprintf('%s x %s', $block->getWidth(), $block->getHeight()));
        }

        $image->drawImage($draw);

        return $image;
    }

    public function markFreeSpace(\ImagickDraw $draw, Node $node)
    {
        if (!$node->isUsed()) {
            $draw->rectangle(
                $node->getX(),
                $node->getY(),
                $node->getX() + $node->getWidth(),
                $node->getY() + $node->getHeight()
            );
            $draw->annotation($node->getX() + 10, $node->getY() + 20, 'free');
            $draw->annotation($node->getX() + 10, $node->getY() + 40, sprintf('%s x %s', $node->getWidth(), $node->getHeight()));
        }

        if ($node->getRight()) {
            $this->markFreeSpace($draw, $node->getRight());
        }

        if ($node->getDown()) {
            $this->markFreeSpace($draw, $node->getDown());
        }
    }
}
