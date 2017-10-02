<?php

namespace Tests\Yep\WorkflowLogger\Formatter;

use Tests\Yep\WorkflowLogger\ContextDumper\DataTrait;
use Yep\WorkflowLogger\ContextDumper\SymfonyVarDumper;

require_once __DIR__.'/DataTrait.php';

/**
 * Class SymfonyVarDumperTest
 *
 * @author Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class SymfonyVarDumperTest extends \PHPUnit_Framework_TestCase
{
    use DataTrait;

    public function testDump()
    {
        $dumper = new SymfonyVarDumper();
        $context = $this->getTestData();

        $expected = '[
  true
  false
  null
  1
  1.1
  "string"
  []
  {#%x}
]
';
        $this->assertStringMatchesFormat($expected, $dumper->dump($context));
    }
}
