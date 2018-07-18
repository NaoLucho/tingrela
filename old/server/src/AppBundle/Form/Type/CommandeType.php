<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Form\Extension\Core\Type\CollectionType;


  // id: number;
  // username: string;
  // email: string;
  // adresse: string;
  // date: Date;
  // validated: boolean;
  // reference: number;

//// Permet de vérifier la validité du contenu lors d'un post ou patch rest
class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('adresse')
            ->add('postalcode')
            ->add('city')
            ->add('comment')
            ->add('validated')
            ->add('reference');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Commande',
            'csrf_protection' => false
        ]);
    }
}
