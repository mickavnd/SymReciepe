<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Webmozart\Assert\Assert as AssertAssert;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class , [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    ' maxlenght' => '50'
                ],
                'label' => 'Nom / Prenom',
                'label_attr' => [
                    'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ]
            
            
            )
            ->add('pseudo',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    ' maxlenght' => '50'
                ],
                'required'=> false,
                'label' => 'pseudo(facultatif)',
                'label_attr' => [
                    'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    
                ]
            ]

            )
            ->add('email',EmailType:: class,[
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    ' maxlenght' => '180'
                ],
                'label' => ' adresse email',
                'label_attr' => [
                    'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 180]),
                    new Assert\NotBlank(),
                    new Assert\Email()
                ]
            ]
            
            )
            ->add('plainpassword',RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options'=>[
                    'attr'=>[
                        'class'=>'form-control'
                    ],
                    'label'=> 'Mot de passe',
                    'label_attr'=>[
                        'form-label mt4'
                    ]
                    
                ],
                'second_options'=>[
   
                    'attr'=>[
                        'class'=>'form-control'
                    ], 
                    "label" => 'Confirmation du mot  de passe',
                    'label_attr'=>[
                        'form-label mt4'
                    ]
                ],
                'invalid_message'=>' les mot de passe ne correspdent pas.'

            ])
            ->add('submit',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-primary'
                ]
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
