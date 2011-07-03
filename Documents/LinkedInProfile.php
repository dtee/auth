<?php
namespace Odl\AuthBundle\Documents;

use Odl\AuthBundle\Documents\ExternalProfile;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\EmbeddedDocument
 */
class LinkedInProfile
    extends ExternalProfile
{
    /**
     * @ODM\Hash
     */
    protected $connections;

    public function __construct(array $info) {
        $this->userId = $info['id'];
        $this->userInfo = $info;
        $this->firstName = $info['firstName'];
        $this->lastName = $info['lastName'];
    }

	/**
     * @return the $connections
     */
    public function getConnections()
    {
        return $this->connections;
    }

	/**
     * @param field_type $connections
     */
    public function setConnections($connections)
    {
        $this->connections = $connections;
    }

}