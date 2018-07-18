<?php

// src/AppBundle/Admin/CategoryAdmin.php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TypeAdmin extends AbstractAdmin
{
    // public function configureShowFields(ShowMapper $showMapper)
    // {
    //     $showMapper
    //         ->tab('General') // the tab call is optional
    //             ->with('Addresses', array(
    //                 'class'       => 'col-md-8',
    //                 'box_class'   => 'box box-solid box-danger',
    //                 'description' => 'Lorem ipsum',
    //             ))
    //                 ->add('type')
    //                 // ...
    //             ->end()
    //         ->end()
    //     ;
    // }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->add('type', 'text')
        ->add('description', 'textarea');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add('description')
        ->add('type');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('type')
            ->add('description');
    }
}
