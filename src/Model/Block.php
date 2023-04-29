<?php

namespace Padam87\BinPacker\Model;

use Padam87\BinPacker\Enum\Orientation;

class Block
{
    private ?Node $node = null;

    public function __construct(
        private int|float $width,
        private int|float $height,
        private bool $rotatable = true,
        private mixed $id = null
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

    public function isRotatable(): bool
    {
        return $this->rotatable;
    }

    public function setRotatable(bool $rotatable): self
    {
        $this->rotatable = $rotatable;

        return $this;
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function setId(mixed $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function rotate(): self
    {
        if (!$this->rotatable) {
            throw new \LogicException();
        }

        $w = $this->getWidth();

        $this->setWidth($this->getHeight());
        $this->setHeight($w);

        return $this;
    }

    public function getRotatedClone(): Block
    {
        return new Block($this->getHeight(), $this->getWidth(), $this->isRotatable(), $this->getId());
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
