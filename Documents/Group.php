<?php
namespace Odl\AuthBundle\Documents;

use FOS\UserBundle\Model\Group as BaseGroup;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Validator as AssertUser;

use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Document\User;

/**
 * @ODM\Document(db="user", collection="group")
 */
class Group
    extends BaseGroup
{
    /**
     * @ODM\Id
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     */
    protected $name;

    /**
     * @ODM\Field(type="hash")
     * @Assert\NotBlank()
     */
    protected $roles;
}