<?php

// src/AppBundle/Admin/BlogPostAdmin.php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper; //pour configureDatagridFilters

class ProductAdmin extends AbstractAdmin
{
    //Structure des blocks et définition des champs pour la création, l'affichage de l'element et son édition.
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Content', ['class' => 'col-md-9'])
                ->add('name', 'text')
                ->add('description', 'textarea')
                ->add('price','number')
                ->add('unity','text')
                ->add('pas','integer')
                ->add('stock','integer')
                ->add('placement','integer')
                ->add('imageFile', 'file', array(
                    'required' => false
                ))
            ->end()
            // NOT Work on edit liste of categories
            ->with('Category', array('class' => 'col-md-9'))
                // ->add('categories', 'entity', array(
                //     'class' => 'AppBundle\Entity\Category',
                //     'choice_label' => 'category',
                //     'multiple' => true
                // ))
                ->add('categories', 'sonata_type_model', [
                    'class' => 'AppBundle\Entity\Category',
                    'property' => 'category',
                    'multiple' => true,
                    'btn_add' => false
                ])
                // ->add('categories', CollectionType::class, array(
                //     'entry_type'   => CategoryType::class,
                //     'allow_add'    => true,
                // ))
            ->end()
        ;
    }

    //Données définies pour l'affichage de la liste des éléments
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->addIdentifier('id')
            ->addIdentifier('name')
            ->add('price')
            ->add('pas')
            ->add('unity')
            ->add('stock')
            ->add('categories', null, array(
                    'associated_property' => 'category')
            )
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
            ->add('name')
            ->add('price')
            ->add('unity')
            ->add('stock')
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
            : 'Produit'; // shown in the breadcrumb on the create view
    }


    //////////////////////////////////////////////////////
    //////////////////////////////////////////////////////

    private function manageEmbeddedImageAdmins($page) {
        // On passe tous les fileds pour chercher le field image
        foreach ($this->getFormFieldDescriptions() as $fieldName => $fieldDescription) {
            // On détecte celui qui gère les fichiers et on le cherche dans l'entité
            if ($fieldDescription->getType() === 'file' &&
                ($associationMapping = $fieldDescription->getAssociationMapping()) &&
                $associationMapping['targetEntity'] === 'AppBundle\Entity\Creation'
            ) {
                //On récupère les getter et setter de l'image
                $getter = 'get'.$fieldName;
                $setter = 'set'.$fieldName;

                /** @var Image $image */
                $image = $page->$getter();

                //Si jamais une image a été chargée on la met à jour
                if ($image) {
                    if ($image->getImageFile()) {
                        // On gère le file management
                        $image->refreshUpdated();
                    } elseif (!$image->getImageFile() && !$image->getImageName()) {
                        // Sinon on évite que sonata met à jour l'image s'il n'y a pas d'image chargée mais qu'elle existe déjà 
                        $page->$setter(null);
                    }
                }
            }
        }
    }

}
