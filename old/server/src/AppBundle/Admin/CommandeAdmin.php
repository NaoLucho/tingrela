<?php

// src/AppBundle/Admin/CommandeAdmin.php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper; //pour configureDatagridFilters

use AppBundle\Form\Type\CommandeBasketType;

class CommandeAdmin extends AbstractAdmin
{
    //Structure des blocks et définition des champs pour la création, l'affichage de l'element et son édition.
    protected function configureFormFields(FormMapper $formMapper)
    {
        
        $formMapper
            ->with("Informations de l'acheteur", array('class' => 'col-md-9'))
                ->add('firstname', 'text', array('disabled' => true))
                ->add('lastname', 'text', array('disabled' => true))
                ->add('email', 'text', array('disabled' => true))
                ->add('phone', 'text', array('disabled' => true))
                ->add('adresse', 'text', array('disabled' => true))
                ->add('postalcode', 'integer', array('disabled' => true))
                ->add('city', 'text', array('disabled' => true))
            ->end()
            ->with("Informations de la commande", array('class' => 'col-md-9'))
                ->add('reference', 'text', array('disabled' => true))
                ->add('comment', 'textarea')
                ->add('validated')
            ->end()
            ->with("Contenu de la commande", array('class' => 'col-md-9'))
                ->add('commandeBaskets', 'sonata_type_collection', array(
                    'required' => false,
                    'btn_add' => false,
                    'disabled' => true,
                    'type_options' => array(
                        // Prevents the "Delete" option from being displayed
                        'delete' => false,
                        'delete_options' => array(
                            // You may otherwise choose to put the field but hide it
                            'type'         => 'hidden',
                            // In that case, you need to fill in the options as well
                            'type_options' => array(
                                'mapped'   => false,
                                'required' => false,
                            )
                        )
                    )
                ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position',
                    'btn_add' => false,
                ))
            ->end()
            // NOT Work on edit liste of categories
            // ->with('Produits', array('class' => 'col-md-9'))
            //     ->add('commandeBaskets', 'entity', array(
            //         'class' => 'AppBundle\Entity\CommandeBaskets',
            //         'choice_label' => 'quantity'
            //     ))
            // ->end()
        ;
    }

    //Données définies pour l'affichage de la liste des éléments
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('reference', 'text')
            ->add('firstname', 'text')
            ->add('lastname', 'text')
            ->add('email', 'text')
            ->add('phone', 'text')
            ->add('city', 'text')
            ->add('validated', 'boolean')
            ->add('comment', 'textarea')
            
            // ->add('commandeBaskets', null, array(
            //         'associated_property' => 'product')
            // )
            // ->add('categories', 'entity', array(
            //         'class' => 'AppBundle\Entity\Category',
            //         'choice_label' => 'category',
            //         'multiple' => true))
        ;
    }

    //Ajout de filtre dans l'affichage de la liste des éléments
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('city')
            ->add('comment')
            ->add('validated')
            ->add('reference')
            // ->add('category', null, array(), 'entity', array(
            //     'class'    => 'AppBundle\Entity\Category',
            //     'choice_label' => 'category', // In Symfony2: 'property' => 'name'
            // ))
            ;
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

    // Override du message de création par un texte souhaité (plus lisible: Item "Produit" has been successfully created.)
    public function toString($object)
    {
        return $object instanceof Product
            ? $object->getTitle()
            : 'Commande'; // shown in the breadcrumb on the create view
    }


}
