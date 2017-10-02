<?php

namespace Tests\Yep\WorkflowLogger\Formatter;

use Tests\Yep\WorkflowLogger\ContextDumper\DataTrait;
use Yep\WorkflowLogger\ContextDumper\TracyDumper;

require_once __DIR__.'/DataTrait.php';

/**
 * Class TracyDumperTest
 *
 * @author Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class TracyDumperTest extends \PHPUnit_Framework_TestCase
{
    use DataTrait;

    public function testDump()
    {
        $dumper = new TracyDumper();
        $context = $this->getTestData();

        $expected = 'array (8)
   0 => true
   1 => false
   2 => null
   3 => 1
   4 => 1.1
   5 => "string" (6)
   6 => array ()
   7 => stdClass #%x

';
        $this->assertStringMatchesFormat($expected, $dumper->dump($context));
    }
}
