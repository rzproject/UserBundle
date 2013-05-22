<?php

namespace Rz\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        //$rz_definition = $container->getDefinition('rz_user.admin.user');

        //override User Admin
        $definition = $container->getDefinition('sonata.user.admin.user');
//        $definition->addMethodCall('setTemplates', array());

        $definedTemplates = array_merge($container->getParameter('sonata.admin.configuration.templates'),
                                        $container->getParameter('rz_user.configuration.user.templates'));

        $definition->addMethodCall('setTemplates', array($definedTemplates));
//        $definition->setClass($container->getParameter('rz_user.admin.user.class'));

//
//        $definition = $container->getDefinition('sonata.user.admin.group');
//        $definition->setClass($container->getParameter('rz_user.admin.group.class'));
    }
}
