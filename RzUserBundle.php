<?php

/*
 * This file is part of the RzUserBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Rz\UserBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;
use Rz\UserBundle\DependencyInjection\Compiler\TemplateCompilerPass;

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
        $container->addCompilerPass(new TemplateCompilerPass());
    }
}
