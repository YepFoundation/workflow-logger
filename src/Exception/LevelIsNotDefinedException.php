<?php

namespace Yep\WorkflowLogger\Exception;

use Psr\Log\InvalidArgumentException;

/**
 * Class LevelIsNotDefinedException
 *
 * @package Yep\WorkflowLogger\Exception
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class LevelIsNotDefinedException extends InvalidArgumentException implements ExceptionInterface
{
    /**
     * @param string $level
     * @param array  $levels
     * @return static
     */
    public static function create($level, array $levels)
    {
        $message = sprintf(
          'Level "%s" is not defined, use one of: "%s"',
          $level,
          implode('", "', array_keys($levels))
        );

        return new static($message);
    }
}
