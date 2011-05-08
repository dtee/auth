<?php
namespace Odl\AuthBundle\Documents;
use Symfony\Component\Security\Core\User\UserInterface;

/** 
 * @mongodb:EmbeddedDocument
 */
class UsernamePasswordAuth
	implements UserInterface
{
	/**
	 * @mongodb:Field(type="string")
	 * @assert:NotBlank
	 * @assert:MinLength(5)
	 * @assert:MaxLength(20)
	 */
	protected $password;

	/**
	 * @mongodb:id(strategy="NONE")
	 * @assert:NotBlank
	 * @assert:Email
	 * @assert:MinLength(5)
	 * @assert:MaxLength(50)
	 */
	protected $email;

	/**
	 * @mongodb:Field(type="string")
	 * @assert:NotBlank
	 */
	protected $salt;

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
	 * @return the $password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	/**
	 * @return the $email
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @return the $email
	 */
	public function getUsername()
	{
		return $this->email;
	}

	/**
	 * @param $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return the $salt
	 */
	public function getSalt()
	{
		return $this->salt;
	}

	/**
	 * @param $salt
	 */
	public function setSalt($salt)
	{
		$this->salt = $salt;
	}

	public function eraseCredentials()
    {
    	return $this->password = '';
    }

    public function equals(UserInterface $user)
    {
    	if ($this->email == $user->getUsername())
    	{
    		if ($this->password == $user->getPassword())
    		{
    			return true;
    		}
    	}

    	return false;
    }

	/**
	 * @return the $roles
	 */
	public function getRoles()
	{
		return $this->roles;
	}
}