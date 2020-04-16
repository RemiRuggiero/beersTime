<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Nom de l\'événement',
                )
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description',
                'attr' => array(
                    'rows' => 5,
                )
            ))
            ->add('pictureFile', FileType::class, array(
                'label' => 'Image',
                'help' => 'Image PNG ou JPEG inférieure à 2M',
            ))
            ->add('startAt', null, array(
                'label' => 'Début de l\'événement',
                'widget' => 'single_text',
            ))
            ->add('endAt', null, array(
                'label' => 'Fin de l\'événement',
                'widget' => 'single_text',
            ))
            ->add('price', null, array(
                'label' => 'Prix',
                'help' => "Pour un événement gratuit laisser le champ vide",
            ))
            ->add('capacity', null, array(
                'label' => 'Capacité',
                'help' => "Pour un événement ilimité en terme de capacité laisser le champ vide",
            ))
            ->add('place', null, array(
                'label' => 'Lieu',
                'choice_label' => 'name',
                'help' => "Seul les lieux partenaires peuvent accueillir des événements",
            ))
            ->add('categories', null, array(
                'choice_label' => 'name',
                'expanded' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}