<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Création d'une interface de session
        $session = new Session();

        // Date du jour
        $date = new \DateTime();

        // Formulaire
        $builder
            ->add('name', TextType::class)
            ->add('lastName', TextType::class)
            ->add('age', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/y',
                'data' => $date
            ))
            ->add('country', CountryType::class, array(
                'data' => 'FR'
            ))
            ->add('visitDate', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/y',
                'data' => $date
            ))
            ->add('duration', ChoiceType::class, array(
                'choices' => array(
                    'Journée' => 'journée',
                    'Demi-journée' => 'demi-journée'),
                'data' => $session->get('CommandeLouvre')['ticket']['duration'],
                'expanded' => true
            ))
            ->add('rate', CheckboxType::class, array(
                'required' => false,
                'value' => 5,
                'data' => true
            ))
            ->add('OrderCustomer', OrderCustomerNbTicketsType::class)
            ->add('save', SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket',
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
