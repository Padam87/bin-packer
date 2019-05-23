<?php

namespace Padam87\BinPacker\Model;

class Block
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
    private $rotatable;

    /**
     * An ID or name to identify the block for your own purposes.
     *
     * @var mixed|null
     */
    private $id;

    /**
     * @var Node
     */
    private $node;

    public function __construct($width, $height, bool $rotatable = true, $id = null)
    {
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setRotatable($rotatable);
        $this->id = $id;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height): self
    {
        if (!is_numeric($height)) {
            throw new \InvalidArgumentException(sprintf('Block height must be numeric, "%s" given', $height));
        }

        $this->height = $height;

        return $this;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width): self
    {
        if (!is_numeric($width)) {
            throw new \InvalidArgumentException(sprintf('Block width must be numeric, "%s" given', $width));
        }

        $this->width = $width;

        return $this;
    }

    public function isRotatable(): ?bool
    {
        return $this->rotatable;
    }

    public function setRotatable(?bool $rotatable): self
    {
        $this->rotatable = $rotatable;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
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
