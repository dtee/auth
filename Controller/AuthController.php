<?php
namespace Odl\AuthBundle\Controller;

use Odl\AuthBundle\Form\UsernamePasswordType;
use Odl\AuthBundle\Documents\UserAuth;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AuthController
	extends Controller
{

	/**
	 * @extra:Route("/register")
	 */
	public function createAction()
	{
		$formFactory = $this->get('form.factory');
		$request = $this->get('request');

		$userAuth = new UserAuth();
		$form = $formFactory
			->createBuilder('form', $userAuth)
			->add('usernamePasswordAuth', new UsernamePasswordType(), array(
				'label' => null
			))
			->getForm();

		// Creates user auth
		if ($form->isValid()) {
			// Handle success, lets create a user?
			$dm = $this->get('doctrine.odm.mongodb.default_document_manager');
			$factory = $this->get('security.encoder_factory');

			$salt = time();
			$userAuth->salt = $salt;
			$userAuth->roles = array('ROLE_USER', 'ROLE_ADMIN');

			$encoder = $factory->getEncoder($userAuth);
			$userAuth->password = $encoder->encodePassword('user', $salt);

			$dm->persist($userAuth);
	        $dm->flush();
        }

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
	 * @extra:Route("/")
	 * @Template()
	 */
	public function authAction()
	{
		$params = array();
		$params = array('form' => $this->getCreateForm());

		return $params;
	}
}
