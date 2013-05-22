<?php

namespace Rz\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use Sonata\AdminBundle\Admin\Pool;

class SecurityRolesType extends AbstractTypeExtension
{
    protected $pool;

    /**
     * @param Pool $pool
     */
    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if ($options['expanded']) {
            $view->vars['selectpicker_enabled'] = $options['selectpicker_enabled']= false;
            $view->vars['chosen_enabled'] = $options['chosen_enabled'] =  false;
            $view->vars['multiselect_enabled'] = $options['multiselect_enabled'] = false;
            $view->vars['multiselect_search_enabled'] = $options['multiselect_search_enabled'] = false;
        } elseif ($options['chosen_enabled']) {

            $view->vars['selectpicker_enabled'] = $options['selectpicker_enabled']= false;
            $view->vars['chosen_enabled'] = $options['chosen_enabled'] =  true;
            $view->vars['multiselect_enabled'] = $options['multiselect_enabled'] = false;
            $view->vars['multiselect_search_enabled'] = $options['multiselect_search_enabled'] = false;

            $view->vars['attr']['class'] = sprintf(($options['multiple']) ? "chzn-select-multiple %s" : "chzn-select %s", $view->vars['attr']['class']);
            $view->vars['attr']['chosen_data_placeholder'] = array_key_exists('chosen_data_placeholder', $options) ? $options['chosen_data_placeholder'] : 'Choose one of the following...';
            $view->vars['attr']['chosen_no_results_text'] = array_key_exists('chosen_no_results_text', $options) ? $options['chosen_no_results_text'] : 'No record found.';

        } elseif ($options['selectpicker_enabled']) {

            $view->vars['selectpicker_enabled'] = $options['selectpicker_enabled'] = true;
            $view->vars['chosen_enabled'] = $options['chosen_enabled'] = false;
            $view->vars['multiselect_enabled'] = $options['multiselect_enabled'] = false;
            $view->vars['multiselect_search_enabled'] = $options['multiselect_search_enabled'] = false;

            if (array_key_exists('class', $view->vars['attr'])) {
                $view->vars['attr']['class'] = sprintf("selectpicker %s", $view->vars['attr']['class']);
            }

            if (array_key_exists('selectpicker_show_tick', $options)) {
                $view->vars['attr']['class'] = sprintf("%s show-tick", $view->vars['attr']['class']);
            }

            if (array_key_exists('selectpicker_data_style', $options)) {
                $view->vars['attr']['data-style'] = $options['selectpicker_data_style'];
            }

            //* TODO: add translation
            $view->vars['attr']['title'] = array_key_exists('selectpicker_title', $options) ? $options['selectpicker_title'] : 'Choose one of the following...';
            //$view->vars['selectpicker_selected_text_format'] = array_key_exists('selectpicker_selected_text_format', $options) ? $options['selectpicker_selected_text_format'] : 'values';

            $view->vars['attr']['data-size'] = array_key_exists('selectpicker_data_size', $options) ? $options['selectpicker_data_size'] : '5';

            if (array_key_exists('selectpicker_data_width', $options)) {
                $view->vars['attr']['data-width'] = $options['selectpicker_data_width'];
            }

            if (array_key_exists('selectpicker_disabled', $options)) {
                $view->vars['attr']['disabled'] = $options['selectpicker_disabled'];
            }

            if (array_key_exists('selectpicker_dropup', $options)) {
                $view->vars['attr']['class'] = sprintf("%s dropup", $view->vars['attr']['class']);
            }
        } elseif ($options['multiselect_enabled']) {
            $view->vars['multiple'] = true;
            $view->vars['selectpicker_enabled'] = $options['selectpicker_enabled'] = false;
            $view->vars['chosen_enabled'] = $options['chosen_enabled'] = false;
            $view->vars['multiselect_enabled'] = true;
            $view->vars['multiselect_search_enabled'] = false;

            if (array_key_exists('class', $view->vars['attr'])) {
                $view->vars['attr']['class'] = sprintf("multiselect %s", $view->vars['attr']['class']);
            }
        } elseif ($options['multiselect_search_enabled']) {
            $view->vars['multiple'] = true;
            $view->vars['selectpicker_enabled'] = $options['selectpicker_enabled'] = false;
            $view->vars['chosen_enabled'] = $options['chosen_enabled'] = false;
            $view->vars['multiselect_enabled'] = false;
            $view->vars['multiselect_search_enabled'] = true;

        } else {
            $view->vars['selectpicker_enabled'] = $options['selectpicker_enabled'] = false;
            $view->vars['chosen_enabled'] = $options['chosen_enabled'] = false;
            $view->vars['multiselect_enabled'] = $options['multiselect_enabled'] = false;
            $view->vars['multiselect_search_enabled'] = $options['multiselect_search_enabled'] = false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $roles = array();
        $rolesReadOnly = array();

        $securityContext = $this->pool->getContainer()->get('security.context');

        // get roles from the Admin classes
        foreach ($this->pool->getAdminServiceIds() as $id) {
            try {
                $admin = $this->pool->getInstance($id);
            } catch (\Exception $e) {
                continue;
            }

            $isMaster = $admin->isGranted('MASTER');
            $securityHandler = $admin->getSecurityHandler();
            // TODO get the base role from the admin or security handler
            $baseRole = $securityHandler->getBaseRole($admin);

            foreach ($admin->getSecurityInformation() as $role => $permissions) {
                $role = sprintf($baseRole, $role);

                if ($isMaster) {
                    // if the user has the MASTER permission, allow to grant access the admin roles to other users
                    $roles[$role] = $this->humanize($role);
                } elseif ($securityContext->isGranted($role)) {
                    // although the user has no MASTER permission, allow the currently logged in user to view the role
                    $rolesReadOnly[$role] = $this->humanize($role);
                }
            }
        }

        // get roles from the service container
        foreach ($this->pool->getContainer()->getParameter('security.role_hierarchy.roles') as $name => $rolesHierarchy) {

            if ($securityContext->isGranted($name)) {
                $roles[$name] = $this->humanize($name) . ': ' . $this->humanize(implode(', ', $rolesHierarchy));

                foreach ($rolesHierarchy as $role) {
                    if (!isset($roles[$role])) {
                        $roles[$role] = $this->humanize($role);
                    }
                }
            }
        }

        $resolver->setDefaults(array(
                                   'choices' =>  $roles,
                                   'read_only_choices' => function (Options $options) use ($rolesReadOnly) {
                                       return empty($options['choices']) ? $rolesReadOnly : array();
                                   },
                                   'data_class' => null,
                                   'chosen_enabled' => false,
                                   'selectpicker_enabled' => false,
                                   'multiselect_enabled' => false,
                                   'multiselect_search_enabled' => true,
                                   'expanded' => false,
                                   'compound' => false,
                                   'error_bubbling'=> true
                               ));

        $resolver->setOptional(array('selectpicker_enabled',
                                   'selectpicker_data_style',
                                   'selectpicker_title',
                                   'selectpicker_selected_text_format',
                                   'selectpicker_show_tick',
                                   'selectpicker_data_width',
                                   'selectpicker_data_size',
                                   'selectpicker_disabled',
                                   'selectpicker_dropup',
                                   'chosen_enabled',
                                   'chosen_data_placeholder',
                                   'chosen_no_results_text',
                                   'multiselect_enabled',
                                   'multiselect_search_enabled',
                               )
        );

    }

    private function humanize($text)
    {
        return trim(strtoupper(preg_replace('/[_\s]+/', ' ', $text)));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'sonata_security_roles';
    }
}
