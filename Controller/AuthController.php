<?php
namespace Odl\AuthBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

use Symfony\Component\HttpKernel\Events;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Odl\AuthBundle\Form\ProfileType;
use Odl\AuthBundle\Form\UsernamePasswordType;
use Odl\AuthBundle\Documents\UserAuth;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AuthController
	extends Controller
{
    /**
     * @Route("")
     */
    public function indexAction() {
        ve('blah');
    }

	/**
	 * @Route("/register/")
	 */
	public function registerAction()
	{
		// Set up form
		$formFactory = $this->get('form.factory');
		$request = $this->get('request');

		$userManager = $this->get('fos_user.user_manager');
		$userAuth = $userManager->createUser();

		$form = $formFactory
			->createBuilder('form', $userAuth)
    		->add('email', 'text')
    		->add('plainPassword', 'password', array(
    			'label' => 'New password'
    		))
			->add('profile', new ProfileType(), array(
				'label' => 'Profile Information'
			))
			->getForm();

		// Handles Success - database wise
		$retVal = array();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			// Creates user auth
			if ($form->isValid()) {
				// Handle success, lets create a user?
				$userManager->updateUser($userAuth, true);

		        // Log the user in
		        $userAuth->setPlainPassword(null);	// Remove password
				$this->authenticateUser($userAuth);

				$router = $this->get('router');
				$retVal['href'] = $router->generate('odl_shadow_main_index');
	        }
	        else
	        {
	        	$errorsProvider = $this->get('form.errors');
	        	$retVal['error'] = $errorsProvider->getErrors($form);
	        }
		}

		// Handles render -
        $response = new Response();
		if ($request->isXmlHttpRequest())
		{
        	$content = json_encode($retVal);
		}
		else
		{
			$params = array();
        	$params =  array(
        		'formView' => $form->createView()
        	);
			$content = $this->renderView(
				'OdlAuthBundle:Auth:create.html.twig', $params);
		}
		$response->setContent($content);

		return $response;
	}

	/**
	 * Log in as facebook user if the user already have an account
	 *
	 * @Route("/fb-auth")
	 * @Template()
	 */
	public function fbAuth() {
		$facebook = $this->get('fos_facebook.api');
		$currentToken = $this->get('security.context')->getToken();
		$user = null;
		if ($currentToken) {
			if ($currentToken instanceof UsernamePasswordToken)
			{
				$user = $currentToken->getUser();
			}
		}

		// Assumes most of the auth request is done via javascript
		if ($fbUserId = $facebook->getUser()) {
			// Check to see if we have the user in our system

			// If the user doesn't have an account - does he want to
			//	create a new one or merge with existing account?
		}

		ve($currentToken);
	}

	/**
	 * @Route("/info")
	 * @Template()
	 */
	public function info() {
        return new Response(phpinfo());
	}

	/**
	 * @Route("/login")
	 */
	public function loginAction()
	{
		$formFactory = $this->get('form.factory');
		$request = $this->get('request');
		$userManager = $this->get('fos_user.user_manager');

		$userAuth = $userManager->createUser();
		$form = $formFactory
			->createBuilder('form', $userAuth)
    		->add('email', 'text')
    		->add('plainPassword', 'password', array(
    			'label' => 'Password'
    		))
			->getForm();

		// Handles Success - database wise
		$retVal = array();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			// Creates user auth
			if ($form->isValid()) {
				$authManager = $this->get('security.authentication.manager');

				try {
					$token = $this->getToken($userAuth);
					$secureToken = $authManager->authenticate($token);

					$this->container->get('security.context')->setToken($secureToken);

					$router = $this->get('router');
					$retVal['href'] = $router->generate('odl_shadow_main_index');
				}
				catch (AccountStatusException $e)
				{
					$retVal['error']['form_plainPassword'][] = $e->getMessage();
				}
				catch (\Exception $ex)
				{
					$retVal['error']['form_plainPassword'][] = $ex->getMessage();
				}
	        }
	        else
	        {
	        	$errorsProvider = $this->get('form.errors');
	        	$retVal['error'] = array_merge($errorsProvider->getErrors($form));
	        }
		}

		// Handles render -
        $response = new Response();
		if ($request->isXmlHttpRequest())
		{
	        $content = json_encode($retVal);
		}
		else
		{
			$params = array();
        	$params =  array(
        		'formView' => $form->createView()
        	);
			$content = $this->renderView(
				'OdlAuthBundle:Auth:login.html.twig', $params);
		}
		$response->setContent($content);

		return $response;
	}

	/**
	 * @Route("/logout")
	 * @Template()
	 */
	public function logoutAction() {
		$this->container->get('security.context')->setToken(null);
		$router = $this->get('router');
		$url = $router->generate('odl_shadow_main_index');

        $response = new RedirectResponse($url);
        return $response;
	}

	/**
	 * @Route("/password-recover")
	 * @Template()
	 */
	public function forgetPasswordAction() {
		$this->container->get('security.context')->setToken(null);
		$router = $this->get('router');
		$url = $router->generate('odl_shadow_main_index');

        $response = new RedirectResponse($url);
        return $response;
	}

    /**
     * Authenticate a user with Symfony Security
     *
     * @param Boolean $reAuthenticate
     * @return null
     */
    protected function authenticateUser(UserInterface $user, $reAuthenticate = false)
    {
        $providerKey = $this->container->getParameter('fos_user.firewall_name');
        $token = new UsernamePasswordToken(
        	$user,
        	null,
        	$providerKey,
        	$user->getRoles());

        if (true === $reAuthenticate) {
            $token->setAuthenticated(false);
        }

        $this->container->get('security.context')->setToken($token);
    }

    protected function getToken(UserAuth $user, $reAuthenticate = false) {
        $providerKey = $this->container->getParameter('fos_user.firewall_name');
        $token = new UsernamePasswordToken(
        	$user->getEmail(),
        	$user->getPlainPassword(),
        	$providerKey,
        	$user->getRoles());

        if (true === $reAuthenticate) {
            $token->setAuthenticated(false);
        }

        return $token;
    }
}
