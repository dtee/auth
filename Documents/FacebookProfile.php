<?php
namespace Odl\AuthBundle\Documents;

/**
 * @mongodb:EmbeddedDocument
 */
class FacebookProfile extends Profile
{
	public function __construct(array $facebookUserInfo = array())
	{
		$this->setFacebookUserInfo($facebookUserInfo);
	}

	/**
	 * @mongodb:String
	 * @assert:NotBlank
	 */
	protected $facebookUserId;

	/**
	 * @mongodb:Hash
	 * @var array
	 */
	protected $facebookUserInfo;

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
