<?php

namespace Tests\Yep\WorkflowLogger\Formatter;

use Yep\WorkflowLogger\Exception\ExceptionInterface;
use Yep\WorkflowLogger\Exception\WorkflowIsLockedException;

/**
 * Class WorkflowIsLockedExceptionTest
 *
 * @author Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class WorkflowIsLockedExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutKey()
    {
        $exception = WorkflowIsLockedException::create();

        $this->assertInstanceOf(WorkflowIsLockedException::class, $exception);
        $this->assertInstanceOf(ExceptionInterface::class, $exception);
        $this->assertSame('Workflow is locked', $exception->getMessage());
    }

    public function testWithKey()
    {
        $exception = WorkflowIsLockedException::create('foo');

        $this->assertInstanceOf(WorkflowIsLockedException::class, $exception);
        $this->assertInstanceOf(ExceptionInterface::class, $exception);
        $this->assertSame('Workflow "foo" is locked', $exception->getMessage());
    }
}
