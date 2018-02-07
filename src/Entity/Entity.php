<?php

namespace Robot\Entity;

use Robot\Command\ConsoleLogger;

class Entity
{
    /**
     * @var ConsoleLogger
     */
    private $logger;

    public function __construct()
    {
    }

    public function setLogger( $logger )
    {
        $this->logger = $logger;
        return $this;
    }
}
