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

        $bundles = $container->getParameter('kernel.bundles');

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (isset($bundles['SonataAdminBundle']) && isset($bundles['RzAdminBundle'])) {
            $loader->load(sprintf('admin_%s.xml', $config['manager_type']));
        }

        $config = $this->addDefaults($config);
        $this->configureAdminClass($config, $container);
        $this->configureClass($config, $container);
        $this->configureTranslationDomain($config, $container);
        $this->configureController($config, $container);
        $this->configureRzTemplates($config, $container);
        $this->configureShortcut($container);

        $loader->load('profile.xml');
        $this->configureProfile($config, $container);
        $this->configureProfileBlockService($config, $container);

        $loader->load('registration.xml');
        $this->configureRegistration($config, $container);

        $loader->load('change_password.xml');
        $this->configureChangePassword($config, $container);

        $loader->load('resetting.xml');
        $this->configureResetting($config, $container);

        $loader->load('password_strength.xml');
        $this->configurePasswordStrength($config, $container);

        $loader->load('validators.xml');
        $loader->load('roles.xml');

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
     * @param ContainerBuilder $container
     */
    public function configureShortcut(ContainerBuilder $container)
    {
        $container->setAlias('rz.user.authentication.form', 'fos_user.profile.form');
        $container->setAlias('rz.user.authentication.form_handler', 'fos_user.profile.form.handler');
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function configureProfile(array $config, ContainerBuilder $container)
    {
        $container->setParameter('rz.user.profile.form.type', $config['profile']['form']['type']);
        $container->setParameter('rz.user.profile.form.name', $config['profile']['form']['name']);
        $container->setParameter('rz.user.profile.form.validation_groups', $config['profile']['form']['validation_groups']);

        $container->setAlias('rz.user.profile.form.handler', $config['profile']['form']['handler']);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function configureProfileBlockService(array $config, ContainerBuilder $container)
    {
        $container->setParameter('rz.user.user.block.menu.class', $config['profile']['blocks_service']['class']);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function configureRegistration(array $config, ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['MopaBootstrapBundle'])) {
            $options = array(
                'horizontal_input_wrapper_class' => "col-lg-8",
                'horizontal_label_class' => "col-lg-4 control-label"
            );
        } else {
            $options = array();
        }

        $container->setParameter('rz.user.registration.form.options', $options);

        $container->setParameter('rz.user.registration.form.type', $config['registration']['form']['type']);
        $container->setParameter('rz.user.registration.form.name', $config['registration']['form']['name']);
        $container->setParameter('rz.user.registration.form.validation_groups', $config['registration']['form']['validation_groups']);

        $container->setAlias('rz.user.registration.form.handler', $config['registration']['form']['handler']);
    }


    public function configureChangePassword(array $config, ContainerBuilder $container){

        $container->setParameter('rz.user.change_password.form.type', $config['change_password']['form']['type']);
        $container->setParameter('rz.user.change_password.form.name', $config['change_password']['form']['name']);
        $container->setParameter('rz.user.change_password.form.validation_groups', $config['change_password']['form']['validation_groups']);

        $container->setAlias('rz.user.change_password.form.handler', $config['change_password']['form']['handler']);

    }

    public function configureResetting(array $config, ContainerBuilder $container){

        $container->setParameter('rz.user.resetting.form.type', $config['resetting']['form']['type']);
        $container->setParameter('rz.user.resetting.form.name', $config['resetting']['form']['name']);
        $container->setParameter('rz.user.resetting.form.validation_groups', $config['resetting']['form']['validation_groups']);

        $container->setAlias('rz.user.resetting.form.handler', $config['resetting']['form']['handler']);

    }

    public function configurePasswordStrength(array $config, ContainerBuilder $container) {

        if (!empty($config['password_security'])) {
            $definition = $container->getDefinition('rz_user.password_strength.config.manager');

            if(array_key_exists('requirement', $config['password_security'])) {
                $definition->addMethodCall('setConfig', array('requirement', $config['password_security']['requirement']));
            }

            if(array_key_exists('strength', $config['password_security'])) {
                $definition->addMethodCall('setConfig', array('strength', $config['password_security']['strength']));
            }

        }

    }
}
