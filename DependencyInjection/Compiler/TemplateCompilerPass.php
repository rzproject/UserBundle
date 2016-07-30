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

class TemplateCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('rz_core.template_loader');
        $templates = $container->getParameter('rz_user.templates');
        $bunldeTemplates = [];
        foreach($templates as $key => $templates) {
            if(is_array($templates)) {
                foreach ($templates as $id=>$template) {
                    $bunldeTemplates[sprintf('rz_user.template.%s.%s', $key, $id)] = $template;
                }
            } else {
                $bunldeTemplates[sprintf('rz_user.template.%s', $key)] = $templates;
            }
        }
        $definition->addMethodCall('setTemplates', array($bunldeTemplates));
    }
}
