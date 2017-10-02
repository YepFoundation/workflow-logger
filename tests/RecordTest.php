<?php

use Yep\WorkflowLogger\Record;

/**
 * Class RecordTest
 *
 * @author Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class RecordTest extends PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $datetime = new DateTime();
        $message = 'foo';
        $level = 'level';
        $context = ['bar'];

        $record = new Record($datetime, $message, $level, $context);

        $this->assertSame($datetime, $record->getDatetime());
        $this->assertSame($message, $record->getMessage());
        $this->assertSame($level, $record->getLevel());
        $this->assertSame($context, $record->getContext());
    }
}
