<?php
namespace Odl\AuthBundle\Model;

use Odl\AuthBundle\Documents\LinkedInProfile;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use OAuth;
use HTTP_OAuth_Consumer;
use HTTP_Request2;
use HTTP_OAuth_Consumer_Request;

class LinkedIn
{
    protected $config;
    protected $session;
    protected $sessionKey;
    protected $consumer;
    protected $baseUrl = 'https://api.linkedin.com';
    protected $oAuthInfo;
    protected $request;
    protected $_isVerified = false;

    public function __construct(array $config, Session $session, Request $request = null)
    {
        $this->session = $session;
        $this->config = $config;
        $this->sessionKey = $config['service'];
        $this->request = $request;

        $httpRequest = new HTTP_Request2(null, HTTP_Request2::METHOD_GET, array(
                'ssl_verify_peer' => false,
                'ssl_verify_host' => false
        ));
        $httpRequest->setHeader('Accept-Encoding', '.*');
        $httpRequest->setHeader('x-li-format', 'json');

        $httpConsumerRequest = new HTTP_OAuth_Consumer_Request();
        $httpConsumerRequest->accept($httpRequest);

        $consumer = new HTTP_OAuth_Consumer($config['key'], $config['secret']);
        $consumer->accept($httpConsumerRequest);
        $this->consumer = $consumer;

        if ($requestToken = $session->get($this->sessionKey)) {
            $consumer->setToken($requestToken['token']);
            $consumer->setTokenSecret($requestToken['token_secret']);

            if (isset($requestToken['state']))
            {
                if ($requestToken['state'] == 'verified') {
                    $this->_isVerified = true;
                }
                else if ($verifier = $request->get('oauth_verifier')) {
                    $consumer->getAccessToken($config['access_url'], $verifier);
                    $this->saveSession('verified');
                }
            }
        }
    }

    public function isVerified() {
        return $this->_isVerified;
    }

    public function getConsumer()
    {
        return $this->consumer;
    }

    public function getOAuthInfo() {
        return array(
                'token' => $this->consumer->getToken(),
                'token_secret' => $this->consumer->getTokenSecret()
        );
    }

    public function setOAuthInfo(array $oAuthInfo, $changeSession = false) {
        $this->consumer->setToken($oAuthInfo['token']);
        $this->consumer->setTokenSecret($oAuthInfo['token_secret']);

        // Don't change session
        if ($changeSession) {
            $this->session->set($this->sessionKey, $oAuthInfo);
        }
    }

    public function invalidateSession() {
        $this->session->remove($this->sessionKey);
        $this->session->save();
    }

    /**
     * @param $state new|verified
     */
    protected function saveSession($state) {
        $requestToken = $this->getOAuthInfo();
        $requestToken['state'] = $state;

        $this->session->set($this->sessionKey, $requestToken);
        $this->session->save();
    }

    public function getAccessUrl($callbackUrl = null)
    {
        // Reset both so that it doesn't error out
        $this->consumer->setToken(null);
        $this->consumer->setTokenSecret(null);

        $this->consumer->getRequestToken($this->config['request_url'], $callbackUrl);
        $this->saveSession('new');
        return $this->consumer->getAuthorizeUrl($this->config['authorize_url']);
    }

    public function getProfile($refresh = false) {
        $profile = $this->session->get('linkedin_profile');
        if ($refresh || !$profile) {
            $info = $this->pullProfile();
            $profile = new LinkedInProfile($info);
            $profile->setOauth($this->getOAuthInfo());
            $this->session->set('linkedin_profile', $profile);
        }

        return $profile;
    }

    public function api($url, $params = array(), $type = 'GET') {
        $url = $this->baseUrl . $url;
        $response = $this->consumer->sendRequest($url, $params, $type);

        if ($response->getStatus() == 200) {
            return json_decode($response->getBody(), true);
        }
        else {
            throw new \Exception('Linkedin api error: ' . $response->getBody());
        }
    }

    public function pullProfile(array $fields = null)
    {
        if (count($fields) == 0)
        {
            $fields = array(
                    'id',
                    'public-profile-url',
                    'picture-url',
                    'first-name',
                    'last-name',
                    'summary',
                    'positions',
                    'educations'
            );
        }

        $url = '/v1/people/~:(' . implode(",", $fields) . ')';
        return $this->api($url, array(), 'GET');
    }
}
