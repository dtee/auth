<?php
namespace Odl\AuthBundle\Controller;

use Dtc\GridBundle\Grid\Source\DocumentGridSource;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;

class UserController
	extends Controller
{

	/**
	 * @Route("/profile/{userId}")
	 * @Template()
	 */
	public function profileAction($userId)
	{
		$userManager = $this->get('fos_user.user_manager');
		$userAuth = $userManager->findUserBy(array('id' => $userId));

		if (!$userAuth) {
		    throw new \RuntimeException("User id {$userId} not found.");
		}

		return array(
		    'auth' => $userAuth
		);
	}

	/**
	 * @Route("list")
	 * @Template()
	 *
     * @Secure(roles="ROLE_USER, ROLE_FOO, ROLE_ADMIN")
	 */
	public function listAction() {
		$renderer = $this->get('grid.renderer.jq_grid');
		//$renderer = $this->get('grid.renderer.html');

		$gridSource = $this->get('grid.source.user_auth');
		$renderer->bind($gridSource);

		return array(
			'grid' => $renderer,
		);
	}

	public function edit() {

	}
}
