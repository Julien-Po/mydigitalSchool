<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NoMonday extends Constraint
{
    public $message = 'Les réservations ne sont pas possibles le lundi.';
}
