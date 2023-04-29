<?php

namespace Padam87\BinPacker;

use Padam87\BinPacker\Model\Bin;
use Padam87\BinPacker\Model\Node;

class Visualizer
{
    const MARGIN = 50;
    const FONT_SIZE = 12;
    const LINE_HEIGHT = 20;
    const FONT_PATH = __DIR__ . '/Resources/Roboto-Light.ttf';

    public function __construct()
    {
        if (!extension_loaded('gd')) {
            throw new \LogicException('The "gd" extension is required to use the visualizer');
        }
    }

    public function visualize(Bin $bin, State $state, ?string $name = null): \GdImage
    {
        $image = imagecreatetruecolor($bin->getWidth() + self::MARGIN * 2, $bin->getHeight() + self::MARGIN * 2);
        imageantialias($image, true);

        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);

        imagefilledrectangle(
            $image,
            0,
            0,
            $bin->getWidth() + self::MARGIN * 2,
            $bin->getHeight() + self::MARGIN * 2,
            $white
        );

        if ($name) {
            imagettftext($image, self::FONT_SIZE, 0, 25, 25, $black, self::FONT_PATH, $name);
        }

        foreach ($state->getNodes() as $node) {
            $this->drawNode($image, $node);
        }

        return $image;
    }

    private function drawNode(\GdImage $image, Node $node)
    {
        $x = $node->getX() + self::MARGIN;
        $y = $node->getY() + self::MARGIN;

        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);
        $whitesmoke = imagecolorallocate($image, 245, 245, 245);

        imagefilledrectangle($image, $x, $y, $x + $node->getWidth(), $y + $node->getHeight(), $node->isUsed() ? $white : $whitesmoke);
        imagerectangle($image, $x, $y, $x + $node->getWidth(), $y + $node->getHeight(), $black);

        $name = $node->getBlock() ? $node->getBlock()->getId() ?? '-' : 'free';

        imagettftext($image, self::FONT_SIZE, 0, $x + 10, $y + self::LINE_HEIGHT, $black, self::FONT_PATH, $name);
        imagettftext($image, self::FONT_SIZE, 0, $x + 10, $y + self::LINE_HEIGHT * 2, $black, self::FONT_PATH, sprintf('%s x %s', $node->getWidth(), $node->getHeight()));
    }
}
