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
        if (!empty($values['rules'])) {
            $this->rules = $values['rules'];
        } else {
            $this->rules = $values['value'];
        }
    }

}
