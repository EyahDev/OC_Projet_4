<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderCustomer
 *
 * @ORM\Table(name="order_customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderCustomerRepository")
 */
class OrderCustomer
{
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="orderCustomer")
     */
    private $tickets;

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
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="nbTickets", type="integer")
     */
    private $nbTickets;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="orderToken", type="string", length=255, unique=true)
     */
    private $orderToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="orderDate", type="datetime")
     */
    private $orderDate;


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
     * Set email
     *
     * @param string $email
     *
     * @return OrderCustomer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nbTickets
     *
     * @param integer $nbTickets
     *
     * @return OrderCustomer
     */
    public function setNbTickets($nbTickets)
    {
        $this->nbTickets = $nbTickets;

        return $this;
    }

    /**
     * Get nbTickets
     *
     * @return int
     */
    public function getNbTickets()
    {
        return $this->nbTickets;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return OrderCustomer
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
     * Set orderToken
     *
     * @param string $orderToken
     *
     * @return OrderCustomer
     */
    public function setOrderToken($orderToken)
    {
        $this->orderToken = $orderToken;

        return $this;
    }

    /**
     * Get orderToken
     *
     * @return string
     */
    public function getOrderToken()
    {
        return $this->orderToken;
    }

    /**
     * Set orderDate
     *
     * @param \DateTime $orderDate
     *
     * @return OrderCustomer
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return OrderCustomer
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}