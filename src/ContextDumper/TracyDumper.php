<?php

namespace Yep\WorkflowLogger\ContextDumper;

use Tracy\Dumper;

/**
 * Class TracyDumper
 *
 * @package Yep\WorkflowLogger\ContextDumper
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class TracyDumper implements DumperInterface
{
    /**
     * @var array
     */
    protected $options;

    public function __construct(
      array $options = [Dumper::DEPTH => 8, Dumper::TRUNCATE => 512]
    ) {
        $this->options = $options;
    }

    public function dump(array $context)
    {
        return Dumper::toText($context, $this->options);
    }
}
