<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

//// Permet de vérifier la validité du contenu lors d'un post ou patch rest
class CommandeBasketType extends AbstractType {

    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add('quantity', 'number');
            // ->add('product', EntityType::class, [
            //     'class' => 'AppBundle:Product',
            //     'choice_label' => 'name'
            //     ]);
            // ->add('text', 'text', array( 'required' => false, 'label' => 'question' ) )
            // ->add('answers', 'collection', array(
            //         'type'               => new AnswerType(),
            //         'allow_add'          => true,
            //         'allow_delete'       => true,
            //         'by_reference'       => false,
            //         'delete_empty'       => true,
            //         'cascade_validation' => false,
            //         'label'              => 'Answers',
            //         'options'            => array( 'label' => false ),
            //         'label_attr'         => array( 'class' => 'answers' ),
            // ));
    }


    public function getName()
    {
        return 'CommandeProduit';
    }

    public function setDefaultOptions( OptionsResolverInterface $resolver )
    {
        $resolver->setDefaults( array(
            'data_class' => 'AppBundle:CommandeBasket',
        ));
    }

}