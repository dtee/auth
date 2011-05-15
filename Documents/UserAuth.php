<?php
namespace Odl\AuthBundle\Documents;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Document\User;

/**
 * @mongodb:Document(db="user", collection="user_auth")
 */
class UserAuth
	extends User
{
	/**
	 * @mongodb:Id
	 */
	protected $id;

	/**
	 * @mongodb:Field(type="string")
	 * @mongodb:UniqueIndex()
	 *
	 * @assert:MinLength(6)
	 * @assert:MaxLength(50)
	 */
	protected $username;

	/**
	 * @mongodb:Field(type="string")
	 */
    protected $usernameCanonical;

	/**
	 * @mongodb:Field(type="string")
	 * @mongodb:UniqueIndex()
	 *
	 * @assert:NotBlank()
	 * @assert:Email()
	 * @assert:MinLength(6)
	 * @assert:MaxLength(50)
	 */
	protected $email;
	
	/**
	 * @mongodb:Field(type="boolean")
	 */
	protected $enabled;

	/**
	 * @mongodb:Field(type="string")
	 */
    protected $emailCanonical;

	/**
	 * @mongodb:Field(type="string")
	 */
	protected $password;

	/**
	 * @mongodb:NotSaved
	 *
	 * @assert:NotBlank()
	 * @assert:MinLength(6)
	 * @assert:MaxLength(20)
	 */
	protected $plainPassword;

	/**
	 * @mongodb:Field(type="string")
	 *
	 * @assert:NotBlank
	 */
	protected $salt;

	/**
	 * @mongodb:Field(type="collection")
	 * @assert:NotBlank()
	 */
	protected $roles;

	/**
	 * @mongodb:Field(type="date")
	 */
	protected $expiresAt;

	/**
	 * @mongodb:Field(type="boolean")
	 */
	protected $expired;

	/**
	 * @mongodb:Field(type="date")
	 * @gedmo:Timestampable(on="create")
	 */
	protected $createdAt;

	/**
	 * @mongodb:Field(type="date")
	 * @gedmo:Timestampable(on="update")
	 */
	protected $updatedAt;

	/**
	 * @mongodb:Field(type="boolean")
	 */
    protected $credentialsExpired;

	/**
	 * @mongodb:Field(type="date")
	 */
    protected $credentialsExpireAt;

	/**
	 * @mongodb:EmbedOne(targetDocument="Profile")
	 * @mongodb:Index
	 */
	protected $profile;
	
	/**
	 * @mongodb:Field(type="string")
	 */
	protected $algorithm; 

	/**
	 * @mongodb:EmbedOne(targetDocument="FacebookProfile")
	 * @mongodb:Index
	 */
	protected $facebookProfile;

	/**
	 * @mongodb:Field(type="collection")
	 */
    protected $groups;

    public function __construct() {
    	parent::__construct();
    	$this->enabled = true;
    	$this->algorithm = 'sha512';
    }
    
    public function setEmail($email){
    	parent::setEmail($email);
    	parent::setUsername($email);
    }

	public function getProfileImage()
	{
		if ($this->facebookProfile && $this->facebookProfile->getFacebookUserId())
		{
			return "http://graph.facebook.com/{$fbUid}/picture";
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

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
    	$profile = $this->getProfile();
    	
 		if ($profile) {
			return $profile->getFirstName() . ' ' . $profile->getLastName();
		}
		
    	return $this->getId();
    }
}
