<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Padam87\BinPacker\BinPacker;
use Padam87\BinPacker\FitHeuristic;
use Padam87\BinPacker\GifMaker;
use Padam87\BinPacker\Model\Bin;
use Padam87\BinPacker\Model\Block;
use Padam87\BinPacker\NodeMerger;
use Padam87\BinPacker\NodeSlider;
use Padam87\BinPacker\SplitHeuristic;
use Padam87\BinPacker\State;
use Padam87\BinPacker\Visualizer;

A5_B1();
mergerAndSlider();
gif();

function A5_B1()
{
    $fitHeuristics = [
        new FitHeuristic\BestAreaFit(),
        new FitHeuristic\BestLongSideFit(),
        new FitHeuristic\BestShortSideFit(),
        new FitHeuristic\NegateScoreFit(new FitHeuristic\BestAreaFit()),
        new FitHeuristic\NegateScoreFit(new FitHeuristic\BestLongSideFit()),
        new FitHeuristic\NegateScoreFit(new FitHeuristic\BestShortSideFit()),
        new FitHeuristic\AssumeSameBlocksFit(),
    ];

    $splitHeuristics = [
        new SplitHeuristic\LongerAxisSplit(),
        new SplitHeuristic\ShorterAxisSplit(),
        new SplitHeuristic\LongerLeftoverAxisSplit(),
        new SplitHeuristic\ShorterLeftoverAxisSplit(),
        new SplitHeuristic\MaximizeAreaSplit(),
        new SplitHeuristic\MinimizeAreaSplit(),
    ];

    foreach ($fitHeuristics as $fitHeuristic) {
        foreach ($splitHeuristics as $splitHeuristic) {
            $binPacker = new BinPacker($fitHeuristic, $splitHeuristic);

            $bin = new Bin(680, 980);
            $blocks = [];

            for ($i = 0; $i < 30; $i++) {
                $blocks[] = new Block(148, 210, true, $i + 1);
            }

            $state = $binPacker->pack($bin, $blocks);

            $filename = implode('_', [$fitHeuristic->getName(), $splitHeuristic->getName()]) . '.jpg';

            createImage($bin, $state, $fitHeuristic, $splitHeuristic, 'A5_B1', $filename);
        }
    }
}

function mergerAndSlider()
{
    $fitHeuristic = new FitHeuristic\BestLongSideFit();
    $splitHeuristic = new SplitHeuristic\LongerLeftoverAxisSplit();

    $binPacker = new BinPacker($fitHeuristic, $splitHeuristic);

    $bin = new Bin(680, 980);
    $blocks = [];

    for ($i = 0; $i < 30; $i++) {
        $blocks[] = new Block(148, 210, true, $i + 1);
    }

    $state = $binPacker->pack($bin, $blocks);

    createImage($bin, $state, $fitHeuristic, $splitHeuristic, 'merger_slider', '1_regular.jpg');

    // merger

    $bin = new Bin(680, 980);
    $blocks = [];

    for ($i = 0; $i < 30; $i++) {
        $blocks[] = new Block(148, 210, true, $i + 1);
    }

    $merger = new NodeMerger();
    $state = $binPacker->pack($bin, $blocks, function ($bin, $state, $node, $block) use ($merger) {
        $merger($state);
    });

    createImage($bin, $state, $fitHeuristic, $splitHeuristic, 'merger_slider', '2_merger.jpg');

    // slider

    $bin = new Bin(680, 980);
    $blocks = [];

    for ($i = 0; $i < 30; $i++) {
        $blocks[] = new Block(148, 210, true, $i + 1);
    }

    $slider = new NodeSlider();
    $state = $binPacker->pack($bin, $blocks, function ($bin, $state, $node, $block) use ($slider) {
        $slider($state);
    });

    createImage($bin, $state, $fitHeuristic, $splitHeuristic, 'merger_slider', '3_slider.jpg');

    // both

    $bin = new Bin(680, 980);
    $blocks = [];

    for ($i = 0; $i < 30; $i++) {
        $blocks[] = new Block(148, 210, true, $i + 1);
    }

    $merger = new NodeMerger();
    $slider = new NodeSlider();
    $state = $binPacker->pack($bin, $blocks, function ($bin, $state, $node, $block) use ($slider) {
        $slider($state);
    });
    $merger($state);

    $state = $binPacker->pack($bin, $blocks, null, $state);

    createImage($bin, $state, $fitHeuristic, $splitHeuristic, 'merger_slider', '4_both.jpg');
}

function gif()
{
    ini_set('memory_limit', -1);

    $fitHeuristic = new FitHeuristic\AssumeSameBlocksFit();
    $splitHeuristic = new SplitHeuristic\MaximizeAreaSplit();

    $binPacker = new BinPacker($fitHeuristic, $splitHeuristic);

    $bin = new Bin(680, 980);
    $blocks = [];

    for ($i = 0; $i < 30; $i++) {
        $blocks[] = new Block(148, 210, true, $i + 1);
    }

    $gifMaker = new GifMaker(new Visualizer());
    $state = $binPacker->pack($bin, $blocks, $gifMaker);

    $gif = $gifMaker->create();
    $gif->writeImages(__DIR__ . DIRECTORY_SEPARATOR . 'demo.gif', true);
}

function createImage(Bin $bin, State $state, FitHeuristic\FitHeuristic $fitHeuristic, SplitHeuristic\SplitHeuristic $splitHeuristic, string $directory, string $filename)
{
    $visualizer = new Visualizer();

    $image = $visualizer->visualize($bin, $state, sprintf(
        'Fit heuristic: %s; Split heuristic: %s; Fit count: %s',
        $fitHeuristic->getName(),
        $splitHeuristic->getName(),
        count($state->getUsedNodes())
    ));

    ob_start();

    imagejpeg($image);
    $content = ob_get_contents();

    ob_end_clean();

    echo $filename . PHP_EOL;

    @mkdir(__DIR__ . DIRECTORY_SEPARATOR . $directory);
    file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $filename, $content);
}
