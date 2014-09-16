<?php

/*
 * This file is part of the RzUserBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\UserBundle\Admin\Entity;

use Sonata\UserBundle\Admin\Entity\GroupAdmin as BaseGroupAdmin;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class GroupAdmin extends BaseGroupAdmin
{

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, array('footable'=>array('attr'=>array('data_toggle'=>true))))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'Show' => array('template' => 'SonataAdminBundle:CRUD:list__action_show.html.twig'),
                    'Edit' => array('template' => 'SonataAdminBundle:CRUD:list__action_edit.html.twig'),
                    'Delete' => array('template' => 'SonataAdminBundle:CRUD:list__action_delete.html.twig')),
                'footable'=>array('attr'=>array('data_hide'=>'phone,tablet')),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('roles', 'sonata_security_roles', array(
                             'multiple' => true,
                             'multiselect_enabled' => true,
                             'required' => false
                         ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null ,array('operator_options'=>array('expanded' => false)))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('roles')
        ;
    }
}
