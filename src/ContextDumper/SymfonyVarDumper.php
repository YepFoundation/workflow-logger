<?php

namespace Yep\WorkflowLogger\ContextDumper;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

/**
 * Class SymfonyVarDumper
 *
 * @package Yep\WorkflowLogger\ContextDumper
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class SymfonyVarDumper implements DumperInterface
{
    /**
     * @var VarCloner
     */
    protected $cloner;

    /**
     * @var CliDumper
     */
    protected $dumper;

    public function __construct($flags = CliDumper::DUMP_LIGHT_ARRAY)
    {
        $this->cloner = new VarCloner();
        $this->dumper = new CliDumper(null, null, $flags);
    }

    public function dump(array $context)
    {
        return $this->dumper->dump($this->cloner->cloneVar($context), true);
    }
}
