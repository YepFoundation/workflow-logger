<?php

namespace Yep\WorkflowLogger;

use Yep\WorkflowLogger\Formatter\FormatterInterface;

/**
 * Class Logger
 *
 * @package Yep\WorkflowLogger
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class Logger extends \Monolog\Logger implements LoggerInterface
{
    /**
     * Detailed workflow debug information
     */
    const WORKFLOW = 111;

    /**
     * @var FormatterInterface
     */
    protected $workflowFormatter;

    /**
     * Logging levels from syslog protocol defined in RFC 5424
     *
     * @var array $levels Logging levels
     */
    protected static $levels = [
      self::WORKFLOW => 'WORKFLOW',
    ];

    /**
     * Logger constructor.
     *
     * @param string             $name
     * @param FormatterInterface $formatter
     * @param array              $handlers
     * @param array              $processors
     */
    public function __construct(
      $name,
      FormatterInterface $formatter,
      $handlers = [],
      $processors = []
    ) {
        parent::__construct($name, $handlers, $processors);
        self::$levels += parent::$levels;
        $this->workflowFormatter = $formatter;
    }

    /**
     * @return \DateTimeZone
     */
    protected function timezoneFactory()
    {
        if (static::$timezone) {
            return static::$timezone;
        }

        $timezone = date_default_timezone_get() ?: 'UTC';

        return static::$timezone = new \DateTimeZone($timezone);
    }

    /**
     * @param string $name
     * @return Workflow
     */
    public function workflow($name)
    {
        return new Workflow(
          $this,
          $this->workflowFormatter,
          $this->timezoneFactory(),
          $name,
          static::WORKFLOW
        );
    }
}
