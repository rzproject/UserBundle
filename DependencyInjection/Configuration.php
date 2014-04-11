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
        //$this->addChangePasswordSection($node);
        //$this->addAdminSettings($node);
        $this->addTemplates($node);
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
                ->booleanNode('security_acl')->defaultValue(false)->end()
                ->arrayNode('table')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('user_group')->defaultValue('fos_user_user_group')->end()
                    ->end()
                ->end()
                ->scalarNode('impersonating_route')->end()
                ->arrayNode('impersonating')
                    ->children()
                        ->scalarNode('route')->defaultValue(false)->end()
                        ->arrayNode('parameters')
                            ->useAttributeAsKey('id')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('google_authenticator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('server')->cannotBeEmpty()->end()
                        ->scalarNode('enabled')->defaultValue(false)->end()
                    ->end()
                ->end()
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
                                        ->scalarNode('list')->defaultValue('SonataAdminBundle:CRUD:list.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('show')->defaultValue('SonataAdminBundle:CRUD:show.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('edit')->defaultValue('RzUserBundle:Admin:CRUD/edit.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('preview')->defaultValue('SonataAdminBundle:CRUD:preview.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('history')->defaultValue('SonataAdminBundle:CRUD:history.html.twig')->cannotBeEmpty()->end()
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
                                        ->scalarNode('show')->defaultValue('SonataAdminBundle:CRUD:show.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('edit')->defaultValue('SonataAdminBundle:CRUD:edit.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('preview')->defaultValue('SonataAdminBundle:CRUD:preview.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('history')->defaultValue('SonataAdminBundle:CRUD:history.html.twig')->cannotBeEmpty()->end()
                                        ->scalarNode('history_revision')->defaultValue('SonataAdminBundle:CRUD:history_revision.html.twig')->cannotBeEmpty()->end()
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
                        ->scalarNode('default_avatar')->defaultValue('bundles/sonatauser/default_avatar.png')->end()
                        ->arrayNode('dashboard')
                            ->addDefaultsIfNotSet()
                            ->fixXmlConfig('group')
                            ->fixXmlConfig('block')
                            ->children()
                                ->arrayNode('groups')
                                    ->useAttributeAsKey('id')
                                    ->prototype('array')
                                    ->fixXmlConfig('item')
                                    ->fixXmlConfig('item_add')
                                    ->children()
                                        ->scalarNode('label')->end()
                                        ->scalarNode('label_catalogue')->end()
                                        ->arrayNode('items')
                                            ->prototype('scalar')->end()
                                        ->end()
                                        ->arrayNode('item_adds')
                                            ->prototype('scalar')->end()
                                        ->end()
                                        ->arrayNode('roles')
                                            ->prototype('scalar')->defaultValue(array())->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('blocks')
                                ->defaultValue(array(array('position' => 'left', 'settings' => array('content' => "<h2>Welcome!</h2> This is a sample user profile dashboard, feel free to override it in the configuration!"), 'type' => 'sonata.block.service.text')))
                                ->prototype('array')
                                    ->fixXmlConfig('setting')
                                        ->children()
                                            ->scalarNode('type')->cannotBeEmpty()->end()
                                            ->arrayNode('settings')
                                                ->useAttributeAsKey('id')
                                                ->prototype('variable')->defaultValue(array())->end()
                                            ->end()
                                            ->scalarNode('position')->defaultValue('right')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('sonata_user_profile')->end()
                                ->scalarNode('handler')->defaultValue('sonata.user.profile.form.handler.default')->end()
                                ->scalarNode('name')->defaultValue('sonata_user_profile_form')->cannotBeEmpty()->end()
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
                        ->arrayNode('menu')
                            ->prototype('array')
                                ->addDefaultsIfNotSet()
                                ->cannotBeEmpty()
                                ->children()
                                    ->scalarNode('route')->cannotBeEmpty()->end()
                                    ->arrayNode('route_parameters')
                                        ->defaultValue(array())
                                        ->prototype('array')->end()
                                    ->end()
                                    ->scalarNode('label')->cannotBeEmpty()->end()
                                    ->scalarNode('domain')->defaultValue('messages')->end()
                                ->end()
                            ->end()
                            ->defaultValue($this->getProfileMenuDefaultValues())
                        ->end()
                        ->arrayNode('change_password')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('rz_user_update_password')->end()
                                ->scalarNode('handler')->defaultValue('rz_user.change_password.form.handler.default')->end()
                                ->scalarNode('name')->defaultValue('rz_user_update_password_form')->cannotBeEmpty()->end()
                                ->arrayNode('validation_groups')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array('Profile', 'Default'))
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('register')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('form')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('type')->defaultValue('sonata_user_registration')->end()
                                        ->scalarNode('handler')->defaultValue('sonata.user.registration.form.handler.default')->end()
                                        ->scalarNode('name')->defaultValue('sonata_user_registration_form')->cannotBeEmpty()->end()
                                        ->arrayNode('validation_groups')
                                            ->prototype('scalar')->end()
                                            ->defaultValue(array('Registration', 'Default'))
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('confirm')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('redirect')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('route')->defaultValue('sonata_user_profile_show')->end()
                                                ->arrayNode('route_parameters')
                                                    ->defaultValue(array())
                                                    ->prototype('array')->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
//                // Original code from the FOS User Bundle
//                ->arrayNode('profile')
//                    ->addDefaultsIfNotSet()
//                    ->canBeUnset()
//                    ->children()
//                        ->arrayNode('form')
//                            ->addDefaultsIfNotSet()
//                            ->children()
//                                ->scalarNode('type')->defaultValue('sonata_user_profile')->end()
//                                ->scalarNode('name')->defaultValue('sonata_user_profile_form')->cannotBeEmpty()->end()
//                                ->arrayNode('validation_groups')
//                                    ->prototype('scalar')->end()
//                                    ->defaultValue(array('Profile', 'Default'))
//                                ->end()
//                            ->end()
//                        ->end()
//                        ->arrayNode('blocks_service')
//                        ->addDefaultsIfNotSet()
//                            ->children()
//                                ->scalarNode('class')->defaultValue('Rz\\UserBundle\\Block\\ProfileMenuBlockService')->end()
//                            ->end()
//                        ->end()
//                        ->arrayNode('update_password')
//                            ->addDefaultsIfNotSet()
//                            ->children()
//                                ->scalarNode('type')->defaultValue('sonata_user_update_password')->end()
//                                ->scalarNode('name')->defaultValue('sonata_user_update_password_form')->cannotBeEmpty()->end()
//                                ->arrayNode('validation_groups')
//                                    ->prototype('scalar')->end()
//                                    ->defaultValue(array('Profile', 'Default'))
//                                ->end()
//                            ->end()
//                        ->end()
//
//                    ->end()
//                ->end()
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

//    private function addChangePasswordSection(ArrayNodeDefinition $node)
//    {
//        $node
//            ->children()
//                ->arrayNode('change_password')
//                    ->addDefaultsIfNotSet()
//                    ->canBeUnset()
//                    ->children()
//                        ->arrayNode('form')
//                            ->addDefaultsIfNotSet()
//                            ->children()
//                                ->scalarNode('type')->defaultValue('rz_user_change_password')->end()
//                                ->scalarNode('name')->defaultValue('fos_user_change_password_form')->end()
//                                ->arrayNode('validation_groups')
//                                    ->prototype('scalar')->end()
//                                    ->defaultValue(array('ChangePassword', 'Default'))
//                                ->end()
//                            ->end()
//                        ->end()
//                    ->end()
//                ->end()
//            ->end();
//    }

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
