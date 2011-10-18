<?php
namespace Odl\AuthBundle\Model;

use Odl\AuthBundle\Documents\FacebookProfile;
use Odl\AuthBundle\Documents\UserAuth;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;
use BaseFacebook;

class FacebookUserManager
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

    public function updateFriends(UserAuth $userAuth)
    {
        $facebookProfile = $userAuth->getFacebookProfile();

        if (!$facebookProfile)
            return;

        $facebookUserId = $facebookProfile->getFacebookUserId();

        try
        {
            $friends = $this->facebook->api("/{$facebookUserId}/friends");
            $friends = $friends['data'];
            $facebookProfile->setFriends($friends);
        }
        catch (\Exception $ex)
        {
        }

        // Lets get friends also
        $userAuth->setUsername('fb_' . $facebookProfile->getFacebookUserId());
        $userAuth->setFacebookProfile($facebookProfile);

        $this->updateUser($userAuth);
    }

    /**
     * @param unknown_type $facebookUserId
     *
     * @return FacebookProfile
     */
    public function getFacebookProfile($facebookUserId)
    {
        $request = array(
            'info' => array(
                'method' => 'GET',
                'relative_url' => "{$facebookUserId}"
            ),
            'friends' => array(
                'method' => 'GET',
                'relative_url' => "{$facebookUserId}/friends?limit=4000"
            )
        );

        $fbRequest = array(
            'batch' => array_values($request),
            'access_token' => $this->facebook->getAccessToken()
        );

        $fbResponse = $this->facebook->api('/?batch', 'POST', $fbRequest);

        $profile = new FacebookProfile();
        foreach (array_keys($request) as $index => $key) {
            if ($fbResponse[$index]['code'] != 200) {
                // Log error and continue
                continue;
            }

            $data = json_decode($fbResponse[$index]['body'], true);
            if ($key == 'info') {
                $profile->setFacebookUserInfo($data);
            }
            else if ($key == 'friends') {
                $data = isset($data['data']) ? $data['data'] : array();
                $profile->setFriends($data);
            }
        }

        return $profile;

        return null;
    }

    protected $facebookUserIdCache = array();

    public function createOrGetUser($facebookUserId)
    {
        // See if the user exists
        $query = array(
            'facebookProfile.facebookUserId' => $facebookUserId
        );

        if (isset($this->facebookUserIdCache[$facebookUserId]))
        {
            return $this->facebookUserIdCache[$facebookUserId];
        }

        $userAuth = $this->userRepository->findOneBy($query);

        if (!$userAuth)
        {
            // Lets create a new user
            $userAuth = new UserAuth();
            $userAuth->setFacebookProfile($this->getFacebookProfile($facebookUserId));
            $userAuth->setUsername("fb_{$facebookUserId}");
            $this->dm->persist($userAuth);
            $this->dm->flush();
        }

        $this->facebookUserIdCache[$facebookUserId] = $userAuth;
        $this->dm->detach($userAuth);
        return $userAuth;
    }

    public function getUserAuth($facebookUserId) {
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

    public function getFacebookUsersById($facebookUserIds)
    {
        $query = array(
            'facebookProfile.facebookUserId' => array(
                '$in' => $facebookUserIds
            )
        );

        $userAuth = $this->userRepository->findOneBy($query);
    }
}