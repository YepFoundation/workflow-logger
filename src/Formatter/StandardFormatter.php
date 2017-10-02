<?php

namespace Yep\WorkflowLogger\Formatter;

use Yep\WorkflowLogger\ContextDumper\DumperInterface;
use Yep\WorkflowLogger\Record;

/**
 * Class StandardFormatter
 *
 * @package Yep\WorkflowLogger\Formatter
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class StandardFormatter implements FormatterInterface
{
    const FORMAT = "[%datetime%] %level%: %message%\n%contextPlaceholder%";
    const CONTEXT_FORMAT = "Context:\n%context%\n";
    const DATETIME_FORMAT = 'Y-m-d H:i:s.u';

    /**
     * @var DumperInterface
     */
    protected $dumper;

    public function __construct(DumperInterface $dumper)
    {
        $this->dumper = $dumper;
    }

    public function format(Record $record)
    {
        $contextString = '';

        if ($context = $record->getContext()) {
            $contextString = strtr(
              self::CONTEXT_FORMAT,
              [
                '%context%' => $this->dumper->dump($context),
              ]
            );
        }

        $string = strtr(
          self::FORMAT,
          [
            '%datetime%' => $record->getDatetime()->format(
              static::DATETIME_FORMAT
            ),
            '%level%' => $record->getLevel(),
            '%message%' => $record->getMessage(),
            '%contextPlaceholder%' => $contextString,
          ]
        );

        return $string;
    }
}
