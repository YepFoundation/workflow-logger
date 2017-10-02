<?php

namespace Tests\Yep\WorkflowLogger\Formatter;

use Yep\WorkflowLogger\ContextDumper\DumperInterface;
use Yep\WorkflowLogger\Formatter\StandardFormatter;
use Yep\WorkflowLogger\Record;

/**
 * Class StandardFormatterTest
 *
 * @author Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class StandardFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function contextGenerator()
    {
        return [
          [[]],
          [['a' => 'b']],
        ];
    }

    /**
     * @dataProvider contextGenerator
     * @param array $context
     */
    public function testFormat(array $context)
    {
        $datetime = new \DateTime();
        $message = 'foo';
        $level = 'level';

        /** @var DumperInterface|\PHPUnit_Framework_MockObject_MockObject $dumper */
        $dumper = $this->createMock(DumperInterface::class);
        $dumper->expects($context ? $this->once() : $this->never())
          ->method('dump')
          ->with($context)
          ->willReturn('OK');

        $formatter = new StandardFormatter($dumper);
        $formatted = $formatter->format(
          new Record($datetime, $message, $level, $context)
        );

        $this->assertInternalType(
          \PHPUnit_Framework_Constraint_IsType::TYPE_STRING,
          $formatted
        );

        $timestamp = $datetime->format(StandardFormatter::DATETIME_FORMAT);
        $this->assertSame(
          "[$timestamp] $level: $message\n".($context ? "Context:\nOK\n" : ''),
          $formatted
        );
    }

    public function testFormatWithIndent()
    {
        $datetime = new \DateTime();
        $message = 'foo';
        $level = 'level';
        $context = ['bar'];

        /** @var DumperInterface|\PHPUnit_Framework_MockObject_MockObject $dumper */
        $dumper = $this->createMock(DumperInterface::class);
        $dumper->expects($this->exactly(2))
          ->method('dump')
          ->willReturn('OK');

        $record = new Record($datetime, $message, $level, $context);

        $formatter = new StandardFormatter($dumper, 1);
        $formatted = $formatter->format($record);

        $this->assertStringMatchesFormat(
          ' [%s] level: foo
  Context:
   OK
',
          $formatted
        );

        $formatter = new StandardFormatter($dumper, 2);
        $formatted = $formatter->format($record);

        $this->assertStringMatchesFormat(
          '  [%s] level: foo
    Context:
      OK
',
          $formatted
        );
    }
}
