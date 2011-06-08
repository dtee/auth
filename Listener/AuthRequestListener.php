<?php
namespace Odl\AuthBundle\Listener;

use Symfony\Component\Security\Core\SecurityContext;

use Odl\AuthBundle\Firewall\FacebookUserToken;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Odl\AuthBundle\Model\FacebookUserManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Facebook;

class AuthRequestListener {
    private $userProvider;
    private $facebook;
    private $logger;
    private $context;

    public function __construct(
        SecurityContext $context,
        Facebook $facebook,
        FacebookUserManager $userProvider,
        LoggerInterface $logger = null) {

        $this->context = $context;
        $this->facebook = $facebook;
        $this->userProvider = $userProvider;
        $this->logger = $logger;
    }

    /**
     * Handles security.
     *
     * @param GetResponseEvent $event An GetResponseEvent instance
     */
    public function onCoreRequest(GetResponseEvent $event) {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType ()) {
            return;
        }

        // Look for the facebook user using providers
        if ($fbUserId = $this->facebook->getUser ()) {
            $userAuth = $this->userProvider->createOrGetUser ( $fbUserId );

            if (! $userAuth) {
                if ($this->logger)
                    $this->logger->debug ( sprintf ( "No user auth for {$fbUserId} - did not give us permission?" ) );
            } else {
                // Facebook user don't need to authentication (authenticationManager)
                $this->context->setToken ( new FacebookUserToken( $userAuth ) );
            }

            if (null !== $this->logger) {
                $this->logger->debug ( sprintf ( "Populated SecurityContext with facebook user {$fbUserId}" ) );
            }
        } else {
            if (null !== $this->logger) {
                $this->logger->debug ( sprintf ( "No Facebook User Id" ) );
            }
        }
    }
}