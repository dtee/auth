<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\FacebookBundle\Firewall;

use Acme\DemoBundle\MongoDB\MongoDBFacebookAuthProvider;

use Symfony\Component\Security\Core\User\ChainUserProvider;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

use Symfony\Component\Security\Http\AccessMap;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Events;

/**
 * ChannelListener switches the HTTP protocol based on the access control
 * configuration.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class FacebookListener implements ListenerInterface
{
	/**
	 *
	 * @var Facebook
	 */
	private $facebook;
	private $context;
	private $userProvider;

	public function __construct(
		SecurityContext $context,
		\Facebook $facebook,
		MongoDBFacebookAuthProvider $userProvider,
		LoggerInterface $logger = null)
	{
		$this->context = $context;
		$this->facebook = $facebook;
		$this->logger = $logger;
		$this->userProvider = $userProvider;
	}

	/**
	 * Handles anonymous authentication.
	 *
	 * @param GetResponseEvent $event A GetResponseEvent instance
	 */
	public function handle(GetResponseEvent $event)
	{
		if (null !== $this->context->getToken())
		{
			return;
		}

		if ($fbUserId = $this->facebook->getUser())
		{
			// Look for the facebook user using providers
			$userAuth = $this->userProvider->getOrCreateUser($fbUserId);

			if (!$userAuth)
			{
				$this->logger->debug(sprintf("No user auth for {$fbUserId} - did not give us permission?"));
				return;
			}

			// Facebook user don't need to authentication (authenticationManager)
			$this->context->setToken(new FacebookUserToken($userAuth));

			if (null !== $this->logger)
			{
				$this->logger->debug(sprintf("Populated SecurityContext with facebook user {$fbUserId}"));
			}
		}
		else
		{
			if (null !== $this->logger)
			{
				$this->logger->debug(sprintf("No Facebook User Id"));
			}
		}
	}
}
