<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketSecondType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $session = new Session();
        $date = date_format($session->get('visitDate'), 'd/m/Y');

        // Formulaire
        $builder
            ->remove('visitDate')
            ->add('visitDate', HiddenType::class, array(
                'data' => $date
            ))
            ->remove('duration')
            ->add('duration', HiddenType::class, array(
                'data' => $session->get('duration')
            ))
            ->remove('orderCustomer')
            ->remove('save');
    }

    public function getParent()
    {
        return TicketType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ticketsecond';
    }


}
