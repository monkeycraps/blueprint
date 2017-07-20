<?php

namespace In\Blueprint\Annotation;

/**
 * @Annotation
 */
class Transaction
{
    /**
     * @array<Request|Response>
     */
    public $value;
}
