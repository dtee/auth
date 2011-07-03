<?php
namespace Odl\AuthBundle\Documents;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Validator as AssertUser;

use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Document\User;

/**
 * @ODM\Document(db="user", collection="user_auth")
 * @ODM\Index(keys={"facebookProfile.facebookUserId"="asc"})
 *
 * @AssertUser\Unique(
 *     message="email already exists",
 *     property="email",
 *     groups={"registration"})
 */
class UserAuth extends User
{
    /**
     * @ODM\Id
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     * @ODM\UniqueIndex()
     *
     * @Assert\MinLength(6)
     * @Assert\MaxLength(50)
     */
    protected $username;

    /**
     * @ODM\Field(type="string")
     */
    protected $usernameCanonical;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * @ODM\Field(type="boolean")
     */
    protected $enabled;

    /**
     * @ODM\Field(type="string")
     */
    protected $emailCanonical;

    /**
     * @ODM\Field(type="string")
     */
    protected $password;

    /**
     * @ODM\NotSaved
     *
     * @Assert\NotBlank(groups={"registration","reset"})
     * @Assert\MinLength(limit="6", groups={"registration","reset"})
     * @Assert\MaxLength(limit="20", groups={"registration","reset"})
     */
    protected $plainPassword;

    /**
     * @ODM\Field(type="string")
     */
    protected $confirmationToken;

    /**
     * @ODM\Field(type="date")
     */
    protected $passwordRequestedAt;

    /**
     * @ODM\Field(type="collection")
     */
    protected $groups;

    /**
     * @ODM\Field(type="string")
     *
     * @Assert\NotBlank
     */
    protected $salt;

    /**
     * @ODM\Field(type="collection")
     * @Assert\NotBlank()
     */
    protected $roles;

    /**
     * @ODM\Field(type="date")
     */
    protected $expiresAt;

    /**
     * @ODM\Field(type="boolean")
     */
    protected $expired;

    /**
     * @ODM\Field(type="date")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @ODM\Field(type="date")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * @ODM\Field(type="boolean")
     */
    protected $credentialsExpired;

    /**
     * @ODM\Field(type="date")
     */
    protected $credentialsExpireAt;

    /**
     * @ODM\EmbedOne(targetDocument="Profile")
     * @ODM\Index
     */
    protected $profile;

    /**
     * @ODM\Field(type="string")
     */
    protected $algorithm;

    /**
     * @ODM\EmbedOne(targetDocument="FacebookProfile")
     * @ODM\Index
     */
    protected $facebookProfile;

    /**
     * @ODM\EmbedOne(targetDocument="LinkedInProfile")
     * @ODM\Index
     */
    protected $linkedInProfile;

    /**
     * @ODM\EmbedOne(targetDocument="TwitterProfile")
     * @ODM\Index
     */
    protected $twitterProfile;

	/**
     * @return the $linkedInProfile
     */
    public function getLinkedInProfile()
    {
        return $this->linkedInProfile;
    }

	/**
     * @return the $twitterProfile
     */
    public function getTwitterProfile()
    {
        return $this->twitterProfile;
    }

	/**
     * @param field_type $linkedInProfile
     */
    public function setLinkedInProfile($linkedInProfile)
    {
        $this->linkedInProfile = $linkedInProfile;
    }

	/**
     * @param field_type $twitterProfile
     */
    public function setTwitterProfile($twitterProfile)
    {
        $this->twitterProfile = $twitterProfile;
    }

	public function __construct()
    {
        parent::__construct();
        $this->enabled = true;
        $this->algorithm = 'sha512';
        $this->groups = array();
        $this->roles = array();
    }

    public function setEmail($email)
    {
        parent::setEmail($email);
        parent::setUsername($email);
    }

    public function getProfileImage()
    {
        if ($this->facebookProfile)
        {
            return "http://graph.facebook.com/{$this->facebookProfile->getFacebookUserId()}/picture";
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
    public function getFacebookProfile()
    {
        return $this->facebookProfile;
    }

    /**
     * @param field_type $facebookProfile
     */
    public function setFacebookProfile($facebookProfile)
    {
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

    public function getLastName() {
        if ($this->facebookProfile)
        {
            $profile = $this->facebookProfile;
        }
        else
        {
            $profile = $this->getProfile();
        }

        if ($profile) {
            return $profile->getLastName();
        }
    }

    public function getFirstName()
    {
        if ($this->facebookProfile)
        {
            $profile = $this->facebookProfile;
        }
        else
        {
            $profile = $this->getProfile();
        }

        if ($profile) {
            return $profile->getFirstName();
        }
    }

    /**
     * Wrapper function that sets firstname and lastname
     * 	from one single string
     *
     * @param unknown_type $name
     */
    public function setFullname($name)
    {
        // Junc Lu Pikard
        $parts = explode(' ', $name);

        if (count($parts) > 0) {
            $firstname = $parts[0];
            unset($parts[0]);
            $lastname = implode(' ', $parts);

            if (!$this->profile) {
                $this->profile = new Profile();
            }

            $this->profile->setFirstName($firstname);
            $this->profile->setLastName($lastname);
        }
    }

    /**
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\MinLength(limit=5, groups={"registration"})
     */
    public function getFullname() {
        $firstname = $this->getFirstName();
        $lastname = $this->getLastName();

        if ($firstname && $lastname) {
            return $firstname . ' ' . $lastname;
        }
    }

    public function generateConfirmationToken()
    {
        $this->confirmationToken = $this->generateToken();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        if ($this->facebookProfile)
        {
            $profile = $this->facebookProfile;
        }
        else
        {
            $profile = $this->profile;
        }

        if ($profile)
        {
            return $profile->getFirstName() . ' ' . $profile->getLastName();
        }

        return $this->getId();
    }
}
