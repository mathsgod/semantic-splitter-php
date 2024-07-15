<?php

namespace TextSplitter\Util;

use Exception;

class Similarity
{
    static public function dot($tags)
    {
        $tags = array_unique($tags);
        $tags = array_fill_keys($tags, 0);
        ksort($tags);
        return $tags;
    }
    static protected function dot_product($a, $b)
    {
        $products = array_map(function ($a, $b) {
            return $a * $b;
        }, $a, $b);
        return array_reduce($products, function ($a, $b) {
            return $a + $b;
        });
    }
    static protected function magnitude($point)
    {
        $squares = array_map(function ($x) {
            return pow($x, 2);
        }, $point);
        return sqrt(array_reduce($squares, function ($a, $b) {
            return $a + $b;
        }));
    }


    static public function Cosine(array $a, array $b)
    {
        return self::dot_product($a, $b) / (self::magnitude($a) * self::magnitude($b));
    }
}
