<?php

namespace Robot\Services;

//use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Robot\Command\ConsoleLogger;
use Robot\Entity\Action;
use Robot\Entity\Robot;

class RobotService
{
    private $rows = 5;
    private $columns = 5;

    /**
     * @var Robot;
     */
    private $robot;
    /**
     * @var ConsoleLogger
     */
    private $logger;

    public function __construct( $params = array() )
    {
        //print_r( $params );
        // get values from config
        if ( isset( $params['rows'] ) && is_int( $params['rows'] ) )
        {
            $rows = (int)$params['rows'];
            if ( $rows > 0 )
            {
                $this->rows = $rows;
            }
        }

        if ( isset( $params['columns'] ) && is_int( $params['columns'] ) )
        {
            $columns = (int)$params['columns'];
            if ( $columns > 0 )
            {
                $this->columns = $columns;
            }
        }

    }

    public static function get()
	{
		$object = new RobotService();
		return $object;
	}

    public function setLogger( $logger )
    {
        $this->logger = $logger;
    }

    public function performActions( $actions )
    {
        $this->robot = Robot::get( $this->logger );
        if ( is_array( $actions ) )
        {
            foreach( $actions as $action )
            {
                if ( $action instanceof Action )
                {
                    $this->performAction( $action );
                }
            }
        }
    }

    public function performAction( Action $action )
    {
        //print_r( $action );
        //$this->logger->debug( "Checking action " . $action->getAction() );
        if ( $this->actionIsValid( $action ) )
        {
            //$this->logger->debug( "Peforming action " . $action->getAction() . ", " . $action->getFacing() . ", " . $action->getX() . ", " . $action->getY() );

            switch( $action->getAction() )
            {
                case Action::PLACE:
                    $this->robot->setIsPlaced( true );
                    $this->robot->setFacing( $action->getFacing() );
                    $this->robot->setX( $action->getX() );
                    $this->robot->setY( $action->getY() );
                break;

                case Action::LEFT:
                case Action::RIGHT:
                    $this->robot->setFacing( $this->rotate( $action->getAction(), $this->robot->getFacing() ) );
                break;

                case Action::REPORT:
                    $this->logger->info( "Report: " . $this->robot->getX() . "," . $this->robot->getY() . "," . $this->robot->getFacing() );
                break;
            }
        }
    }

    /**
     * @param $rotate Directtion to rotate (e.g. LEFT or RIGHT)
     * @param $facing Currently facing (e.g. NORTH)
     */
    private function rotate( $rotate, $facing )
    {
        $compass = array
        (
            Action::FACING_NORTH => 0,
            Action::FACING_EAST => 1,
            Action::FACING_SOUTH => 2,
            Action::FACING_WEST => 3
        );

        //print_r($compass);
        //$this->logger->debug( "changeDirection: " . $this->robot->getFacing() . ", " . $rotate );
        $value = null;
        if ( isset( $compass[ $facing ] ) )
        {
            $last = ( count( $compass ) - 1 );
            $value = $compass[ $facing ];
            //$this->logger->debug( "changeDirection: " . $this->robot->getFacing() . ", " . $rotate . ", " . $value );
            if ( $rotate == Action::LEFT )
            {
                $value = ( $value - 1 );
                //$this->logger->debug( "left: " . $this->robot->getFacing() . ", " . $rotate . ", " . $value );
                if ( $value < 0 )
                {
                    $value = $last;
                }
            }

            if ( $rotate == Action::RIGHT )
            {
                $value = ( $value + 1 );
                if ( $value > $last )
                {
                    $value = 0;
                }
            }
        }

        if ( is_int( $value ) )
        {
            foreach( $compass as $k => $v )
            {
                if ( $v == $value )
                {
                    //$this->logger->debug( "changeDirection: " . $value . ", " . $k );
                    return $k;
                }
            }
        }

        return $facing;
    }

    /**
     * @param Action $action
     * @return bool
     */
    private function actionIsValid( Action $action )
    {
        if ( !$this->robot->getIsPlaced() )
        {
            // Action must be a placement
            if ( $action->getAction() != Action::PLACE )
            {
                $this->logger->warning( "Ignoring action " . $action->getAction() . " because the robot has not been placed on the table" );
                return false;
            }
        }

        if ( in_array( $action->getAction(), array( Action::LEFT, Action::RIGHT, Action::REPORT ) ) )
        {
            return true;
        }

        if ( $action->getAction() == Action::PLACE )
        {
            // check that action x,y falls within the bounds
            if ( $this->placeIsValid( $action ) )
            {
                return true;
            }
            else
            {
                $this->logger->warning( "Cannot place robot at X " . $action->getX() . ", Y " . $action->getY() );
            }
        }

        return false;
    }

    /**
     * @param Action $action
     * @return bool
     */
    private function placeIsValid( Action $action )
    {
        if ( $action->getX() < 0 || $action->getX() > $this->rows )
        {
            return false;
        }

        if ( $action->getY() < 0 || $action->getY() > $this->columns )
        {
            return false;
        }

        return true;
    }

    public function test()
    {
        $this->logger->info("rows = " . $this->rows . ", columns " . $this->columns );
    }
}
