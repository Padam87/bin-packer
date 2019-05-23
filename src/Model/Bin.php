<?php

namespace Padam87\BinPacker\Model;

class Bin
{
    /**
     * @var int|float|string
     */
    private $height;

    /**
     * @var int|float|string
     */
    private $width;

    /**
     * @var bool
     */
    private $growthAllowed;

    /**
     * @var Node
     */
    private $node;

    public function __construct($width, $height, bool $growthAllowed = false)
    {
        if (!is_numeric($width)) {
            throw new \InvalidArgumentException(sprintf('Bin width must be numeric, "%s" given', $width));
        }

        if (!is_numeric($height)) {
            throw new \InvalidArgumentException(sprintf('Bin height must be numeric, "%s" given', $height));
        }

        $this->width = $width;
        $this->height = $height;
        $this->growthAllowed = $growthAllowed;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;

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
}
