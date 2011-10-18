<?php
namespace Odl\AuthBundle\Model;

use FOS\FacebookBundle\Facebook\FacebookSessionPersistence;

use Odl\AuthBundle\Documents\FacebookProfile;
use Odl\AuthBundle\Documents\UserAuth;
use Doctrine\ODM\MongoDB\DocumentManager;
use Facebook;
use Exception;
use BaseFacebook;

class FacebookUserProvider
    implements UserProviderInterface
{
    private $dm;
    private $facebook;
    private $userRepository;

    public function __construct(DocumentManager $dm, BaseFacebook $facebook)
    {
        $this->dm = $dm;
        $this->facebook = $facebook;

        $this->userRepository = $this->dm->getRepository('Odl\AuthBundle\Documents\UserAuth');
    }

    public function supportsClass($class)
    {
        return true;
    }

    public function findUserByFbId($facebookUserId) {
        $query = array(
            'facebookProfile.facebookUserId' => $facebookUserId
        );

        if (isset($this->facebookUserIdCache[$facebookUserId]))
        {
            return $this->facebookUserIdCache[$facebookUserId];
        }

        $userAuth = $this->userRepository->findOneBy($query);
        if ($userAuth) {
            $this->facebookUserIdCache[$facebookUserId] = $userAuth;
        }

        return $userAuth;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->findUserByFbId($username);
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getFacebookProfile()->getId());
    }
}