<?php 

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoMondayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\NoMonday */

        if (null === $value || '' === $value) {
            return;
        }

        // Vérifier si le jour de la semaine est lundi (1)
        if ($value->format('N') == 1) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}

