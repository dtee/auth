<?php
namespace Odl\AuthBundle\Documents;

/**
 * @mongodb:EmbeddedDocument
 */
class Profile
{
	/**
	 * @mongodb:String
	 * @assert:NotBlank
	 * @assert:MinLength(2)
	 * @assert:MaxLength(25)
	 */
	protected $firstName;

	/**
	 * @mongodb:String
	 * @assert:NotBlank
	 * @assert:MinLength(2)
	 * @assert:MaxLength(25)
	 */
	protected $lastName;

	/**
	 * @return the $firstName
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * @return the $lastName
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * @param $firstName
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	/**
	 * @param $lastName
	 */
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}
}