<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-08
 * Time: 12:38
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