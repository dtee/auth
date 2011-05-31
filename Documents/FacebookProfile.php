<?php
namespace Odl\AuthBundle\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\EmbeddedDocument
 */
class FacebookProfile
	extends Profile
{
	/**
	 * @ODM\String
	 * @Assert\NotBlank
	 */
	protected $facebookUserId;

	/**
	 * @ODM\Hash
	 */
	protected $facebookUserInfo;

	/**
	 * @ODM\Hash
	 */
	protected $friends;

	public function __construct(array $facebookUserInfo = array())
	{
		$this->setFacebookUserInfo($facebookUserInfo);
	}

	public function setFriends($friends)
	{
		$this->friends = $friends;
	}

	public function getFriends() {
		return $this->friends;
	}

	public function getEmail() {
		if (isset($this->facebookUserInfo['email']))
			return $this->facebookUserInfo['email'];
	}
	/**
	 * @return the $facebookUserInfo
	 */
	public function getFacebookUserInfo()
	{
		return $this->facebookUserInfo;
	}

	/**
	 * @param array $facebookUserInfo
	 */
	public function setFacebookUserInfo(array $facebookUserInfo)
	{
		$this->facebookUserInfo = $facebookUserInfo;

		if (isset($this->facebookUserInfo['id']))
		{
			$this->setFacebookUserId($this->facebookUserInfo['id']);
		}

		if (isset($this->facebookUserInfo['first_name']))
		{
			$this->setFirstName($this->facebookUserInfo['first_name']);
		}

		if (isset($this->facebookUserInfo['last_name']))
		{
			$this->setLastName($this->facebookUserInfo['last_name']);
		}
	}

	/**
	 * @return the $facebookUserId
	 */
	public function getFacebookUserId()
	{
		return $this->facebookUserId;
	}

	/**
	 * @param $facebookUserId
	 */
	public function setFacebookUserId($facebookUserId)
	{
		$this->facebookUserId = $facebookUserId;
	}
}
