<?php

namespace Yep\WorkflowLogger;

/**
 * Class Record
 *
 * @package Yep\WorkflowLogger
 * @author  Martin Zeman (Zemistr) <me@zemistr.eu>
 */
class Record
{
    /**
     * @var array
     */
    protected $context;

    /**
     * @var \DateTime
     */
    protected $datetime;

    /**
     * @var string
     */
    protected $level;

    /**
     * @var string
     */
    protected $message;

    /**
     * Record constructor.
     *
     * @param \DateTime $datetime
     * @param string    $message
     * @param string    $level
     * @param array     $context
     */
    public function __construct(
      \DateTime $datetime,
      $message,
      $level,
      array $context
    ) {
        $this->datetime = $datetime;
        $this->message = (string)$message;
        $this->level = (string)$level;
        $this->context = $context;
    }

    /**
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }
}
