<?php
namespace Odl\AuthBundle\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\EmbeddedDocument
 */
class Profile
{
	/**
	 * @ODM\String
	 *
	 * @Assert\NotBlank
	 * @Assert\MinLength(2)
	 * @Assert\MaxLength(25)
	 */
	protected $firstName;

	/**
	 * @ODM\String
	 *
	 * @Assert\NotBlank
	 * @Assert\MinLength(2)
	 * @Assert\MaxLength(25)
	 */
	protected $lastName;

	/**
	 * @ODM\ReferenceOne(targetDocument="Image")
	 */
	protected $image;

	/**
     * @return the $image
     */
    public function getImage()
    {
        return $this->image;
    }

	/**
     * @param field_type $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

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

	public function __toString() {
	    return $this->firstName . ' ' . $this->lastName;
	}
}