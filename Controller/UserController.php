<?php
namespace Odl\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController
	extends Controller
{
	
	/**
	 * @extra:Route("/profile/{userId}")
	 * @Template()
	 */
	public function profileAction($userId)
	{
		return new Response($userId);
	}
}
