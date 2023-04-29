<?php

namespace Padam87\BinPacker;

use Padam87\BinPacker\Model\Node;

class NodeSlider
{
    public function __invoke(State $state)
    {
        /** @var Node $free */
        foreach ($state->getFreeNodes() as $free)  {
            /** @var Node $used */
            foreach ($state->getUsedNodes() as $used) {
                if ($free->getWidth() == $used->getWidth() && $free->getX() == $used->getX() && $free->getY() + $free->getHeight() === $used->getY()) {
                    $used->setY($free->getY());
                    $free->setY($used->getY() + $used->getHeight());

                } elseif ($free->getHeight() == $used->getHeight() && $free->getY() == $used->getY() && $free->getX() + $free->getWidth() === $used->getX()) {
                    $used->setX($free->getX());
                    $free->setX($used->getX() + $used->getWidth());
                }
            }
        }
    }
}
