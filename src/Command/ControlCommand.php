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
    private $logger;
    private $action;
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
            ->setHelp('This command allows you to move the robot around the tabletop')
            ->addArgument( "action", InputArgument::REQUIRED, "Valid options are  
            PLACE (first valid action, places the robot on the tabletop), 
            MOVE (will move the toy robot one unit forward in the direction it is currently facing), 
            LEFT (will rotate the robot 90 degrees to the left without changing the position of the robot), 
            RIGHT (will rotate the robot 90 degrees to the right without changing the position of the robot)" )
            ->addOption( "posX", "x", InputOption::VALUE_OPTIONAL, "Required when using PLACE action, X axis position" )
            ->addOption( "posY", "y", InputOption::VALUE_OPTIONAL, "Required when using PLACE action, Y axis position" )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger = new ConsoleLogger( $output );
        $this->action = $input->getArgument( "action" );

        //$this->logger->info( "The action is " . $this->action );

        $this->service = RobotService::get();
        $this->service->setLogger( $this->logger );

        //$this->service->test();
        $this->service->performActions( $this->getTestActions() );
    }

    private function getTestActions()
    {
        $result = array();

        //$data = "PLACE,NORTH,2,5|MOVE|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|LEFT|REPORT|PLACE,NORTH,3,4|REPORT";
		$data = "PLACE,NORTH,2,5|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT";
		$data = $data . "|LEFT|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT";
		$data = $data . "|RIGHT|REPORT|RIGHT|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT";
		$data = $data . "|RIGHT|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT";
		$data = $data . "|LEFT|REPORT|MOVE|REPORT|MOVE|REPORT|MOVE|REPORT";
        $dataArray = explode( "|", $data );
        foreach( $dataArray as $actionString )
        {
            $action = Action::get( $actionString, $this->logger );
            if ( $action instanceof Action )
            {
                $result[] = $action;
            }
        }

        return $result;
    }
}
