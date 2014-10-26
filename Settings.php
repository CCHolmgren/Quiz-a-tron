<?php

/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-26
 * Time: 18:13
 */
class Settings {
    static public function getSetting($settingName) {
        $ini_array = parse_ini_file("settings.ini");
        if ($ini_array && isset($ini_array[$settingName])) {
            return $ini_array[$settingName];
        } else {
            return "";
        }
    }
}