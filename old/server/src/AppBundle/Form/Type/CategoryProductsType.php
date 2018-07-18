<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CategoryProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // { "products": [
        //         { "id": 1 },
        //         { "name" : "produit2" }
        //     ]
        // }

        $builder
            ->add('products', CollectionType::class, array(
            'entry_type' => ProductType::class,
            'allow_add' => true
        ));
            //
            //     'error_bubbling' => false,
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Category',
            'csrf_protection' => false
        ]);
    }
}
