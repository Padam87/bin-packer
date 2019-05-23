<?php

namespace Padam87\BinPacker\Model;

class Node
{
    /**
     * @var int|float|string
     */
    private $x;

    /**
     * @var int|float|string
     */
    private $y;

    /**
     * @var int|float|string
     */
    private $width;

    /**
     * @var int|float|string
     */
    private $height;

    /**
     * @var bool
     */
    private $used;

    /**
     * @var Node|null
     */
    private $right;

    /**
     * @var Node|null
     */
    private $down;

    public function __construct($x, $y, $width, $height, bool $used = false, ?Node $right = null, ?Node $down = null)
    {
        foreach (['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height] as $key => $value) {
            if (!is_numeric($value)) {
                throw new \InvalidArgumentException(sprintf('Block %s must be numeric, "%s" given', $key, $value));
            }
        }

        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;

        $this->used = $used;
        $this->right = $right;
        $this->down = $down;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
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

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
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

    public function getRight(): ?Node
    {
        return $this->right;
    }
    
    public function setRight(?Node $right): self
    {
        $this->right = $right;
        
        return $this;
    }

    public function getDown(): ?Node
    {
        return $this->down;
    }

    public function setDown(?Node $down): self
    {
        $this->down = $down;

        return $this;
    }
}
