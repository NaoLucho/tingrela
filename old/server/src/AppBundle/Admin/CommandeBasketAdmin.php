<?php

// src/AppBundle/Admin/CommandeBasketAdmin.php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CommandeBasketAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->add('product', 'entity', array(
                    'class' => 'AppBundle\Entity\Product',
                    'choice_label' => 'name',
                    'multiple' => false
                ))
        ->add('quantity', 'number');
    }

}
