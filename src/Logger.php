<?php

namespace Yep\WorkflowLogger;

use Yep\WorkflowLogger\Exception\WorkflowIsLockedException;
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
     * @var array|Workflow[]
     */
    protected $workflows = [];

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
        static::$levels += parent::$levels;
        $this->workflowFormatter = $formatter;
    }

    /**
     * @return Workflow
     */
    protected function createWorkflow()
    {
        return new Workflow(
          $this,
          $this->workflowFormatter,
          $this->getDateTimeZone(),
          static::WORKFLOW
        );
    }

    /**
     * @return \DateTimeZone
     */
    protected function getDateTimeZone()
    {
        if (static::$timezone) {
            return static::$timezone;
        }

        $timezone = date_default_timezone_get() ?: 'UTC';

        return static::$timezone = new \DateTimeZone($timezone);
    }

    /**
     * @param string|null $key
     * @return Workflow
     * @throws WorkflowIsLockedException
     */
    public function workflow($key = null)
    {
        if ($key === null) {
            return $this->createWorkflow();
        }

        if (!isset($this->workflows[$key])) {
            $this->workflows[$key] = $this->createWorkflow();
        }

        if ($this->workflows[$key]->isLocked()) {
            throw WorkflowIsLockedException::create($key);
        }

        return $this->workflows[$key];
    }
}
