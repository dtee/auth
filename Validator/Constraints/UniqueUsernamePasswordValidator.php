<?php
namespace Odl\AuthBundle\Validator\Constraints;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Symfony\Component\Security\Core\User\UserProviderInterface;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Doctrine\ODM\MongoDB\DocumentManager;

class UniqueUsernamePasswordValidator
	extends ConstraintValidator
{
	/**
	 * @var UserProviderInterface
	 */
	protected $userProvider;

	public function __construct(UserProviderInterface $userProvider) {
		$this->userProvider = $userProvider;
	}

	public function isValid($username, Constraint $constraint)
    {
    	if (!$username)
    		return true;

		$user = $this->userProvider->loadUserByUsername($username);

		if ($user) {
            $this->setMessage($constraint->message, array(
            	'{{ username }}' => $username
            ));

            return false;
		}

		return true;
    }
}