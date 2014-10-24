<?php

/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-24
 * Time: 15:27
 */
class StringHelper {
    public static function shortenString($string, $length = "15", $ellipsis = "...") {
        if (mb_strlen($string) - 3 > $length) {
            return mb_substr($string, 0, $length) . "...";
        } else {
            return $string;
        }
    }
}