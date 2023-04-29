<?php

namespace Padam87\BinPacker;

use Padam87\BinPacker\Model\Node;

class NodeMerger
{
    public function __invoke(State $state)
    {
        $freeNodes = $state->getFreeNodes();
        $count = count($freeNodes);

        for ($i = 0; $i < $count; $i++) {
            /** @var Node $a */
            if (null === $a = $freeNodes[$i]) {
                continue;
            }

            for ($j = 0; $j < $count; $j++) {
                /** @var Node $b */
                if (null === $b = $freeNodes[$j]) {
                    continue;
                }

                if ($a->getWidth() == $b->getWidth() && $a->getX() == $b->getX()) {
                    if ($a->getY() == $b->getY() + $b->getHeight()) {
                        $a->setY($a->getY() - $b->getHeight());
                        $a->setHeight($a->getHeight() + $b->getHeight());

                        $state->removeNode($b);
                        $freeNodes[$j] = null;
                    } elseif ($a->getY() + $a->getHeight() == $b->getY()) {
                        $a->setHeight($a->getHeight() + $b->getHeight());

                        $state->removeNode($b);
                        $freeNodes[$j] = null;
                    }
                } elseif ($a->getHeight() == $b->getHeight() && $a->getY() == $b->getY()) {
                    if ($a->getX() == $b->getX() + $b->getWidth()) {
                        $a->setX($a->getX() - $b->getWidth());
                        $a->setWidth($a->getWidth() + $b->getWidth());

                        $state->removeNode($b);
                        $freeNodes[$j] = null;
                    } elseif ($a->getX() + $a->getWidth() == $b->getX()) {
                        $a->setWidth($a->getWidth() + $b->getWidth());

                        $state->removeNode($b);
                        $freeNodes[$j] = null;
                    }
                }
            }
        }
    }
}
