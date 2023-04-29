<?php

namespace Padam87\BinPacker\Model;

use Padam87\BinPacker\Enum\Orientation;

class Node
{
    private ?Block $block = null;

    public function __construct(
        private int|float $x,
        private int|float $y,
        private int|float $width,
        private int|float $height,
        private bool $used = false
    ) {
    }

    public function getX(): int|float
    {
        return $this->x;
    }

    public function setX(int|float $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): int|float
    {
        return $this->y;
    }

    public function setY(int|float $y): self
    {
        $this->y = $y;

        return $this;
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

    public function isUsed(): bool
    {
        return $this->used;
    }

    public function setUsed(bool $used): self
    {
        $this->used = $used;

        return $this;
    }

    public function getBlock(): ?Block
    {
        return $this->block;
    }

    public function setBlock(?Block $block): self
    {
        $this->block = $block;

        return $this;
    }

    public function canContain(Block $block): bool
    {
        return $this->getHeight() >= $block->getHeight() && $this->getWidth() >= $block->getWidth();
    }

    public function getOrientation(): Orientation
    {
        return $this->getWidth() > $this->getHeight() ? Orientation::Landscape : Orientation::Portrait;
    }

    public function isValid(): bool
    {
        return $this->getHeight() > 0 && $this->getWidth() > 0;
    }
}
