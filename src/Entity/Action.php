<?php

namespace Robot\Entity;

use Robot\Command\ConsoleLogger;

class Action extends Entity
{
    const PLACE = "PLACE";
    const MOVE = "MOVE";
    const LEFT = "LEFT";
    const RIGHT = "RIGHT";
    const OUTPUT = "OUTPUT";
    const REPORT = "REPORT";
    const FACING_NORTH = "NORTH";
    const FACING_SOUTH = "SOUTH";
    const FACING_EAST = "EAST";
    const FACING_WEST = "WEST";

    private $X;
    private $Y;
    private $Facing;
    private $Action;

    public function __construct()
    {
    }

    /**
     * @param $action Comma separated string (Action,Facing,X,Y)
     * @return Action|null
     */
    public static function get( $action )
    {
        $actionArray = explode( ",", $action );
        if ( count( $actionArray ) > 0 )
        {
            //$logger->debug( "Checking action from string " . $actionArray[0] );
            //print_r( $actionArray );
            $object = new Action();
            $object->setAction( $actionArray[0] );

            // Facing
            if ( isset( $actionArray[1] ) )
            {
                $object->setFacing( $actionArray[1] );
            }

            // X
            if ( isset( $actionArray[2] ) )
            {
                $object->setX( $actionArray[2] );
            }

            // Y
            if ( isset( $actionArray[3] ) )
            {
                $object->setY( $actionArray[3] );
            }

            if ( $object->isValid() )
            {
                //print_r( $object );
                return $object;
            }
        }

    }

    public function setX( $X )
    {
        $this->X = $X;
        return $this;
    }

    public function getX()
    {
        return $this->X;
    }

    public function setY( $Y )
    {
        $this->Y = $Y;
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

    public static function isValidFacing( $Facing )
    {
        if ( in_array( $Facing,
            array( Action::FACING_NORTH, Action::FACING_SOUTH, Action::FACING_EAST, Action::FACING_WEST ) ) )
        {
            return true;
        }

        return false;
    }

    public function setAction( $Action )
    {
        $this->Action = $Action;
        return $this;
    }

    public function getAction()
    {
        return $this->Action;
    }

    public static function isValidAction( $Action )
    {
        if ( in_array( $Action,
            array( Action::PLACE, Action::MOVE, Action::LEFT, Action::RIGHT, Action::REPORT ) ) )
        {
            return true;
        }

        return false;
    }

    public function isValid()
    {
        if ( Action::isValidAction( $this->getAction() ) )
        {
            // Place must have X, Y and Facing
            if ( $this->getAction() == Action::PLACE )
            {
                if ( Action::isValidFacing( $this->getFacing() ) )
                {
                    if ( is_int( $this->getX() ) && is_int( $this->getY() ) )
                    {
                        return true;
                    }
                }
            }

            return true;
        }


        return false;
    }

}
