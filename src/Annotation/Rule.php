<?php

namespace Kassner\AuthBundle\Annotation;

/**
 * @Annotation
 */
class Rule
{

    public $rules = "";

    public function __construct(array $values)
    {
        $this->rules = $values['value'];
    }

}