<?php

namespace Deliv\Resource;

/**
 * Exception
 *
 * An exception object represents the event or defect that caused a delivery
 * or fetch to be canceled or returned.
 *
 * @see http://docs.deliv.co/v2/#exception
 *
 */
class Exception extends AbstractResource
{
    /** @var int $code An integer representing the unique exception or fuckup */
    public $code;

    /** @var string $Description A terse human readable description of the exception or defect */
    public $Description;
    /** @var string $description A terse human readable description of the exception or defect */
    public $description;

    /**
     * Exception constructor.
     * @param \stdClass $data
     *
     * Description vs description. API says
     */
    public function __construct(\stdClass $data)
    {
        parent::__construct($data);
        $this->description = $this->Description;
        unset($this->Description);
    }

}
