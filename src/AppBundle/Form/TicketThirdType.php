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

class TicketThirdType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Récupération de l'interface de session et de l'entityManager
        $session = new Session();

        // Récupération du tarif
        $order = $session->get('CommandeLouvre');

        if (!$rateSession->getRate()) {
            $order->setRate('5');
            $order->setPrice('10');
        }

        // Formulaire
        $builder
            ->remove('name')
            ->remove('lastName')
            ->remove('age')
            ->remove('country')
            ->remove('rate')
            ->add('rate', HiddenType::class, array(
                'data' => ''
            ))
            ->add('price', HiddenType::class, array(
                'data' => ''
            ))
            ->remove('visitDate')
            ->remove('duration')
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
        return 'appbundle_ticketthrid';
    }


}
