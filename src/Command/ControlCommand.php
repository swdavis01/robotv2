<?php

namespace Robot\Command;

use Robot\Entity\Action;
use Robot\Services\RobotService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ControlCommand extends BaseCommand
{
    const TRAVERSE_GRID_ACTION = "TRAVERSE_GRID";

    private $logger;
    private $data;
    /**
     * @var RobotService
     */
    private $service;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('robot:control')
            // the short description shown while running "php bin/console list"
            ->setDescription('Simulation of a toy robot moving on a square tabletop')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to move the robot around the tabletop. Commands are separated by the | symbol. 
            Data format is ACTION,FACING,X,Y|ACTION|ACTION|ACTION. 
            ACTION options are:  
            PLACE (Must also include FACING,X,Y e.g. PLACE,NORTH,2,5), 
            MOVE (will move the toy robot one unit forward in the direction it is currently facing), 
            LEFT (will rotate the robot 90 degrees to the left without changing the position of the robot), 
            RIGHT (will rotate the robot 90 degrees to the right without changing the position of the robot)
            Sample command:
            src/Command/Console.php robot:control 'MOVE|LEFT|RIGHT|PLACE,NORTH,2,5|MOVE|REPORT|MOVE|REPORT'
            Sample command to traverse grid:
            src/Command/Console.php robot:control 'TRAVERSE_GRID'")
            ->addArgument( "data", InputArgument::REQUIRED, "A string of | separated commands." )
        ;
    }

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger = new ConsoleLogger( $output );
        $this->data = $input->getArgument( "data" );

        //$this->logger->info( "The data is " . $this->data );

        $this->service = RobotService::get();
        $this->service->setLogger( $this->logger );

        $this->service->performActions( $this->getTestActions() );
        if ( $this->data == ControlCommand::TRAVERSE_GRID_ACTION )
        {
            $this->service->performActions( $this->getFullGridActions() );
        }
        else
        {
            $this->service->performActions( $this->getActionsFromString( $this->data ) );
        }
    }

	/**
	 * @param $data
	 * @return array
	 */
    private function getActionsFromString( $data )
	{
		$result = array();

		$dataArray = explode( "|", $data );
		foreach( $dataArray as $actionString )
		{
			$action = Action::get( $actionString );
			if ( $action instanceof Action )
			{
				$result[] = $action;
			}
		}

		return $result;
	}

    /**
     * Sample set of actions to go across each row
     * @return array
     */
	private function getFullGridActions()
	{
		$result = array();

		$report = Action::get( Action::REPORT );
		$action = Action::get( Action::PLACE . "," . Action::FACING_EAST . ",0,0" );
		array_push( $result, $action, $report );

		for( $i = 0; $i <= RobotService::ROWS; $i++ )
		{
			for( $c = 0; $c < RobotService::COLUMNS; $c++ )
			{
				$action = Action::get( Action::MOVE );
				array_push( $result, $action, $report );
			}

			$facing = Action::RIGHT;
            if ($i & 1)
            {
                // odd
                $facing = Action::LEFT;
            }

            // turn 90 degrees, move, turn another 90 degrees to start the next row
            $actionFacing = Action::get( $facing );
            $actionMove = Action::get( Action::MOVE );
            array_push( $result, $actionFacing, $actionMove, $actionFacing, $report );
		}

		//print_r( $result ); exit;

		return $result;
	}

    private function getTestActions()
    {
        //$data = "PLACE,NORTH,2,5|MOVE|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|PLACE,NORTH,3,4|REPORT";
		$data = "PLACE,NORTH,2,4|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT";
		$data = $data . "|LEFT|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT";
		$data = $data . "|RIGHT|REPORT|RIGHT|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT";
		$data = $data . "|RIGHT|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT";
		$data = $data . "|LEFT|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT";

		return $this->getActionsFromString( $data );
    }
}
