<?php

namespace Rz\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Rz\UserBundle\DependencyInjection\Compiler\TemplateCompilerPass;

class RzUserBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TemplateCompilerPass());
    }
}