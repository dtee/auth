<?php
namespace Odl\AuthBundle\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\MappedSuperclass
 */
class ExternalProfile
    extends Profile
{
    /**
     * @ODM\Hash
     */
    protected $userInfo;

    /**
     * @ODM\Hash
     *
     * Remember oauth information
     */
    protected $oauth;

    /**
     * @ODM\String
     * @Assert\NotBlank
     */
    protected $userId;

    /**
     * @return the $userInfo
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * @return the $userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param field_type $userInfo
     */
    public function setUserInfo($userInfo)
    {
        $this->userInfo = $userInfo;
    }

    /**
     * @param field_type $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}

