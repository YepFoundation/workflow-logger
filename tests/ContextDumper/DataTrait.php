<?php

namespace Tests\Yep\WorkflowLogger\ContextDumper;

trait DataTrait
{
    protected function getTestData()
    {
        return [
          true,
          false,
          null,
          1,
          1.1,
          'string',
          [],
          new \stdClass(),
        ];
    }
}
