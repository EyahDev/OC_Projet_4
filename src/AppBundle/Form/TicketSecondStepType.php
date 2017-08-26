<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketSecondStepType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new \DateTime();

        // Formulaire
        $builder
            ->add('name',TextType::class)
            ->add('lastName', TextType::class)
            ->add('age', DateType::class, array(
                'widget' => "single_text",
                'format' => 'dd/MM/y',
                'data' => $date
            ))
            ->add('country', CountryType::class, array(
                'data' => 'FR'
            ))
            ->add('reduced_price', CheckboxType::class, array(
                'required' => false
            ));
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket'
        ));
    }
}
