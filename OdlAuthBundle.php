<?php
namespace Odl\AuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Odl\AuthBundle\DependencyInjection\Compiler\AddValidatorNamespaceAliasPass;

class OdlAuthBundle
	extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // Add custom validator
        $container->addCompilerPass(
        	new AddValidatorNamespaceAliasPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
    }
}
