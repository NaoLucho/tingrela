<?php

// src/AppBundle/Admin/BlogPostAdmin.php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper; //pour configureDatagridFilters

class CreationAdmin extends AbstractAdmin {
    //Structure des blocks et définition des champs pour la création, l'affichage de l'element et son édition.
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Content', array('class' => 'col-md-9'))
                ->add('title', 'text')
                ->add('description', 'textarea')
                ->add('date', 'datetime')
                ->add('imageFile', 'file', array(
                    'required' => false
                ))
            ->end()
        ;
    }

    //Données définies pour l'affichage de la liste des éléments
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('date')
            ->add('description')
        ;
    }

    //Ajout de filtre dans l'affichage de la liste des éléments
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('date', 'doctrine_orm_date_range', array(
                'field_type'=>'sonata_type_datetime_range_picker'
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
            : 'Creation'; // shown in the breadcrumb on the create view
    }

    /////////////////////////////////////////////////
    /////////////////////////////////////////////////

    public function prePersist($page) {
        $this->manageEmbeddedImageAdmins($page);
    }

    public function preUpdate($page) {
        $this->manageEmbeddedImageAdmins($page);
    }

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
