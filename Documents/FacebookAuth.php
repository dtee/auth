<?php
namespace Odl\AuthBundle\Documents;

use Odl\AuthBundle\Firewall\FacebookUserInterface;

/** 
 * @mongodb:EmbeddedDocument
 */
class FacebookAuth
	implements FacebookUserInterface
{
	public function __construct(array $facebookUserInfo = array())
	{
		$facebookProfile = new FacebookProfile($facebookUserInfo);
		$this->setProfile($facebookProfile);

		if (isset($facebookUserInfo['id']))
		{
			$this->setFacebookUserId($facebookUserInfo['id']);
		}
	}

	/**
	 * @mongodb:EmbedOne(targetDocument="FacebookProfile")
	 */
	protected $profile;

	/**
	 * @mongodb:id(strategy="NONE")
	 */
	protected $facebookUserId;

	/**
	 * @mongodb:Field(type="date")
	 * @gedmo:Timestampable(on="create")
	 */
	protected $createTime;

	/**
	 * @mongodb:Field(type="date")
	 * @gedmo:Timestampable(on="update")
	 */
	protected $updateTime;

	/**
	 * @return the $profile
	 */
	public function getProfile()
	{
		return $this->profile;
	}

	/**
	 * @param field_type $profile
	 */
	public function setProfile($profile)
	{
		$this->profile = $profile;
	}

	/**
	 * @return the $createTime
	 */
	public function getCreateTime()
	{
		return $this->createTime;
	}

	/**
	 * @return the $updateTime
	 */
	public function getUpdateTime()
	{
		return $this->updateTime;
	}

	/**
	 * @param field_type $createTime
	 */
	public function setCreateTime($createTime)
	{
		$this->createTime = $createTime;
	}

	/**
	 * @param field_type $updateTime
	 */
	public function setUpdateTime($updateTime)
	{
		$this->updateTime = $updateTime;
	}

	/**
	 * @return $facebookUserId
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
