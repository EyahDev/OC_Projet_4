<?php

namespace Tests\AppBundle\Validator;


use AppBundle\Validator\Tickets\ContainsTicketsSold;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

abstract class TestTicketsSoldValidator extends ConstraintValidatorTestCase
{

    public function testViteFait()
    {
        $this->validator->validate(1000, new ContainsTicketsSold());
        $this->assertNoViolation();
    }

}