<?php

namespace Yep\WorkflowLogger\Exception;

/**
 * Class WorkflowIsLockedException
 *
 * @package Yep\WorkflowLogger\Exception
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class WorkflowIsLockedException extends \Exception implements ExceptionInterface
{
    /**
     * @param null|string $key
     * @return static
     */
    public static function create($key = null)
    {
        $message = 'Workflow ';

        if ($key !== null) {
            $message .= sprintf('"%s" ', $key);
        }

        $message .= 'is locked';

        return new static($message);
    }
}
