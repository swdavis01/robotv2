<?php

namespace Robot\Entity;

use Robot\Command\ConsoleLogger;

class Robot extends Entity
{
    private $X = 0;
    private $Y = 0;
    private $Facing = Action::FACING_NORTH;
    private $IsPlaced = false;

    public function __construct()
    {
    }

    public static function get( ConsoleLogger $logger )
    {
        $object = new Robot();
        $object->setLogger( $logger );
        return $object;
    }

    public function setX( $X )
    {
        $this->X = (int)$X;
        return $this;
    }

    public function getX()
    {
        return $this->X;
    }

    public function setY( $Y )
    {
        $this->Y = (int)$Y;
        return $this;
    }

    public function getY()
    {
        return $this->Y;
    }

    public function setFacing( $Facing )
    {
        $this->Facing = $Facing;
        return $this;
    }

    public function getFacing()
    {
        return $this->Facing;
    }

    public function setIsPlaced( $IsPlaced )
    {
        $this->IsPlaced = (bool)$IsPlaced;
        return $this;
    }

    public function getIsPlaced()
    {
        return $this->IsPlaced;
    }

}
