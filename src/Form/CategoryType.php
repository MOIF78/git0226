<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title' , null, [
                
                'label' => 'Titre<span class="text-danger">*</span>',
                'label_html' => true,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisir le titre de la cétégorie'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le titre de la categorie' 
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Veuillez saisir au moins 5 caractères',
                        'max' => 30,
                        'maxMessage' => 'veuillez saisir au maximum 30 caractères'
                    ])
                ]
                
            ])
        ;
    }

  

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
