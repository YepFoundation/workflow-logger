<?php

namespace Yep\WorkflowLogger;

use Monolog\Formatter\FormatterInterface;

/**
 * Class MonologFormatterAdapter
 *
 * @package Yep\WorkflowLogger\Formatter
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class MonologFormatterAdapter implements FormatterInterface
{
    /**
     * @var mixed
     */
    protected $newLevel;

    /**
     * @var FormatterInterface
     */
    protected $formatter;

    public function __construct(
      FormatterInterface $formatter,
      $newLevel = Logger::DEBUG
    ) {
        $this->formatter = $formatter;
        $this->newLevel = $newLevel;
    }

    /**
     * Formats a log record.
     *
     * @param  array $record A record to format
     * @return mixed The formatted record
     */
    public function format(array $record)
    {
        $record = $this->fixRecord($record);

        return $this->formatter->format($record);
    }

    /**
     * @param array $record
     * @return array
     */
    protected function fixRecord(array $record)
    {
        if (isset($record['level']) && $record['level'] === Logger::WORKFLOW) {
            $record['level'] = $this->newLevel;
        }

        return $record;
    }

    /**
     * Formats a set of log records.
     *
     * @param  array $records A set of records to format
     * @return mixed The formatted set of records
     */
    public function formatBatch(array $records)
    {
        $records = array_map([$this, 'fixRecord'], $records);

        return $this->formatter->formatBatch($records);
    }
}
