<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderCustomerNbTicketsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('email')
            ->remove('price')
            ->remove('orderToken')
            ->remove('orderDate');
    }

    public function getParent()
    {
        return OrderCustomerType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ordercustomernbtickets';
    }


}
