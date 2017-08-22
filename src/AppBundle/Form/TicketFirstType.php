<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TicketFirstType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('name')
            ->remove('lastName')
            ->remove('age')
            ->remove('country')
            ->remove('rate')
            ->remove('price')
            ->remove('orderCustomer')
            ->add('orderCustomer', OrderCustomerFirstType::class);
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
        return 'appbundle_ticketfirst';
    }


}
