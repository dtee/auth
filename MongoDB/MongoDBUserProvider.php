<?php
namespace Odl\AuthBundle\MongoDB;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Acme\FacebookBundle\Firewall\FacebookUserInterface;

class MongoDBUserProvider
	implements UserProviderInterface
{
	private $dm;
	private $documentName;
	private $repository;

    public function __construct(DocumentManager $dm, $documentName)
    {
    	$this->dm = $dm;
    	$this->documentName = $documentName;
    	$this->repository = $this->dm->getRepository($documentName);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username) {
        $user = $this->repository->findOneBy(
        	array('email' => $username));

        if (!$user) {
        	return null;
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUser(UserInterface $user)
    {
    	return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
    	return ($class instanceof FacebookUserInterface
    			|| $class instanceof UserInterface);
    }
}
