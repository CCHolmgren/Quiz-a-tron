<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-08
 * Time: 12:38
 */

/*
 * This is a nice way to handle the otherwise ugly hashmap and array that would be used
 * Might add more functions to this class , maybe change stuff or something?
 */
class RoutingDirective{
    public $regex;
    public $controllername;
    public $functionname;
    public $name;

    public function __construct($regex, $controllername, $functionname, $name){
        $this->regex = $regex;
        $this->controllername = $controllername;
        $this->functionname = $functionname;
        $this->name = $name;
    }
}