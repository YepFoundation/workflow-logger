<?php

namespace Yep\WorkflowLogger;

use Psr\Log\LoggerInterface;
use Yep\WorkflowLogger\Exception\LevelIsNotDefinedException;
use Yep\WorkflowLogger\Formatter\FormatterInterface;

/**
 * Class Workflow
 *
 * @package Yep\WorkflowLogger
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class Workflow implements LoggerInterface
{
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';

    /**
     * Logging levels from syslog protocol defined in RFC 5424
     *
     * @var array|string[] $levels Logging levels
     */
    protected static $levels = [
      self::DEBUG => 'DEBUG',
      self::INFO => 'INFO',
      self::NOTICE => 'NOTICE',
      self::WARNING => 'WARNING',
      self::ERROR => 'ERROR',
      self::CRITICAL => 'CRITICAL',
      self::ALERT => 'ALERT',
      self::EMERGENCY => 'EMERGENCY',
    ];

    /**
     * @var int|string
     */
    private $level;

    /**
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array|Record[]
     */
    protected $records = [];

    /**
     * @var \DateTimeZone
     */
    protected $timezone;

    /**
     * Workflow constructor.
     *
     * @param LoggerInterface    $logger    Main logger
     * @param FormatterInterface $formatter Workflow records formatter
     * @param \DateTimeZone      $timezone  Current timezone
     * @param string             $name      Workflow name
     * @param int|string         $level     Workflow level code
     */
    public function __construct(
      LoggerInterface $logger,
      FormatterInterface $formatter,
      \DateTimeZone $timezone,
      $name,
      $level
    ) {
        $this->logger = $logger;
        $this->formatter = $formatter;
        $this->timezone = $timezone;
        $this->name = (string)$name;
        $this->level = $level;
    }

    /**
     * @return \DateTime
     */
    protected function getCurrentDateTime()
    {
        $time = new \DateTime(null, $this->timezone);
        $time->setTimezone($this->timezone);

        return $time;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int|string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Gets the name of the logging level.
     *
     * @param string $level
     * @return string
     * @throws LevelIsNotDefinedException
     */
    public function getLevelName($level)
    {
        if (!isset(static::$levels[$level])) {
            throw LevelIsNotDefinedException::create($level, static::$levels);
        }

        return static::$levels[$level];
    }

    /**
     * Logs with a workflow level and with all workflow records
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return void
     */
    public function finish($message = '', array $context = [])
    {
        $message .= $message === '' ? '' : "\n\n";
        $message .= "Workflow: {$this->getName()}\n";

        foreach ($this->records as $record) {
            $message .= $this->formatter->format($record);
        }

        $this->logger->log($this->level, $message, $context);
    }

    /**
     * Logs workflow record with an arbitrary level.
     *
     * @param  int    $level   The logging level
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return void
     * @throws \Yep\WorkflowLogger\Exception\LevelIsNotDefinedException
     */
    public function log($level, $message, array $context = [])
    {
        $this->records[] = new Record(
          $this->getCurrentDateTime(),
          $message,
          $this->getLevelName($level),
          $context
        );
    }

    /**
     * System is unusable.
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return void
     * @throws \Yep\WorkflowLogger\Exception\LevelIsNotDefinedException
     */
    public function emergency($message, array $context = [])
    {
        $this->log(self::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return void
     * @throws \Yep\WorkflowLogger\Exception\LevelIsNotDefinedException
     */
    public function alert($message, array $context = [])
    {
        $this->log(self::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return void
     * @throws \Yep\WorkflowLogger\Exception\LevelIsNotDefinedException
     */
    public function critical($message, array $context = [])
    {
        $this->log(self::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return void
     * @throws \Yep\WorkflowLogger\Exception\LevelIsNotDefinedException
     */
    public function error($message, array $context = [])
    {
        $this->log(self::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return void
     * @throws \Yep\WorkflowLogger\Exception\LevelIsNotDefinedException
     */
    public function warning($message, array $context = [])
    {
        $this->log(self::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return void
     * @throws \Yep\WorkflowLogger\Exception\LevelIsNotDefinedException
     */
    public function notice($message, array $context = [])
    {
        $this->log(self::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return void
     * @throws \Yep\WorkflowLogger\Exception\LevelIsNotDefinedException
     */
    public function info($message, array $context = [])
    {
        $this->log(self::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return void
     * @throws \Yep\WorkflowLogger\Exception\LevelIsNotDefinedException
     */
    public function debug($message, array $context = [])
    {
        $this->log(self::DEBUG, $message, $context);
    }
}
