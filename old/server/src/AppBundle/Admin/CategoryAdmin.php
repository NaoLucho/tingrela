<?php

// src/AppBundle/Admin/BlogPostAdmin.php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper; //pour configureDatagridFilters

class CategoryAdmin extends AbstractAdmin
{
    //Structure des blocks et définition des champs pour la création, l'affichage de l'element et son édition.
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Content', array('class' => 'col-md-9'))
                ->add('category', 'text')
                ->add('description', 'textarea')
            ->end()
            ->with('Type', array('class' => 'col-md-9'))
                ->add('type', 'entity', array(
                    'class' => 'AppBundle\Entity\Type',
                    'choice_label' => 'type',
                ))
            ->end()
            ->with('Products', array('class' => 'col-md-9'))
                ->add('products', 'entity', array(
                    'class' => 'AppBundle\Entity\Product',
                    'choice_label' => 'name',
                    'multiple' => true
                ))
            ->end()
        ;
    }

    //Données définies pour l'affichage de la liste des éléments
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id') //->addIdentifier('category')
            ->addIdentifier('category')
            ->add('type.type')
            ->add('description')
        ;
    }

    //Ajout de filtre dans l'affichage de la liste des éléments
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('category')
            ->add('type', null, array(), 'entity', array(
                'class'    => 'AppBundle\Entity\Type',
                'choice_label' => 'type', // In Symfony2: 'property' => 'name'
            ));
    }

    // Pour information, la fonction add utilisé dans l'admin reçoit 5 arguments:
    // public function add(
    //     $name,

    //     // filter
    //     $type = null,
    //     array $filterOptions = array(),

    //     // field
    //     $fieldType = null,
    //     $fieldOptions = null
    // )

    //Override du message de création par un texte souhaité (plus lisible: Item "Categorie" has been successfully created.)
    public function toString($object)
    {
        return $object instanceof category
            ? $object->getTitle()
            : 'Category'; // shown in the breadcrumb on the create view
    }


}
