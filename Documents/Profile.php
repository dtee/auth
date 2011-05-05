<?php
namespace Odl\AuthBundle\Documents;

/**
 * @mongodb:EmbeddedDocument
 * @mongodb:InheritanceType("SINGLE_COLLECTION")
 * @mongodb:DiscriminatorField(fieldName="type")
 * @mongodb:DiscriminatorMap(
 * 	{	"Profile"="Acme\DemoBundle\Documents\Profile",
 * 		"FacebookProfile"="Acme\DemoBundle\Documents\FacebookProfile"})
 */
class Profile
{
	/**
	 * @mongodb:String
	 * @assert:NotBlank
	 * @assert:MinLength(5)
	 * @assert:MaxLength(25)
	 */
	protected $firstName;

	/**
	 * @mongodb:String
	 * @assert:NotBlank
	 * @assert:MinLength(5)
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