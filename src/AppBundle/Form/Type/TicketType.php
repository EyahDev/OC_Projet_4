<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Définition de la date du jour
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
            ->add('rate', CheckboxType::class, array(
                'required' => false
            ))
            ->add('visitDate', DateType::class, array(
                'widget' => 'single_text',
                'data' => $date
            ))
            ->add('duration', ChoiceType::class, array(
                'choices' => array(
                    'Journée' => 'journée',
                    'Demi-journée' => 'demi-journée'),
                'expanded' => true,
                'multiple' => false
            ))
            ->add('orderCustomer', OrderCustomerType::class)
            ->add('save', SubmitType::class);
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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ticket';
    }


}
