<?php

namespace Robot\Services;

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

	/**
	 * RobotService constructor.
	 * @param array $params
	 */
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

	/**
	 * @return RobotService
	 */
    public static function get()
	{
		$object = new RobotService();
		return $object;
	}

	/**
	 * @param ConsoleLogger $logger
	 */
    public function setLogger( ConsoleLogger $logger )
    {
        $this->logger = $logger;
    }

	/**
	 * @param $actions
	 */
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

	/**
	 * @param Action $action
	 */
    public function performAction( Action $action )
    {
        if ( $this->actionIsValid( $action, $this->robot ) )
        {
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

				case Action::MOVE:
					$moveAction = $action = $this->getMoveAction( $this->robot );
					$this->robot->setXY( $moveAction->getX(), $moveAction->getY() );
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

        $value = null;
        if ( isset( $compass[ $facing ] ) )
        {
            $last = ( count( $compass ) - 1 );
            $value = $compass[ $facing ];
            if ( $rotate == Action::LEFT )
            {
                $value = ( $value - 1 );
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
    private function actionIsValid( Action $action, Robot $robot )
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

		if ( $action->getAction() == Action::MOVE )
		{
			// check that action x,y falls within the bounds
			if ( $this->moveIsValid( $robot ) )
			{
				return true;
			}
			else
			{
				$this->logger->warning( "Cannot move robot as X " . $robot->getX() . ", Y " . $robot->getY() . " is the edge of the table" );
			}
		}

		$this->logger->warning( "Ignoring action " . $action->getAction() );

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

	/**
	 * @param Action $action
	 * @return bool
	 */
	private function moveIsValid( Robot $robot )
	{
		$action = $this->getMoveAction( $robot );
		return $this->placeIsValid( $action );
	}

	/**
	 * @param Robot $robot
	 * @return Action
	 */
	private function getMoveAction( Robot $robot )
	{
		$action = Action::get( Action::MOVE, $this->logger );
		$action->setFacing( $robot->getFacing() );
		$action->setX( $robot->getX() );
		$action->setY( $robot->getY() );

		switch( $robot->getFacing() )
		{
			case Action::FACING_NORTH:
				// move up
				$action->setY( $robot->getY() - 1 );
			break;
			case Action::FACING_SOUTH:
				// move down
				$action->setY( $robot->getY() + 1 );
			break;
			case Action::FACING_EAST:
				// move right
				$action->setX( $robot->getX() + 1 );
			break;
			case Action::FACING_WEST:
				// move left
				$action->setX( $robot->getX() - 1 );
			break;
		}

		return $action;
	}
}
