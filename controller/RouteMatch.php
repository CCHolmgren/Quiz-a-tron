<?php

/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-11-01
 * Time: 11:00
 */
class RouteMatch {
    private $routingDirective;
    private $matches;

    public function __construct($routingDirective, $matches) {
        $this->routingDirective = $routingDirective;
        $this->matches = $matches;
    }

    /**
     * @return mixed
     */
    public function getRoutingDirective() {
        return $this->routingDirective;
    }

    /**
     * @param mixed $routingDirective
     */
    public function setRoutingDirective($routingDirective) {
        $this->routingDirective = $routingDirective;
    }

    /**
     * @return mixed
     */
    public function getMatches() {
        return $this->matches;
    }

    /**
     * @param mixed $matches
     */
    public function setMatches($matches) {
        $this->matches = $matches;
    }
}