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
     * @var int
     */
    protected $indent;

    /**
     * @var DumperInterface
     */
    protected $dumper;

    public function __construct(DumperInterface $dumper, $indent = 0)
    {
        $this->dumper = $dumper;
        $this->indent = (int)$indent;
    }

    protected function prepareContextPart(Record $record)
    {
        $context = $record->getContext();

        if (empty($context)) {
            return '';
        }

        $dump = $this->dumper->dump($context);
        $dump = $this->indent($dump);
        $string = strtr(static::CONTEXT_FORMAT, ['%context%' => $dump]);
        $string = $this->indent($string);

        return $string;
    }

    public function format(Record $record)
    {
        $string = strtr(
          static::FORMAT,
          [
            '%datetime%' => $record->getDatetime()->format(
              static::DATETIME_FORMAT
            ),
            '%level%' => $record->getLevel(),
            '%message%' => $record->getMessage(),
            '%contextPlaceholder%' => $this->prepareContextPart($record),
          ]
        );

        $string = $this->indent($string);

        return $string;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function indent($string)
    {
        if ($this->indent === 0) {
            return $string;
        }

        return preg_replace(
          '#(?:^|[\r\n]+)(?=[^\r\n])#',
          '$0'.str_repeat(' ', $this->indent),
          $string
        );
    }
}
