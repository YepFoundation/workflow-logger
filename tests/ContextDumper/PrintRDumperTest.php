<?php

namespace Tests\Yep\WorkflowLogger\Formatter;

use Tests\Yep\WorkflowLogger\ContextDumper\DataTrait;
use Yep\WorkflowLogger\ContextDumper\PrintRDumper;

require_once __DIR__.'/DataTrait.php';

/**
 * Class PrintRDumperTest
 *
 * @author Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class PrintRDumperTest extends \PHPUnit_Framework_TestCase
{
    use DataTrait;

    public function testDump()
    {
        $dumper = new PrintRDumper();
        $context = $this->getTestData();

        $this->assertSame(print_r($context, true), $dumper->dump($context));
    }
}
