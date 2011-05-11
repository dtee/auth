<?php
namespace Odl\AuthBundle\Documents;

/**
 * @mongodb:Document(db="user", collection="user_auth")
 * @mongodb:HasLifecycleCallbacks
 */
class UserAuth
	implements UserInterface
{
	/**
	 * @mongodb:id
	 */
	protected $id;


	/**
	 * @mongodb:Field(type="string")
	 * @mongodb:UniqueIndex()
	 *
	 * @assert:NotBlank()
	 * @assert:Email()
	 * @assert:MinLength(6)
	 * @assert:MaxLength(50)
	 * @assertAuth:UniqueUsernamePassword()
	 */
	protected $email;

	/**
	 * @mongodb:Field(type="string")
	 * @assert:NotBlank()
	 * @assert:MinLength(6)
	 * @assert:MaxLength(20)
	 */
	protected $password;

	/**
	 * @mongodb:Field(type="string")
	 * @assert:NotBlank
	 */
	protected $salt;

	/**
	 * @mongodb:Field(type="hash")
	 * @assert:NotBlank()
	 */
	protected $roles;

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
	 * @mongodb:EmbedOne(targetDocument="Profile")
	 */
	protected $profile;

	/**
	 * @mongodb:EmbedOne(targetDocument="FacebookAuth")
	 * @mongodb:Index
	 */
	protected $facebookAuth;

	public function __construct()
    {
		$this->roles = array('ROLE_USER');
    }

	/**
	 * @return $facebookAuth
	 */
	public function getFacebookAuth()
	{
		return $this->facebookAuth;
	}

	public function getFacebookUserId()
	{
		if ($this->facebookAuth)
		{
			return $this->facebookAuth->getFacebookUserId();
		}

		return null;
	}

	public function getProfileImage()
	{
		if ($fbUid = $this->getFacebookUserId())
		{
			return "http://graph.facebook.com/{$fbUid}/picture";
		}

		// Default null image
		return 'http://static.ak.fbcdn.net/rsrc.php/v1/yi/r/odA9sNLrE86.jpg';
	}

	/**
	 * @param $facebookAuth
	 */
	public function setFacebookAuth($facebookAuth)
	{
		$this->facebookAuth = $facebookAuth;
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
	 * @param $roles
	 */
	public function setRoles($roles)
	{
		$this->roles = $roles;
	}

	/**
	 * @return $roles
	 */
	public function getRoles()
	{
		return $this->roles;
	}

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
    	if ($fbAuth = $this->getFacebookAuth())
    	{
    		if ($profile = $fbAuth->getProfile())
    		{
    			return $profile->getFirstName() . ' ' . $profile->getLastName();
    		}
    	}

    	return $this->getId();
    }

    /**
     * @mongodb:PostLoad
     */
	public function setReferenceOnPostLoad()
    {
    	// Set references
    }
}
