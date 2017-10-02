<?php

namespace Yep\WorkflowLogger\Formatter;

use Yep\WorkflowLogger\Record;

/**
 * Interface FormatterInterface
 *
 * @package Yep\WorkflowLogger\Formatter
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
interface FormatterInterface
{
    public function format(Record $record);
}
