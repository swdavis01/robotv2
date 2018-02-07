<?php
namespace Robot\Command;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;
//use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Psr\Log\LoggerInterface;

class ConsoleLogger implements LoggerInterface
{
	protected $output;
	protected $styles = [
		'alert' => 'alert',
		'warning' => 'alert',
		'crit' => 'error',
		'critical' => 'error',
		'debug' => 'notice',
		'emerg' => 'error',
		'emergency' => 'error',
		'err' => 'error',
		'error' => 'error',
		'notice' => 'notice',
		'info' => 'info',
		'log' => 'notice',
		'warn' => 'alert',
	];

	public function __construct(OutputInterface $output)
	{
		$this->output = $output;

		$this->output->getFormatter()->setStyle('alert', new OutputFormatterStyle('yellow'));
		$this->output->getFormatter()->setStyle('notice', new OutputFormatterStyle('cyan'));
		$this->output->getFormatter()->setStyle('error', new OutputFormatterStyle('red'));
		$this->output->getFormatter()->setStyle('text', new OutputFormatterStyle('white'));
	}

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function alert($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}

	/**
	 * @api
	 * @deprecated since 2.2, to be removed in 3.0. Use critical() which is PSR-3 compatible.
	 */
	public function crit($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function critical($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}

	/**
	 * Detailed debug information.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function debug($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}

	/**
	 * @api
	 * @deprecated since 2.2, to be removed in 3.0. Use emergency() which is PSR-3 compatible.
	 */
	public function emerg($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}

	/**
	 * System is unusable.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function emergency($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}

	/**
	 * @api
	 * @deprecated since 2.2, to be removed in 3.0. Use error() which is PSR-3 compatible.
	 */
	public function err($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function error($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function warning($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}

	/**
	 * Normal but significant events.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function notice($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}

	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function info($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function log($level, $message, array $context = array())
	{
		$style = isset($this->styles[$level]) ? $this->styles[$level] : 'info';
		$level = str_pad(substr($level, 0, 5), 5, ' ', STR_PAD_LEFT);
		$this->output->writeln("<$style>$level </$style> <text>$message</text>");
	}

	/**
	 * @api
	 * @deprecated since 2.2, to be removed in 3.0. Use warning() which is PSR-3 compatible.
	 */
	public function warn($message, array $context = array())
	{
		$this->log(__FUNCTION__, $message);
	}
} 