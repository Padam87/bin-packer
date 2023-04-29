<?php

namespace Padam87\BinPacker\Model;

use Padam87\BinPacker\Enum\Orientation;

class Bin
{
    private ?Node $node = null;

    public function __construct(
        private int|float $width,
        private int|float $height,
        private bool $growthAllowed = false
    ) {
    }

    public function getWidth(): int|float
    {
        return $this->width;
    }

    public function setWidth(int|float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): int|float
    {
        return $this->height;
    }

    public function setHeight(int|float $height): self
    {
        $this->height = $height;

        return $this;
    }    

    public function isGrowthAllowed(): bool
    {
        return $this->growthAllowed;
    }

    public function getNode(): ?Node
    {
        return $this->node;
    }

    public function setNode(?Node $node): self
    {
        $this->node = $node;

        return $this;
    }

    public function getOrientation(): Orientation
    {
        return $this->getWidth() > $this->getHeight() ? Orientation::Landscape : Orientation::Portrait;
    }
}
