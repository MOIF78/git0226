<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
              'label' => 'Email<span class="text-gold">*</span>',
              'label_html' => true,
              'required' => false,
              'attr' => [
                 'placeholder' => 'saisir une adresse email',
                  'autocomplete' => 'email'
              ],
              'constraints' => [
                new Email([
                    'message' => 'Veuillez saisir une adresse email valide'
                ]),
                    new NotBlank([
                    'message' => 'Veuillez saisir une adresse email conforme'
                    ]),
                ],
             
            ])
            ->add('firstName', null,[
            'label' => 'Prénom<span class="text-gold">*</span>',
            'label_html' => true,
            'required' => false,
              'attr' => [
                 'placeholder' => 'saisir un prénom',   
                ],
              'constraints' => [
                    new NotBlank([
                    'message' => 'Veuillez saisir un prénom'
                    ]),
                ],
                
            ])
            ->add('lastName', null,[
            'label' => 'Nom<span class="text-gold">*</span>',
            'label_html' => true,
            'required' => false,
              'attr' => [
                 'placeholder' => 'saisir un nom',  
                 'autocomplete' => 'fake-value' 
                ],
              'constraints' => [
                    new NotBlank([
                    'message' => 'Veuillez saisir un nom'
                    ]),
                ],
                
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J\'accepter les conditions générales d\'utilisation du site',
                'label_html' => true,
                'required' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter les conditions générales d\'utilisation du site',
                    ]),
                ],
            ])
            
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,

                'invalid_message' => 'Les mots de passes ne sont pas identiques.',

                'options' => [

                    'constraints' => [
                        new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe',
                        ]),
                        new Regex([  
                       'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_?.]).{12,}$/',
                       'match' => true,
                        'message' => 'Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractères spéciaux suivants : -+!*$@%_?.',
                        
                        ]),
                    ]
                ],
                'mapped' => false,
                'required' => false,
                'first_options'  => [
                    'label' => 'Mot de passe<span class="text-danger">*</span>',
                    'label_html' => true,
                    'attr' => [
                    'placeholder' => 'Saisir un mot de passe',
                    ],
                    'help' => 'Votre mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractères spéciaux suivants : <span class="text-primary"> -+!*$@%_?.</span>', 
                    'help_html' => true,
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe<span class="text-danger">*</span>',
                    'label_html' => true, 
                    'attr' => [
                        'placeholder' => 'Confirmer votre mot de passe',
                    ],  
                ],
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
