<?php
namespace Odl\AuthBundle\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Document\User;

/**
 * @ODM\Document(db="user", collection="user_auth")
 * @ODM\Index(keys={"facebookProfile.facebookUserId"="asc"})
 */
class UserAuth
	extends User
{
	/**
	 * @ODM\Id
	 */
	protected $id;

	/**
	 * @ODM\Field(type="string")
	 * @ODM\UniqueIndex()
	 *
	 * @Assert\MinLength(6)
	 * @Assert\MaxLength(50)
	 */
	protected $username;

	/**
	 * @ODM\Field(type="string")
	 */
    protected $usernameCanonical;

	/**
	 * @ODM\Field(type="string")
	 * @ODM\UniqueIndex()
	 *
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 * @Assert\MinLength(6)
	 * @Assert\MaxLength(50)
	 */
	protected $email;

	/**
	 * @ODM\Field(type="boolean")
	 */
	protected $enabled;

	/**
	 * @ODM\Field(type="string")
	 */
    protected $emailCanonical;

	/**
	 * @ODM\Field(type="string")
	 */
	protected $password;

	/**
	 * @ODM\NotSaved
	 *
	 * @Assert\NotBlank()
	 * @Assert\MinLength(6)
	 * @Assert\MaxLength(20)
	 */
	protected $plainPassword;

	/**
	 * @ODM\Field(type="string")
	 *
	 * @Assert\NotBlank
	 */
	protected $salt;

	/**
	 * @ODM\Field(type="collection")
	 * @Assert\NotBlank()
	 */
	protected $roles;

	/**
	 * @ODM\Field(type="date")
	 */
	protected $expiresAt;

	/**
	 * @ODM\Field(type="boolean")
	 */
	protected $expired;

	/**
	 * @ODM\Field(type="date")
	 * @gedmo:Timestampable(on="create")
	 */
	protected $createdAt;

	/**
	 * @ODM\Field(type="date")
	 * @gedmo:Timestampable(on="update")
	 */
	protected $updatedAt;

	/**
	 * @ODM\Field(type="boolean")
	 */
    protected $credentialsExpired;

	/**
	 * @ODM\Field(type="date")
	 */
    protected $credentialsExpireAt;

	/**
	 * @ODM\EmbedOne(targetDocument="Profile")
	 * @ODM\Index
	 */
	protected $profile;

	/**
	 * @ODM\Field(type="string")
	 */
	protected $algorithm;

	/**
	 * @ODM\EmbedOne(targetDocument="FacebookProfile")
	 * @ODM\Index
	 */
	protected $facebookProfile;

	/**
	 * @ODM\Field(type="collection")
	 */
    protected $groups;

    public function __construct() {
    	parent::__construct();
    	$this->enabled = true;
    	$this->algorithm = 'sha512';
    	$this->groups = array();
    	$this->roles = array();
    }

    public function setEmail($email){
    	parent::setEmail($email);
    	parent::setUsername($email);
    }

	public function getProfileImage()
	{
		if ($this->facebookProfile)
		{
			return "http://graph.facebook.com/{$this->facebookProfile->getFacebookUserId()}/picture";
		}

		// Default null image
		return 'http://static.ak.fbcdn.net/rsrc.php/v1/yi/r/odA9sNLrE86.jpg';
	}

	/**
	 * @return $createTime
	 */
	public function getCreateTime()
	{
		return $this->createTime;
	}

	/**
	 * @return $id
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return $profile
	 */
	public function getProfile()
	{
		return $this->profile;
	}

	/**
	 * @param $profile
	 */
	public function setProfile($profile)
	{
		$this->profile = $profile;
	}

	/**
	 * @return the $facebookProfile
	 */
	public function getFacebookProfile() {
		return $this->facebookProfile;
	}

	/**
	 * @param field_type $facebookProfile
	 */
	public function setFacebookProfile($facebookProfile) {
		$this->facebookProfile = $facebookProfile;
	}

	/**
	 * @param $createTime
	 */
	public function setCreateTime($createTime)
	{
		$this->createTime = $createTime;
	}

	/**
	 * @return $updateTime
	 */
	public function getUpdateTime()
	{
		return $this->updateTime;
	}

	/**
	 * @param $updateTime
	 */
	public function setUpdateTime($updateTime)
	{
		$this->updateTime = $updateTime;
	}

	public function getFirstName() {
		if ($this->facebookProfile)
		{
			$profile = $this->facebookProfile;
		}
		else {
    		$profile = $this->getProfile();
		}

		return $profile->getFirstName();
	}

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
		if ($this->facebookProfile)
		{
			$profile = $this->facebookProfile;
		}
		else {
    		$profile = $this->getProfile();
		}

 		if ($profile) {
			return $profile->getFirstName() . ' ' . $profile->getLastName();
		}

    	return $this->getId();
    }
}
