<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TicketCoordonneesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         // Formulaire
        $builder
            ->remove('visitDate')
            ->remove('duration')
            ->remove('OrderCustomer');
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
        return 'appbundle_ticketcoordonnees';
    }


}
