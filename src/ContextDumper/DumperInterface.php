<?php

namespace Yep\WorkflowLogger\ContextDumper;

/**
 * Interface DumperInterface
 *
 * @package Yep\WorkflowLogger\ContextDumper
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
interface DumperInterface
{
    public function dump(array $context);
}
