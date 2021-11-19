<?php

namespace App\Form\Database;

use App\Entity\Ad;
use App\Entity\Company;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;


class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', NULL, [
                'required'   => true,
            ])
            ->add('description', NULL, [
                'required'   => true,
            ])
            ->add('salary', IntegerType::class, [
                'required'   => true,
                'attr'       => [
                    'min' => 0,
                    'max' => 1000000000
                ]
            ])
            ->add('location', NULL, [
                'required'   => true,
            ])
            ->add('company', EntityType::class, [
                'required'   => true,
                'class' => Company::class,
                'choice_label' => "name"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
