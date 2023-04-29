<?php

namespace Padam87\BinPacker;

use Padam87\BinPacker\Model\Bin;
use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\Model\Node;

class GifMaker
{
    /**
     * @var \GdImage[]
     */
    private array $images = [];

    public function __construct(private Visualizer $visualizer)
    {
    }

    public function __invoke(Bin $bin, State $state, ?Node $node, Block $block)
    {
        $this->images[] = $this->visualizer->visualize($bin, $state);
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

        foreach ($this->images as $gdImage) {
            ob_start();
            imagegif($gdImage);
            $content = ob_get_contents();
            ob_end_clean();

            $image = new \Imagick();
            $image->readImageBlob($content);

            $image->setImageDelay($delay);

            $gif->addImage($image);
        }

        return $gif;
    }
}
