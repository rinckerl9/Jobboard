<?php

namespace App\Form\Database;

use App\Entity\User;
use App\Entity\Ad;
use App\Entity\Company;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('id')
            ->add('username')
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'choices'  => [
                    'Applicant' => "ROLE_USER",
                    'Advertiser' => "ROLE_ADVERTISER",
                    'Administrator' => "ROLE_ADMIN"
                ],
                'required' => true
            ])
            ->add('email', EmailType::class)
            ->add('company', EntityType::class, [
                'required'   => true,
                'class' => Company::class,
                'choice_label' => "name"
            ])
            ->add('applications')
            ->add('Advertisements', EntityType::class, [
                'required' => false,
                'multiple' => true,
                'class' => Ad::class,
                'choice_label' => "title"
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
