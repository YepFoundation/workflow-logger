<?php

namespace Tests\Yep\WorkflowLogger;

use Yep\Reflection\ReflectionClass;
use Yep\WorkflowLogger\Exception\LevelIsNotDefinedException;
use Yep\WorkflowLogger\Exception\WorkflowIsLockedException;
use Yep\WorkflowLogger\Formatter\FormatterInterface;
use Yep\WorkflowLogger\LoggerInterface;
use Yep\WorkflowLogger\Record;
use Yep\WorkflowLogger\Workflow;

/**
 * Class WorkflowTest
 *
 * @author Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class WorkflowTest extends \PHPUnit_Framework_TestCase
{
    protected function createTimezone()
    {
        return new \DateTimeZone('UTC');
    }

    /**
     * @param int $level
     * @return Workflow
     */
    protected function createWorkflow($level = 987)
    {
        /** @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var FormatterInterface|\PHPUnit_Framework_MockObject_MockObject $formatter */
        $formatter = $this->createMock(FormatterInterface::class);
        $timezone = $this->createTimezone();

        return new Workflow($logger, $formatter, $timezone, $level);
    }

    public function testLogMethodsAndSendMethod()
    {
        $level = 123;
        $sendMessage = 'Problem during something';
        $context = ['bar' => true];
        $message = $sendMessage.'

Workflow:
';
        //           call         message      context
        $calls[] = ['emergency', 'Emergency', ['dump' => 'emergency']];
        $calls[] = ['alert', 'Alert', ['dump' => 'alert']];
        $calls[] = ['critical', 'Critical', ['dump' => 'critical']];
        $calls[] = ['error', 'Error', ['dump' => 'error']];
        $calls[] = ['warning', 'Warning', ['dump' => 'warning']];
        $calls[] = ['notice', 'Notice', ['dump' => 'notice']];
        $calls[] = ['info', 'Info', ['dump' => 'info']];
        $calls[] = ['debug', 'Debug', ['dump' => 'debug']];

        $consecutiveCalls = [];

        foreach ($calls as list($call, $subMessage, $subContext)) {
            $consecutiveCalls[] = $this->callback(
              function ($record) use ($call, $subMessage, $subContext) {
                  if (!$record instanceof Record) {
                      return false;
                  }

                  if ($record->getLevel() !== $call) {
                      return false;
                  }

                  if ($record->getMessage() !== $subMessage) {
                      return false;
                  }

                  if ($record->getContext() !== $subContext) {
                      return false;
                  }

                  return true;
              }
            );

            $message .= 1;
        }

        // Add extra level ↓
        $extraLevelKey = 'test';
        $extraLevelName = 'TEST';
        $extraMessage = 'foo for test';
        $extraContext = ['context for test'];

        $consecutiveCalls[] = $this->callback(
          function ($record) use (
            $extraLevelName,
            $extraMessage,
            $extraContext
          ) {
              if (!$record instanceof Record) {
                  return false;
              }

              if ($record->getLevel() !== $extraLevelName) {
                  return false;
              }

              if ($record->getMessage() !== $extraMessage) {
                  return false;
              }

              if ($record->getContext() !== $extraContext) {
                  return false;
              }

              return true;
          }
        );

        $message .= 1;
        // Add extra level ↑

        /** @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
          ->method('log')
          ->with($level, $message, $context);

        /** @var FormatterInterface|\PHPUnit_Framework_MockObject_MockObject $formatter */
        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects($this->exactly(count($consecutiveCalls)))
          ->method('format')
          ->withConsecutive(...$consecutiveCalls)
          ->willReturn(1);

        $timezone = $this->createTimezone();
        $workflow = new Workflow($logger, $formatter, $timezone, $level);

        // Add extra level ↓
        $reflection = ReflectionClass::from($workflow);
        $levels = $reflection->getPropertyValue('levels');
        $reflection->setPropertyValue(
          'levels',
          $levels + [$extraLevelKey => $extraLevelName]
        );
        // Add extra level ↑

        foreach ($calls as list($call, $subMessage, $subContext)) {
            $workflow->{$call}($subMessage, $subContext);
        }

        $workflow->log($extraLevelKey, $extraMessage, $extraContext);
        $workflow->finish($sendMessage, $context);
    }

    public function testLogWithWrongLevel()
    {
        /** @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);

        /** @var FormatterInterface|\PHPUnit_Framework_MockObject_MockObject $formatter */
        $formatter = $this->createMock(FormatterInterface::class);

        $timezone = $this->createTimezone();
        $workflow = new Workflow($logger, $formatter, $timezone, 1);

        $this->expectException(LevelIsNotDefinedException::class);
        $workflow->log('wrong level key', '');
    }

    public function testGetCurrentDateTime()
    {
        $workflow = $this->createWorkflow();
        $reflection = ReflectionClass::from($workflow);

        /** @var \DateTime $datetime */
        $datetime = $reflection->invokeMethod('getCurrentDateTime');

        $this->assertInstanceOf(\DateTime::class, $datetime);

        /** @var \DateTime $datetimeB */
        $datetimeB = $reflection->invokeMethod('getCurrentDateTime');

        $this->assertNotSame($datetime, $datetimeB);
    }

    public function testGetLevel()
    {
        $level = 456;
        $workflow = $this->createWorkflow($level);
        $this->assertSame($level, $workflow->getLevel());
    }

    public function testGetLevelName()
    {
        $workflow = $this->createWorkflow();
        $reflection = ReflectionClass::from($workflow);

        $levelKey = 'key';
        $levelName = 'name';
        $reflection->setPropertyValue('levels', [$levelKey => $levelName]);

        $this->assertSame($levelName, $workflow->getLevelName($levelKey));

        $this->expectException(LevelIsNotDefinedException::class);
        $workflow->getLevelName('wrong level key');
    }

    public function testLock()
    {
        $workflow = $this->createWorkflow();
        $this->assertFalse($workflow->isLocked());
        $workflow->lock();
        $this->assertTrue($workflow->isLocked());
    }

    public function testLogWithLock()
    {
        $workflow = $this->createWorkflow();
        $workflow->lock();
        $this->expectException(WorkflowIsLockedException::class);
        $workflow->log(Workflow::ERROR, 'foo');
    }

    public function testFinishWithLock()
    {
        $workflow = $this->createWorkflow();
        $workflow->lock();
        $this->expectException(WorkflowIsLockedException::class);
        $workflow->finish();
    }
}
