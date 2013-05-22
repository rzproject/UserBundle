<?php

namespace Rz\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Rz\UserBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;

class RzUserBundle extends Bundle
{
    public function getParent()
    {
        return 'SonataUserBundle';
    }

    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }
}

