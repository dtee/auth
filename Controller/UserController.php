<?php
namespace Odl\AuthBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController
	extends Controller
{

	/**
	 * @Route("/profile/{userId}")
	 * @Template()
	 */
	public function profileAction($userId)
	{
		return new Response($userId);
	}
}
