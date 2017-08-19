<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

        // Défintion de la date du jour pour l'autoremplissage du champ visitDate
        if ($session->get('visitDate') != null) {
            $dateSession = $session->get('visitDate');
            $date = new \DateTime($dateSession);
        } else {
            $date = new \DateTime();
        }

        // Formulaire
        $builder
            ->add('name')
            ->add('lastName')
            ->add('age')
            ->add('country', CountryType::class, array(
                'data' => 'FR'
            ))
            ->add('visitDate', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd-MM-y',
                'data' => $date
            ))
            ->add('duration', ChoiceType::class, array(
                'choices' => array(
                    'Journée' => 'journée',
                    'Demi-journée' => 'demi-journée'),
                'data' => $session->get('duration'),
                'expanded' => true
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
