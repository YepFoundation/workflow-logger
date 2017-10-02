<?php

namespace Tests\Yep\WorkflowLogger;

use Monolog\Formatter\FormatterInterface;
use Yep\WorkflowLogger\Logger;
use Yep\WorkflowLogger\MonologFormatterAdapter;

/**
 * Class MonologFormatterAdapterTest
 *
 * @author Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class MonologFormatterAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormat()
    {
        $newLevel = Logger::ERROR;
        $record['level'] = Logger::WORKFLOW;
        $expected['level'] = $newLevel;
        $expectedReturn = 'OK';

        /** @var FormatterInterface|\PHPUnit_Framework_MockObject_MockObject $formatter */
        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects($this->once())
          ->method('format')
          ->with($expected)
          ->willReturn($expectedReturn);

        $adapter = new MonologFormatterAdapter($formatter, $newLevel);

        $this->assertSame($expectedReturn, $adapter->format($record));
    }

    public function testFormatBatch()
    {
        $newLevel = Logger::ERROR;
        $records[]['level'] = Logger::WORKFLOW;
        $records[]['level'] = Logger::INFO;
        $expected[]['level'] = $newLevel;
        $expected[]['level'] = Logger::INFO;
        $expectedReturn = 'OK';

        /** @var FormatterInterface|\PHPUnit_Framework_MockObject_MockObject $formatter */
        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects($this->once())
          ->method('formatBatch')
          ->with($expected)
          ->willReturn($expectedReturn);

        $adapter = new MonologFormatterAdapter($formatter, $newLevel);

        $this->assertSame($expectedReturn, $adapter->formatBatch($records));
    }
}
