<?php
namespace Robot\Command;

use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends Command
{

	const INPUT_CONFIG_FILE = "configFile";

	/**
	 * @var OutputInterface
	 */
	protected $output;

	/**
	 * @var InputInterface
	 */
	protected $input;

	/**
	 * @var \Matter\MsCrmApi\Config\Config
	 */
	protected $config;

	protected function initialize(InputInterface $input, OutputInterface $output)
	{
		$this->input  = $input;
		$this->output = $output;
		$this->setConfig();

		//$logger = new ConsoleLogger( $this->output );
		//CommonService::get( $this->config )->setLogger( $logger );
	}

	protected function setConfig()
	{
	}

	protected function question( $question )
	{
		$helper = $this->getHelper( "question" );
		$question = new ConfirmationQuestion( $question, false );
		if (!$helper->ask( $this->input, $this->output, $question ) )
		{
			return false;
		}

		return true;
	}
}