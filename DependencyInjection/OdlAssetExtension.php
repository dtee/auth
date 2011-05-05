<?php
namespace Odl\AuthBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class OdlAuthExtension
	extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
    	v('in auth extension');

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Lets set up
    }

    public function getAlias()
    {
        return 'odl_asset';
    }
}
