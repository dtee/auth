<?php
namespace Acme\FacebookBundle\Firewall;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

use Acme\DemoBundle\Documents\FacebookAuth;

use Acme\DemoBundle\Documents\UserAuth;

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
		$userAuth = new UserAuth();
		$fbAuth = new FacebookAuth();
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
