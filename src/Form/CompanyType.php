<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Société'
            ])
            ->add('siret', TextType::class, [
                'label' => 'N° Siret'
            ])
            ->add('description',TextareaType::class,[
                'label' => 'Quelques mots sur votre société',
                'empty_data' => "Pas de descriptif renseigné pour cette société"
            ])
            ->add('validated', HiddenType::class, [
                'attr' => [
                    'value' => 0,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
