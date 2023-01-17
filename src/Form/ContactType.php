<?php

namespace App\Form;

use App\Entity\Contact;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName',TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlenght' => '2',
                    ' maxlenght' => '50'
                ],
                'label' => 'Nom / Prenom',
                'label_attr' => [
                    'form-label mt-4'
                ],
                
            ])
            ->add('email',EmailType::class,[
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
            ->add('subject',TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlenght' => '2',
                    ' maxlenght' => '200'
                ],
                'label' => 'subject',
                'label_attr' => [
                    'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 100]),
                   
                ]
            ])
            ->add('message',TextareaType::class,[
                'attr'=>[
                    'class'=>'form-control'
                    
                ],
                'label'=>'description',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints' =>[
                    new Assert\NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class,[
                'attr'=> [
                    'class'=>'btn btn-primary mt-4'
                ],
                'label'=>'envoyer'
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
