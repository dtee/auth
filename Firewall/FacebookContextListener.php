<?php
namespace Acme\FacebookBundle\Firewall;

use Symfony\Component\Security\Http\Firewall\ContextListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Events;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Acme\DemoBundle\MongoDB\MongoDBFacebookAuthProvider;

class FacebookContextListener
	extends ContextListener
{
	private $facebook;
	private $userProvider;

    public function __construct(
    	SecurityContext $context,
    	array $userProviders,
    	$contextKey,
    	LoggerInterface $logger = null,
    	EventDispatcherInterface $dispatcher = null,
    	\Facebook $facebook,
		MongoDBFacebookAuthProvider $userProvider)
    {
        parent::__construct($context, $userProviders, $contextKey, $logger, $dispatcher);
        $this->facebook = $facebook;
		$this->userProvider = $userProvider;
    }

	/**
     * Reads the SecurityContext from the session.
     *
     * @param GetResponseEvent $event A GetResponseEvent instance
     */
    public function handle(GetResponseEvent $event)
    {
		if ($fbUserId = $this->facebook->getUser())
		{
			// Look for the facebook user using providers
			$userAuth = $this->userProvider->getOrCreateUser($fbUserId);

			if (!$userAuth)
			{
				$this->logger->debug(sprintf("No user auth for {$fbUserId} - did not give us permission?"));
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

		if (null == $this->context->getToken())
		{
        	parent::handle($event);
		}
    }

    /**
     * Writes the SecurityContext to the session.
     *
     * @param FilterResponseEvent $event A FilterResponseEvent instance
     */
    public function onCoreResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        if ($token = $this->context->getToken()) {
            if ($token instanceof FacebookUserToken)
            {
				return;
            }
        }

        parent::onCoreResponse($event);
    }
}