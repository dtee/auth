<?php
namespace Odl\AuthBundle\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\EmbeddedDocument
 */
class TwitterProfile
	extends ExternalProfile
{
    protected $followers;
}

