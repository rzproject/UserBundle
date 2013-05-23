<?php

/*
 * This file is part of the RzUserBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\UserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
