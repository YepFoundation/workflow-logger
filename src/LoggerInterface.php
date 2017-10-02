<?php

namespace Yep\WorkflowLogger;

/**
 * Interface LoggerInterface
 *
 * @package Yep\WorkflowLogger
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
interface LoggerInterface extends \Psr\Log\LoggerInterface
{
    /**
     * @param string|null $key
     * @return Workflow
     */
    public function workflow($key = null);
}
