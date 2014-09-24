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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('rz_user');
        $this->addBundleSettings($node);
        $this->addChangePasswordSection($node);
        $this->addRegistrationSection($node);
        $this->addTemplates($node);
        $this->addPasswordStrength($node);
        return $treeBuilder;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addBundleSettings(ArrayNodeDefinition $node)
    {
        /**
         * TODO: refactor as not to copy the whole configuration of SonataUserBundle
         * This section will allow RzBundle to override SonataUserBundle via rz_user configuration
         */
        $supportedManagerTypes = array('orm', 'mongodb');

        $node
            ->children()
                ->scalarNode('manager_type')
                    ->defaultValue('orm')
                    ->validate()
                        ->ifNotInArray($supportedManagerTypes)
                        ->thenInvalid('The manager type %s is not supported. Please choose one of '.json_encode($supportedManagerTypes))
                    ->end()
                ->end()
                ->arrayNode('class')
                    ->children()
                        ->scalarNode('group')->cannotBeEmpty()->end()
                        ->scalarNode('user')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('admin')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('user')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\UserBundle\\Admin\\Entity\\UserAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('SonataAdminBundle:CRUD')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('RzUserBundle')->end()
                                ->arrayNode('templates')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('edit')->defaultValue('RzUserBundle:Admin:CRUD/edit.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('history_revision')->defaultValue('RzAdminBundle:CRUD:history_revision.html.twig')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('group')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->cannotBeEmpty()->defaultValue('Rz\\UserBundle\\Admin\\Entity\\GroupAdmin')->end()
                                ->scalarNode('controller')->cannotBeEmpty()->defaultValue('SonataAdminBundle:CRUD')->end()
                                ->scalarNode('translation')->cannotBeEmpty()->defaultValue('RzUserBundle')->end()
                                ->arrayNode('templates')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('list')->defaultValue('SonataAdminBundle:CRUD:list.html.twig')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                // Original code from the FOS User Bundle
                ->arrayNode('profile')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('rz_user_profile')->end()
                                ->scalarNode('handler')->defaultValue('rz.user.profile.form.handler.default')->end()
                                ->scalarNode('name')->defaultValue('rz_user_profile_form')->cannotBeEmpty()->end()
                                ->arrayNode('validation_groups')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array('Profile', 'Default'))
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('blocks_service')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue('Rz\\UserBundle\\Block\\ProfileMenuBlockService')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }


    private function addRegistrationSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('registration')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('rz_user_registration')->end()
                                ->scalarNode('handler')->defaultValue('rz.user.registration.form.handler.default')->end()
                                ->scalarNode('name')->defaultValue('rz_user_registration_form')->end()
                                ->arrayNode('validation_groups')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array('Registration', 'Default'))
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     *
     * @return void
     */
    private function addTemplates(ArrayNodeDefinition $node)
    {
        //TODO: add other templates for configuration
        $node
            ->children()
                ->arrayNode('templates')
                        ->addDefaultsIfNotSet()
                        ->canBeUnset()
                        ->children()
                            ->scalarNode('layout')->defaultValue('RzUserBundle::layout.html.twig')->end()
                            ->scalarNode('login')->defaultValue('RzUserBundle:Security:login.html.twig')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

            /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     *
     * @return void
     */
    private function addPasswordStrength(ArrayNodeDefinition $node)
    {
        //TODO: add other templates for configuration
        $node
            ->children()
                ->arrayNode('password_security')
                        ->addDefaultsIfNotSet()
                        ->canBeUnset()
                        ->children()
                            ->arrayNode('requirement')
                            ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('min_length')->defaultValue(8)->end()
                                    ->scalarNode('require_letters')->defaultValue(true)->end()
                                    ->scalarNode('require_case_diff')->defaultValue(false)->end()
                                    ->scalarNode('require_numbers')->defaultValue(false)->end()
                                    ->scalarNode('require_special_character')->defaultValue(false)->end()
                                ->end()
                            ->end()
                            ->arrayNode('strength')
                            ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('min_length')->defaultValue(8)->end()
                                    ->scalarNode('min_strength')->defaultValue(1)->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addChangePasswordSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('change_password')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('rz_user_change_password')->end()
                                ->scalarNode('handler')->defaultValue('rz.user.change_password.form.handler.default')->end()
                                ->scalarNode('name')->defaultValue('rz_user_change_password_form')->end()
                                ->arrayNode('validation_groups')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array('ChangePassword', 'Default'))
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }


    /**
     * Returns default values for profile menu (to avoid BC Break)
     *
     * @return array
     */
    protected function getProfileMenuDefaultValues()
    {
        return array(
            array(
                'route'  => 'sonata_user_profile_edit',
                'label'  => 'link_edit_profile',
                'domain' => 'SonataUserBundle',
                'route_parameters' => array()
            ),
            array(
                'route'  => 'sonata_user_profile_edit_authentication',
                'label'  => 'link_edit_authentication',
                'domain' => 'SonataUserBundle',
                'route_parameters' => array()
            ),
        );
    }
}
