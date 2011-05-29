<?php
namespace Odl\AuthBundle\Model;

use Odl\AuthBundle\Documents\FacebookProfile;
use Odl\AuthBundle\Documents\UserAuth;
use Doctrine\ODM\MongoDB\DocumentManager;
use Facebook;

class FacebookUserManager {
	private $dm;
	private $facebook;
	private $userRepository;

	public function __construct(
		DocumentManager $dm,
		Facebook $facebook) {


		$this->dm = $dm;
		$this->facebook = $facebook;

		$this->userRepository =
			$this->dm->getRepository('Odl\AuthBundle\Documents\UserAuth');
	}

	public function updateUser(UserAuth $userAuth) {
		if (!$userAuth->getGroups()) {
			$userAuth->setGroups(array());
		}

/*		$this->dm->persist($userAuth);
		$this->dm->flush(); */
	}

	public function updateFriends(UserAuth $userAuth) {
		$facebookProfile = $userAuth->getFacebookProfile();

		if (!$facebookProfile)
			return;

		$facebookUserId  = $facebookProfile->getFacebookUserId();

		try {
			$friends = $this->facebook->api("/{$facebookUserId}/friends");
			$friends = $friends['data'];
			$facebookProfile->setFriends($friends);
		}
		catch (\Exception $ex) {
		}

		// Lets get friends also
		$userAuth->setUsername('fb_' . $facebookProfile->getFacebookUserId());
		$userAuth->setFacebookProfile($facebookProfile);

		$this->updateUser($userAuth);
	}

	protected $facebookUserIdCache = array();
	public function createOrGetUser($facebookUserId) {
		// See if the user exists
		$query = array(
			'facebookProfile.facebookUserId' => $facebookUserId
		);

		if (isset($this->facebookUserIdCache[$facebookUserId])) {
			return $this->facebookUserIdCache[$facebookUserId];
		}

		$userAuth = $this->userRepository->findOneBy($query);

		if (!$userAuth) {
			// Lets create a new user
			$userAuth = new UserAuth();
/*			$request = array(
				array('method' => 'POST', 'relative_url' => "{$facebookUserId}"),
				array('method' => 'POST', 'relative_url' => "{$facebookUserId}/friends?limit=1024"),
			);

			$request = array(
				'batch' => $request,
				'access_token' => $this->facebook->getAccessToken()
			);

			$fbInfo = $this->facebook->api('/?batch', 'POST', $request); */
			try {
				$fbInfo = $this->facebook->api("/{$facebookUserId}");
			}
			catch (\Exception $ex) {
				$this->facebookUserIdCache[$facebookUserId] = null;
				return null;
			}

			if ($fbInfo) {
				$facebookProfile = new FacebookProfile($fbInfo);

				try {
					$friends = $this->facebook->api("/{$facebookUserId}/friends");
					$friends = $friends['data'];
					$facebookProfile->setFriends($friends);
				}
				catch (\Exception $ex) {
				}

				// Lets get friends also
				$userAuth->setUsername('fb_' . $facebookProfile->getFacebookUserId());
				$userAuth->setFacebookProfile($facebookProfile);

				$this->dm->persist($userAuth);
				$this->dm->flush();
			}
		}
		else {
			// User exists
		}

		$this->facebookUserIdCache[$facebookUserId] = $userAuth;
		$this->dm->detach($userAuth);
		return $userAuth;
	}

	public function getFacebookUsersById($facebookUserIds) {
		$query = array(
			'facebookProfile.facebookUserId' => array (
				'$in' => $facebookUserIds
			)
		);

		ve($query);
		$userAuth = $this->userRepository->findOneBy($query);

	}
}