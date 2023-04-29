<?php

namespace Padam87\BinPacker;

use Padam87\BinPacker\Model\Node;

class State
{
    /**
     * @var Node[]
     */
    private array $nodes = [];

    public function __construct(Node $root)
    {
        $this->nodes[] = $root;
    }

    public function addNode(Node $node): self
    {
        $this->nodes[] = $node;

        return $this;
    }

    public function removeNode(Node $node): self
    {
        foreach ($this->nodes as $k => $n) {
            if ($n !== $node) {
                continue;
            }

            unset($this->nodes[$k]);

            break;
        }

        return $this;
    }

    public function getNodes(): array
    {
        return array_values($this->nodes);
    }

    public function getUsedNodes(): array
    {
        return array_values(array_filter($this->nodes, fn(Node $node) => $node->isUsed()));
    }

    public function getFreeNodes(): array
    {
        return array_values(array_filter($this->nodes, fn(Node $node) => !$node->isUsed()));
    }
}
