<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterGamesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('name',TextType::class,[
                'label' => "Nom du jeu",
                'required' => false,
                'attr'=>[
                    'placeholder' => 'Rechercher un jeu'
                ]
            ])
            ->add('price_max',IntegerType::class,[
                'label' => false,
                'required' => false,
                'attr'=>[
                    'placeholder' => 'Prix Max'
                ]
            ])
            ->add('price_min',IntegerType::class,[
                'label' => false,
                'required' => false,
                'attr'=>[
                    'placeholder' => 'Prix mini'
                ]
            ])
            ->add('genres',EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr'=>[
                    'class' => 'checkbox_content'
                ]
            ])
            ->add('published_at',DateType::class,[
                'label' => "Date de sortie",
                'required' => false,
                'widget' => 'single_text',
                
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
                'attr' => [
                    'class' => 'btn btn-steamish'
            ]
        ])
        // Prix max recherché
            // ->add('price')
        // Langue recherché
            // ->add('countries')
        // Genre recherché 
            // ->add('genres')
        // Editeur recherché 
            // ->add('publisher')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}
