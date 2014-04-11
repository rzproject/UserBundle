<?php

/*
 * This file is part of the RzUserBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\UserBundle\DependencyInjection;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RzUserExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('fields.xml');
        $loader->load('listeners.xml');
        $loader->load('admin_orm.xml');
        $loader->load('profile.xml');
        $loader->load('change_password.xml');
        # addition of service on compiler pass
        //$loader->load('form.xml');

        $config = $this->addDefaults($config);
        $this->configureAdminClass($config, $container);
        $this->configureClass($config, $container);

        $this->configureTranslationDomain($config, $container);
        $this->configureController($config, $container);
        $this->configureRzTemplates($config, $container);
        $this->configureProfileBlockService($config, $container);
        $this->loadChangePassword($config, $container);

        // add custom form widgets
        $container->setParameter('twig.form.resources', array_merge(
                                                          $container->getParameter('twig.form.resources'),
                                                          array('RzUserBundle:Form:form_admin_fields.html.twig')
                                                      ));
    }

    /**
     * @param array $config
     *
     * @return array
     */
    public function addDefaults(array $config)
    {
        if ('orm' === $config['manager_type']) {
            $modelType = 'Entity';
        } elseif ('mongodb' === $config['manager_type']) {
            $modelType = 'Document';
        }

        $defaultConfig['class']['user']  = sprintf('Application\\Sonata\\UserBundle\\%s\\User', $modelType);
        $defaultConfig['class']['group'] = sprintf('Application\\Sonata\\UserBundle\\%s\\Group', $modelType);

        return array_replace_recursive($defaultConfig, $config);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureClass($config, ContainerBuilder $container)
    {
        if ('orm' === $config['manager_type']) {
            $modelType = 'entity';
        } elseif ('mongodb' === $config['manager_type']) {
            $modelType = 'document';
        }

        $container->setParameter(sprintf('sonata.user.admin.user.%s', $modelType), $config['class']['user']);
        $container->setParameter(sprintf('sonata.user.admin.group.%s', $modelType), $config['class']['group']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureAdminClass($config, ContainerBuilder $container)
    {
        $container->setParameter('sonata.user.admin.user.class', $config['admin']['user']['class']);
        $container->setParameter('sonata.user.admin.group.class', $config['admin']['group']['class']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureTranslationDomain($config, ContainerBuilder $container)
    {
        $container->setParameter('sonata.user.admin.user.translation_domain', $config['admin']['user']['translation']);
        $container->setParameter('sonata.user.admin.group.translation_domain', $config['admin']['group']['translation']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureController($config, ContainerBuilder $container)
    {
        $container->setParameter('sonata.user.admin.user.controller', $config['admin']['user']['controller']);
        $container->setParameter('sonata.user.admin.group.controller', $config['admin']['group']['controller']);
    }

    /**
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function configureRzTemplates($config, ContainerBuilder $container)
    {
        $container->setParameter('rz_user.configuration.user.templates', $config['admin']['user']['templates']);
        $container->setParameter('rz_user.configuration.group.templates', $config['admin']['group']['templates']);
        $container->setParameter('rz_user.templates', $config['templates']);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function configureProfile(array $config, ContainerBuilder $container)
    {
        $container->setParameter('sonata.user.profile.form.type', $config['profile']['form']['type']);
        $container->setParameter('sonata.user.profile.form.name', $config['profile']['form']['name']);
        $container->setParameter('sonata.user.profile.form.validation_groups', $config['profile']['form']['validation_groups']);

        $container->setParameter('sonata.user.register.confirm.redirect_route', $config['profile']['register']['confirm']['redirect']['route']);
        $container->setParameter('sonata.user.register.confirm.redirect_route_params', $config['profile']['register']['confirm']['redirect']['route_parameters']);

        $container->setParameter('sonata.user.configuration.profile_blocks', $config['profile']['dashboard']['blocks']);

        $container->setAlias('sonata.user.profile.form.handler', $config['profile']['form']['handler']);

//        $container->setParameter('sonata.user.update_password.form.type', $config['profile']['update_password']['type']);
//        $container->setParameter('sonata.user.update_password.form.name', $config['profile']['update_password']['name']);
//        $container->setParameter('sonata.user.update_password.form.validation_groups', $config['profile']['update_password']['validation_groups']);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function configureProfileBlockService(array $config, ContainerBuilder $container)
    {
        $container->setParameter('rz.user.user.block.menu.class', $config['profile']['blocks_service']['class']);
    }


    public function loadChangePassword(array $config, ContainerBuilder $container)
    {
        $container->setParameter('rz_user.change_password.form.type', $config['profile']['change_password']['type']);
        $container->setParameter('rz_user.change_password.form.name', $config['profile']['change_password']['name']);
        $container->setParameter('rz_user.change_password.form.validation_groups', $config['profile']['change_password']['validation_groups']);
        $container->setAlias('rz_user.change_password.form.handler', $config['profile']['change_password']['handler']);
    }
}
