<?php
namespace Odl\AuthBundle\Controller;

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
	 * @extra:Route("/register/")
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
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			// Creates user auth
			if ($form->isValid()) {
				// Handle success, lets create a user?

				$userManager->updateUser($userAuth, true);

		        // Log the user in
				$this->authenticateUser($userAuth);
				$retVal['href'] = '/';
	        }
		}

		// Handles render -
        $response = new Response();
		if ($request->isXmlHttpRequest())
		{
        	$errorsProvider = $this->get('form.errors');
        	$retVal['error'] = $errorsProvider->getErrors($form);
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
	 * @extra:Route("/fb-register")
	 * @Template()
	 */
	public function fbRegister() {
		$facebook = $this->get('facebook');
	}

	/**
	 * @extra:Route("/login")
	 */
	public function loginAction()
	{
		$formFactory = $this->get('form.factory');
		$request = $this->get('request');
		$userManager = $this->get('fos_user.user_manager');

		$userAuth = $userManager->createUser();
		$form = $formFactory
			->createBuilder('form')
    		->add('email', 'text')
    		->add('plainPassword', 'password', array(
    			'label' => 'Password'
    		))
			->getForm();

		// Handles Success - database wise
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			$data = $form->getData();
			ve($data);
			// Creates user auth
			if ($form->isValid()) {
				// Handle success, lets create a user?

				$userManager->updateUser($userAuth, true);

		        // Log the user in
				$this->authenticateUser($userAuth);
				$retVal['href'] = '/';
	        }
		}

		// Handles render -
        $response = new Response();
		if ($request->isXmlHttpRequest())
		{
        	$errorsProvider = $this->get('form.errors');
        	$retVal['error'] = $errorsProvider->getErrors($form);
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
	 * @extra:Route("/logout")
	 * @Template()
	 */
	public function logoutAction() {

	}

	/**
	 * @extra:Route("/password-recover")
	 * @Template()
	 */
	public function forgetPasswordAction() {

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
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        if (true === $reAuthenticate) {
            $token->setAuthenticated(false);
        }

        $this->container->get('security.context')->setToken($token);
    }
}
