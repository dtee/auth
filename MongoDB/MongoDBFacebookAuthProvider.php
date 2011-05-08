<?php
namespace Odl\AuthBundle\MongoDB;

use Acme\DemoBundle\Documents\UsernamePasswordAuth;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Acme\DemoBundle\Documents\UserAuth;
use Acme\DemoBundle\Documents\FacebookProfile;
use Acme\DemoBundle\Documents\FacebookAuth;

class MongoDBFacebookAuthProvider
{
	private $dm;
	private $facebook;
	private $documentName;
	private $repository;

	public function __construct(
		\Facebook $facebook,
		DocumentManager $dm,
		$documentName)
	{
		$this->dm = $dm;
		$this->facebook = $facebook;
    	$this->documentName = $documentName;
    	$this->repository = $this->dm->getRepository($documentName);
	}

	public function getOrCreateUser($facebookUserId)
	{
		$query = array('facebookAuth.$id' => $facebookUserId);
		$userAuth = $this->repository->findOneBy($query);

		if (!$userAuth)
		{
			$facebookUserInfo = $this->facebook->api('/' . $facebookUserId);
			if ($facebookUserInfo)
			{
				$userAuth = new UserAuth();
				$fbAuth = new FacebookAuth($facebookUserInfo);
				$userAuth->setFacebookAuth($fbAuth);

				$this->dm->persist($fbAuth);
				$this->dm->persist($userAuth);
				$this->dm->flush();
			}
			else
			{
				throw new Exception("Unable to find info for facebook user {$facebookUserId	}");
			}
		}

		return $userAuth;
	}

	public function createUsernamePasswordAuth(UserAuth $userAuth)
	{
		if ($usernamePasswordAuth = $userAuth->getUsernamePasswordAuth())
		{
			return $usernamePasswordAuth;
		}

		$facebookAuth = $userAuth->getFacebookAuth();
		if ($facebookAuth)
		{
			if ($facebookProfile = $facebookAuth->getProfile())
			{
				$facebookUserInfo = $facebookProfile->getFacebookUserInfo();
				if (isset($facebookUserInfo['email']))
				{
					$email = $facebookUserInfo['email'];
					$usernamePasswordAuth = new UsernamePasswordAuth();
					$usernamePasswordAuth->setEmail($email);
					$usernamePasswordAuth->setSalt(rand(1, time()));

					$userAuth->setUsernamePasswordAuth($usernamePasswordAuth);

					$this->dm->persist($usernamePasswordAuth);
					$this->dm->persist($userAuth);
					$this->dm->flush();

					return $usernamePasswordAuth;
				}
			}
		}

		return null;
	}
}
