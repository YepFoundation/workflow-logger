<?php

namespace Tests\Yep\WorkflowLogger\Formatter;

use Yep\WorkflowLogger\Exception\ExceptionInterface;
use Yep\WorkflowLogger\Exception\LevelIsNotDefinedException;

/**
 * Class LevelIsNotDefinedExceptionTest
 *
 * @author Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class LevelIsNotDefinedExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $exception = LevelIsNotDefinedException::create(
          'foo',
          [
            'a' => 0,
            'b' => 1,
          ]
        );

        $this->assertInstanceOf(LevelIsNotDefinedException::class, $exception);
        $this->assertInstanceOf(ExceptionInterface::class, $exception);
        $this->assertSame(
          'Level "foo" is not defined, use one of: "a", "b"',
          $exception->getMessage()
        );
    }
}
