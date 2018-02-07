<?php

namespace Robot\Entity;

class Place
{
    private $X = 0;
    private $Y = 0;
    private $Facing = Place::FACING_NORTH;

    public function __construct()
    {
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
}
