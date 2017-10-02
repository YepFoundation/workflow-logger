<?php

namespace Tests\Yep\WorkflowLogger;

use Yep\Reflection\ReflectionClass;
use Yep\WorkflowLogger\Formatter\FormatterInterface;
use Yep\WorkflowLogger\Logger;
use Yep\WorkflowLogger\Workflow;

/**
 * Class LoggerTest
 *
 * @author Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class LoggerTest extends \PHPUnit_Framework_TestCase
{
    protected function createLogger()
    {
        /** @var FormatterInterface|\PHPUnit_Framework_MockObject_MockObject $formatter */
        $formatter = $this->createMock(FormatterInterface::class);

        return new Logger('Foo', $formatter);
    }

    public function testCreateWorkflow()
    {
        $logger = $this->createLogger();

        $workflow = $logger->workflow('Important workflow');

        $this->assertInstanceOf(Workflow::class, $workflow);
        $this->assertSame('Important workflow', $workflow->getName());
        $this->assertSame(Logger::WORKFLOW, $workflow->getLevel());
    }

    public function testTimezoneFactory()
    {
        $logger = $this->createLogger();
        $reflection = ReflectionClass::from($logger);

        /** @var \DateTimeZone $timezone */
        $timezone = $reflection->invokeMethod('timezoneFactory');
        $this->assertInstanceOf(\DateTimeZone::class, $timezone);

        /** @var \DateTimeZone $timezoneB */
        $timezoneB = $reflection->invokeMethod('timezoneFactory');
        $this->assertSame($timezone, $timezoneB);
    }
}
