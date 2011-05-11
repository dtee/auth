<?php
namespace Odl\AuthBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueUsernamePassword
	extends Constraint
{
    public $charset = 'UTF-8';
    public $message = 'There is an existing account associated with this email.';

    /**
     * {@inheritDoc}
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }

    public function validatedBy()
    {
    	return 'auth_unique_username_password';
    }
}
