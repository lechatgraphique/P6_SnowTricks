<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $widgetAttr = 'w-full appearance-none bg-gray-200 border border-grey-lighter text-grey-darker py-3 px-4 rounded focus:outline-none focus:bg-white';
        $labelAttr = 'block mb-2 uppercase tracking-wide text-grey-darker text-xs font-bold';

        $builder
            ->add('username',TextType::class, [
                'label'             => 'Email',
                'label_attr'        => [
                    'class'         => $labelAttr
                ],
                'attr'              => [
                    'placeholder'   => 'Email',
                    'class'         => $widgetAttr
                ]
            ])
            ->add('password',PasswordType::class, [
                'label' => 'Mot de passe',
                'label_attr'        => [
                    'class'         => $labelAttr
                ],
                'attr'              => [
                    'placeholder'   => 'Mot de passe',
                    'class'         => $widgetAttr
                ]
            ])
            ->add('confirmPassword',PasswordType::class, [
                'label' => 'Confirmez le Mot de passe',
                'label_attr'        => [
                    'class'         => $labelAttr
                ],
                'attr'              => [
                    'placeholder'   => 'Confirmez le Mot de passe',
                    'class'         => $widgetAttr
                ]
            ])
            ->add('firstName',TextType::class, [
                'label' => 'Prénom',
                'label_attr'        => [
                    'class'         => $labelAttr
                ],
                'attr'              => [
                    'placeholder'   => 'Prénom',
                    'class'         => $widgetAttr
                ]
            ])
            ->add('lastName',TextType::class, [
                'label' => 'Nom',
                'label_attr'        => [
                    'class'         => $labelAttr
                ],
                'attr'              => [
                    'placeholder'   => 'Nom',
                    'class'         => $widgetAttr
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

