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
    public function loadUserByUsername($username)
    {
        // do whatever you need to retrieve the user from the database
        // code below is the implementation used when using the property setting
        $user = $this->getRepository()->findOneBy(array('username' => $username));
        if (!$user)
        {
        	throw  new UsernameNotFoundException();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function loadUser(UserInterface $user)
    {
		$query = array('facebookAuth.$id' => $facebookUserId);
        $query = array('username' => $user->getUserName());

    	return $this->getRepository()->findOneBy($query);
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
