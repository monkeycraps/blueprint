<?php

namespace In\Blueprint\Annotation;

/**
 * @Annotation
 */
class Response
{
    /**
     * @var int
     */
    public $statusCode;

    /**
     * @var string
     */
    public $contentType = 'application/json';

    /**
     * @var mixed
     */
    public $body;

    /**
     * @var array
     */
    public $headers = [];

    /**
     * @var array<In\Blueprint\Annotation\Attribute>
     */
    public $attributes;
}
