<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                // key = clé
                'label' => 'Titre<span class="text-danger">*</span>',
                'label_html' => true,
                'label_attr' => [
                    'class' => 'text-primary'
                ],
                'attr' => [
                    'placeholder' => 'Saisir un titre',
                    'class' => 'bg-light shadow rounded'

                ],
                'help' => 'Nombre de caractères maximal : <span class="text-dark">30</span>',
                'help_html' => true,
                'help_attr' => [
                    'class' => 'text-success'
                ],
                'row_attr' => [
                    'class' => 'shadow p-4 m-4'
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le titre du produit'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Veuillez saisir au moins 5 caractères',
                        'max' => 30,
                        'maxMessage' => 'veuillez saisir au maximum 30 caractères'                                                       
                    ])
                ]
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'EUR',
                'label' => 'Prix<span class="text-danger">*</span>',
                'label_html' => true,
                'label_attr' => [
                    'class' => 'text-primary'
                ],
                'attr' => [
                    'placeholder' => 'Saisir un prix',
                    'class' => 'bg-light shadow rounded'
                ],
                'help' => 'Prix strictement supérieur à : <span class="text-dark">0</span>',
                'help_html' => true,
                'help_attr' => [
                    'class' => 'text-info'
                ],
                'row_attr' => [
                    'class' => 'shadow p-4 m-4'
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le prix du produit'
                    ]),
                    new Positive([
                        'message' => 'Veuillez saisir un prix strictement supérieur à 0'
                    ])
                ]
            ])

            ->add('description', null, [
                'label' => 'Description <span class="text-info">(facultative)</span>',
                'label_html' => true,
                'label_attr' => [
                    'class' => 'text-primary'
                ],
                'attr' => [
                    'placeholder' => 'Saisir une description',
                    'class' => 'bg-textarea shadow rounded',
                    'rows' => 5
                ],
                'help' => 'Nombre de caratères maximal : <span class="text-dark">200</span>',
                'help_html' => true,
                'help_attr' => [
                    'class' => 'text-success'
                ],
                'row_attr' => [
                    'class' => 'shadow p-4 m-4'
                ],
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 200,
                        'maxMessage' => 'Veuillez saisir au maximum 200 caractères'
                    
                    ])
                ]
            ])

            

            ->add('image', FileType::class, [
                'label' => 'Image du parfum',
                'label_html' => true,
                'label_attr' => [
                    'class' => 'text-dark'
                ],
                'attr' => [
                    'class' => 'bg-light shadow rounded',
                ],
                'row_attr' => [
                    'class' => 'shadow p-4 m-4'
                ],
                'mapped' => false,
                'required' => false
            ]) 
         
            ->add('category', EntityType::class, [ // propriété relationnelle
                'class' => Category::class, // relation avec quel class (entitie)
                 //'choice_label' => 'title',  // nom de la propriété dans la class

                /* 'multiple' => true  // obligatoir pour la relation ManyToMany */

                'choice_label' => function(Category $category){
                    return ucfirst($category->getTitle()) . '( Nombre: ' . count($category->getProducts()) . ')';
                }, 

                'expanded' => false, // tranformer le select soit en un chekbox soit en radio (en fonction de multiple)
                'placeholder' => '-- Séléctionner une catégorie --',
                'label' => 'Catégorie<span class="text-danger">*</span>',
                'label_html' => true,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une catégorie '
                    ]),
                ],
                'query_builder' => function (CategoryRepository $categoryRepository){
                    return $categoryRepository->createQueryBuilder('c')
                        ->orderBy('c.title' , 'ASC')

                ;   
                }

            ])


            

            //->add( 'Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            
        ]);
    }
}
