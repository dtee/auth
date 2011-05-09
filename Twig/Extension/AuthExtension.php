<?php
namespace Odl\AuthBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\Container;
use Assetic\Asset\AssetInterface;
use Assetic\Asset\AssetCollection;

class AuthExtension
	extends \Twig_Extension
{
	private $container;
	private $debug;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->debug = $container->get('kernel')->isDebug();
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
        	'jumbo' => new \Twig_Filter_Method(
        		$this, 'getJumbo', array('is_safe' => array('html'))),
        );
    }

    public function getJumbo($name)
    {
    	$managerName = 'asset.asset_manager';
    	$assetManager = $this->container->get($managerName);
    	if ($assetManager->has($name))
    	{
    		$asset = $assetManager->get($name);
    		return $this->getAssetHTML($asset);
    	}
    	else
    	{
    		throw new \Exception("Asset '{$name}' not found in {$managerName}");
    	}
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'asset';
    }
}
