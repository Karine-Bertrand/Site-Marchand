<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('line', TextType::class,[
                'label' => 'Numéro et Rue'
            ])
            ->add('line2', TextType::class,
            [
                'label' => 'Complément d\'adresse',
                'required' => false
            ])
            ->add('zipcode', TextType::class,[
                'label' => 'Code Postal'
            ])
            ->add('city', TextType::class,[
                'label' => 'Ville'
            ])
            ->add('latitude', HiddenType::class, [
                'data' => '45.80'
            ])
            ->add('longitude', HiddenType::class, [
                'data' => '4.80'
            ]); 
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
