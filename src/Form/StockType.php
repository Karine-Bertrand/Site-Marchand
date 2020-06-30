<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Product;
use App\Entity\Stock;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class StockType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
        return $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('product', EntityType::class, [
                'label' => 'Produit',
                'class' => Product::class,
                'choice_label' => 'name'
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix',
            ])
            ->add('conditioning', ChoiceType::class, [
                'label' => 'Conditionnement',
                'choices' => [
                    'kg' => 'kg',
                    'unité' => 'unité'
                ]
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité'
            ])
            
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => false
            ])

            /* ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => "id", 
                
            ]) */
            ->add('validated', HiddenType::class, [
                'attr' => [
                    "value" => true,
                ]
            ])
            //->add('validated')
            //->add('company')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
