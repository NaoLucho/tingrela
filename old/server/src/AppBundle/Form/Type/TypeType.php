<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Form\Extension\Core\Type\CollectionType;

//// Permet de vérifier la validité du contenu lors d'un post ou patch rest
// (non utilisé pour le moment puisque nous ne faisons que des GET)
// Update et Create est géré par Sonata Admin
class TypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type')
            ->add('description');

        // $builder
        //     ->add('categories', CollectionType::class, [
        //         'entry_type' => CategoryType::class,
        //         'allow_add' => true,
        //         'error_bubbling' => false
        //         ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Type',
            'csrf_protection' => false
        ]);
    }
}
