<?php

namespace Tests\Yep\WorkflowLogger;

use Yep\Reflection\ReflectionClass;
use Yep\WorkflowLogger\Exception\WorkflowIsLockedException;
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

        $workflow = $logger->workflow();
        $this->assertInstanceOf(Workflow::class, $workflow);
        $this->assertSame(Logger::WORKFLOW, $workflow->getLevel());

        $workflowB = $logger->workflow();
        $this->assertInstanceOf(Workflow::class, $workflowB);
        $this->assertNotSame($workflow, $workflowB);

        $workflowC = $logger->workflow('c');
        $this->assertInstanceOf(Workflow::class, $workflowC);
        $this->assertNotSame($workflow, $workflowC);

        $workflowCC = $logger->workflow('c');
        $this->assertInstanceOf(Workflow::class, $workflowCC);
        $this->assertSame($workflowCC, $workflowC);

        $workflowC->lock();

        $this->expectException(WorkflowIsLockedException::class);
        $logger->workflow('c');
    }

    public function testGetDateTimeZone()
    {
        $logger = $this->createLogger();
        $reflection = ReflectionClass::from($logger);

        /** @var \DateTimeZone $timezone */
        $timezone = $reflection->invokeMethod('getDateTimeZone');
        $this->assertInstanceOf(\DateTimeZone::class, $timezone);

        /** @var \DateTimeZone $timezoneB */
        $timezoneB = $reflection->invokeMethod('getDateTimeZone');
        $this->assertSame($timezone, $timezoneB);
    }
}
