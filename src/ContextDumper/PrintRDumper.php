<?php

namespace Yep\WorkflowLogger\ContextDumper;

/**
 * Class PrintRDumper
 *
 * @package Yep\WorkflowLogger\ContextDumper
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class PrintRDumper implements DumperInterface
{
    public function dump(array $context)
    {
        return print_r($context, true);
    }
}
