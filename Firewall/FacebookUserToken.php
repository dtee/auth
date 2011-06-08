<?php
namespace Odl\AuthBundle\Firewall;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class FacebookUserToken
	extends AbstractToken
{
	public function __construct($user)
    {
        parent::__construct($user->getRoles());
        $this->setUser($user);
        $this->setAuthenticated(true);
    }

    public function unserialize($serialized)
    {
    	parent::unserialize($serialized);
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials()
    {
        return '';
    }
}
