<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Repository\IngredientRepository;
use \Symfony\Bundle\SecurityBundle\Security ;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RecipeType extends AbstractType
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security =$security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    ' maxlenght' => '50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('time',IntegerType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'min'=>1,
                    'max'=>1440
                ],
                'required'=> false,
                'label'=>'temps (minutes)',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints' =>[
                    new Assert\Positive(),
                    new Assert\LessThan(1441)
                ]

                ])
            ->add('nbPeople', IntegerType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'min'=>1,
                    'max'=>50
                ],
                'required'=> false,
                'label'=>'Nombre de personne',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints' =>[
                    new Assert\Positive(),
                    new Assert\LessThan(51)
                ]

                ])
            ->add('difficutly',RangeType::class,[
                'attr'=>[
                    'class'=>'form-range',
                    'min'=>1,
                    'max'=>5
                ],
                'required'=> false,
                'label'=>'difficulté',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints' =>[
                    new Assert\Positive(),
                    new Assert\LessThan(6)
                ]

                ])
            ->add('description',TextareaType::class,[
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
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control '
                ],
                'required'=> false,
                'label' => 'prix',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1001)
                ]
            ])

            ->add('imageFile',VichImageType::class,[
                'label' =>'image de la recette',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ]
            ])
            ->add('isFavorite',CheckboxType::class,[
                'attr'=>[
                    'class'=>'form-ckeck-input',
                   
                ],
                'required'=> false,
                'label'=>'Favorie ?',
                'label_attr'=>[
                    'class'=>'form-check-label'
                ],
                'constraints' =>[
                    new Assert\NotNull()
                ]

                ])

                ->add('isPublic',CheckboxType::class,[
                    'attr'=>[
                        'class'=>'form-ckeck-input',
                       
                    ],
                    'required'=> false,
                    'label'=>'Public ?',
                    'label_attr'=>[
                        'class'=>'form-check-label'
                    ],
                    'constraints' =>[
                        new Assert\NotNull()
                    ]
    
                    ])
            ->add('ingredient',EntityType:: class,[
                
                // looks for choices from this entity
                'class' => Ingredient::class,
                'query_builder' => function (IngredientRepository $r) {
                    return $r->createQueryBuilder('i')
                        ->where('i.user = :user')
                        ->orderBy('i.name', 'ASC')
                        ->setParameter('user',$this->security->getToken()->getUser());
                },
                'label'=>'les ingredient',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'multiple'=>true,
                'expanded'=>true,
                
                ])
            ->add('submit', SubmitType::class,[
                'attr'=> [
                    'class'=>'btn btn-primary mt-4'
                ],
                'label'=>'créer ma recette'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
