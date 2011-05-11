<?php
namespace Odl\AuthBundle\Controller;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Odl\AuthBundle\Documents\UsernamePasswordAuth;

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
		$authenticationManager = $this->get('security.authentication.manager');
		$token = new UsernamePasswordToken('test', 'test', 'create_auth');

		$userProvider = $this->get('auth.mongodb.username_password_provider');

		$userAuth = $userProvider->loadUserByUsername('davidkmo@gmail.com');

		$usernamePasswordAuth = $userAuth->getUsernamePasswordAuth();
		v($usernamePasswordAuth);
		ve($userAuth);

        $token = new UsernamePasswordToken(
        	$usernamePasswordAuth->getUsername(),
        	$usernamePasswordAuth->getPassword(),
        	'admin');

        $authenticationManager->authenticate($token);

		sort($ids);
		ve($ids);

		// Set up form
		$facebook = $this->get('facebook');
		$formFactory = $this->get('form.factory');
		$request = $this->get('request');

		$userAuth = new UserAuth();
		$userAuth->setRoles(array('ROLE_USER'));

		$usernamePasswordAuth = new UsernamePasswordAuth();
		$usernamePasswordAuth->setSalt(time());
		$userAuth->setUsernamePasswordAuth($usernamePasswordAuth);

		$form = $formFactory
			->createBuilder('form', $userAuth, array(
				'label' => 'Sign up'
			))
			->add('profile', new ProfileType(), array(
				'label' => 'Profile Information'
			))
			->add('usernamePasswordAuth', new UsernamePasswordType(), array(
				'label' => 'Account Information'
			))
			->getForm();

		// Handles Success - database wise
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			// Creates user auth
			if ($form->isValid()) {
				// Handle success, lets create a user?
				$dm = $this->get('doctrine.odm.mongodb.default_document_manager');
				$factory = $this->get('security.encoder_factory');
				$encoder = $factory->getEncoder($usernamePasswordAuth);
				$password = $encoder->encodePassword(
					'user', $usernamePasswordAuth->getSalt());

				$usernamePasswordAuth->setPassword($password);

				$dm->persist($userAuth);
		        $dm->flush();

		        // Lets log the user into the system... raise event?
		        $authenticationManager = $this->get('security.authentication.manager');
		        $token = new UsernamePasswordToken(
		        	$usernamePasswordAuth->getUsername(),
		        	$usernamePasswordAuth->getPassword(),
		        	$this->providerKey);

		        $success = $authenticationManager->authenticate($token);

		        // Where shall we redirect them to?
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
	 * @extra:Route("/login")
	 * @Template()
	 */
	public function loginAction()
	{
		$facebook = $this->get('facebook');
		$formFactory = $this->get('form.factory');
		$request = $this->get('request');

		$form = $formFactory
			->createBuilder('form', $userAuth, array(
				'label' => 'Sign up'
			))
			->add('profile', new ProfileType(), array(
				'label' => 'Profile Information'
			))
			->add('usernamePasswordAuth', new UsernamePasswordType(), array(
				'label' => 'Account Information'
			))
			->getForm();
	}
}
