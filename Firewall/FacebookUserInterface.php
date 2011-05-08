<?php
namespace Odl\AuthBundle\Firewall;

use Symfony\Component\Security\Core\User\UserInterface;

interface FacebookUserInterface
{
	public function getFacebookUserID();
}
