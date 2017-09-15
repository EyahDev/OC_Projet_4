<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OrderCustomer", inversedBy="tickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderCustomer;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Regex(
     *     pattern = "/\d/",
     *     match = false,
     *     message = "validator.step.2.name.string"
     * )
     * @Assert\NotBlank(message = "validator.step.2.name.not_blank")
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "validator.step.2.name.short",
     *     maxMessage = "validator.step.2.name.long"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     *
     * @Assert\Regex(
     *     pattern = "/\d/",
     *     match = false,
     *     message = "validator.step.2.lastname.string"
     * )
     * @Assert\NotBlank(message = "validator.step.2.lastname.not_blank")
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "validator.step.2.lastname.short",
     *     maxMessage = "validator.step.2.lastname.long"
     * )
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="age", type="datetime")
     * @Assert\NotBlank(message = "validator.step.2.age.not_blank")
     * @Assert\Valid
     * @Assert\LessThan("today", message = "validator.step.2.age.lesser")
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank(message = "validator.step.2.country.not_blank")
     * @Assert\Country(message = "validator.step.2.country.country")
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="rate", type="string", length=255)
     */
    private $rate;

    /**
     * @var bool
     *
     * @ORM\Column(name="reduced_price", type="boolean")
     */
    private $reducedPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="token_ticket", type="string", length=255)
     */
    private $tokenTicket;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Ticket
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Ticket
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Ticket
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set rate
     *
     * @param string $rate
     *
     * @return Ticket
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * Set tickets
     *
     * @param \AppBundle\Entity\OrderCustomer $tickets
     *
     * @return Ticket
     */
    public function setTickets(\AppBundle\Entity\OrderCustomer $tickets = null)
    {
        $this->tickets = $tickets;

        return $this;
    }

    /**
     * Get tickets
     *
     * @return \AppBundle\Entity\OrderCustomer
     */
    public function getTickets()
    {
        return $this->tickets;
    }


    /**
     * Set orderCustomer
     *
     * @param \AppBundle\Entity\OrderCustomer $orderCustomer
     *
     * @return Ticket
     */
    public function setOrderCustomer(\AppBundle\Entity\OrderCustomer $orderCustomer = null)
    {
        $this->orderCustomer = $orderCustomer;

        return $this;
    }

    /**
     * Get orderCustomer
     *
     * @return \AppBundle\Entity\OrderCustomer
     */
    public function getOrderCustomer()
    {
        return $this->orderCustomer;
    }

    /**
     * Set reducedPrice
     *
     * @param boolean $reducedPrice
     *
     * @return Ticket
     */
    public function setReducedPrice($reducedPrice)
    {
        $this->reducedPrice = $reducedPrice;

        return $this;
    }

    /**
     * Get reducedPrice
     *
     * @return boolean
     */
    public function getReducedPrice()
    {
        return $this->reducedPrice;
    }

    /**
     * Set tokenTicket
     *
     * @param string $tokenTicket
     *
     * @return Ticket
     */
    public function setTokenTicket($tokenTicket)
    {
        $this->tokenTicket = $tokenTicket;

        return $this;
    }

    /**
     * Get tokenTicket
     *
     * @return string
     */
    public function getTokenTicket()
    {
        return $this->tokenTicket;
    }
}
