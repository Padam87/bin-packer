<?php

namespace Padam87\BinPacker;

use Padam87\BinPacker\Model\Bin;
use Padam87\BinPacker\Model\Block;

class GifMaker
{
    private $visualizer;

    /**
     * @var \Imagick[]
     */
    private $images = [];

    public function __construct(Visualizer $visualizer)
    {
        $this->visualizer = $visualizer;
    }

    public function __invoke(Bin $bin, array $blocks, Block $currentBlock)
    {
        $visualizer = new Visualizer();
        $image = $visualizer->visualize($bin, $blocks);

        $this->images[] = $image;
    }

    public function reset()
    {
        $this->images = [];
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function create(int $delay = 50): \Imagick
    {
        $gif = new \Imagick();
        $gif->setFormat("gif");

        $last = $this->images[count($this->images) - 1];

        foreach ($this->images as $image) {
            $image->setImageDelay($delay);
            $image->setImageExtent($last->getImageWidth(), $last->getImageHeight());

            $gif->addImage($image);
        }

        return $gif;
    }
}
