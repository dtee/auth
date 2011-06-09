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
     * @Route("test")
     */
    public function testAction() {
		$fbUserManager = $this->get('auth.facebook_user_manager');
		$userAuth = $fbUserManager->createOrGetUser('663694611');

		if ($profile = $userAuth->getFacebookProfile()) {
		    foreach ($profile->getFriends() as $friendInfo)
		    {
		        $fbUserManager->createOrGetUser($friendInfo['id']);
		        v($friendInfo);
		        flush();
		    }
		}
    }

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
