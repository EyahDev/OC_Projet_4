<?php

namespace AppBundle\Entity;

use AppBundle\Validator as CustomAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OrderCustomer
 *
 * @ORM\Table(name="order_customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderCustomerRepository")
 */
class OrderCustomer
{
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="orderCustomer", cascade={"persist"})
     * @Assert\Valid()
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
     * @Assert\NotBlank(message="validator.step.1.mail.not_blank", groups={"step_1"})
     * @Assert\Email(message="validator.step.1.mail.valid", checkMX=true, groups={"step_1"})
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="nbTickets", type="integer")
     * @Assert\NotBlank(message="validator.step.1.tickets.not_blank", groups={"step_1"})
     * @Assert\GreaterThanOrEqual(
     *     value = 1,
     *     message="validator.step.1.tickets.greater",
     *     groups={"step_1"}
 *     )
     * @Assert\LessThanOrEqual(
     *     value = 10,
     *     message = "validator.step.1.tickets.lesser",
     *     groups={"step_1"}
 *     )
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
     * @var \DateTime
     *
     * @ORM\Column(name="visitDate", type="datetime")
     * @Assert\NotBlank( message = "validator.step.1.visit.date.not_blank", groups={"step_1"})
     * @Assert\GreaterThanOrEqual("today", message = "validator.step.1.visit.date.greater", groups={"step_1"})
     * @CustomAssert\VisitDate\ContainsVisitDateDimanche(groups={"step_1"})
     * @CustomAssert\VisitDate\ContainsVisitDateFerie(groups={"step_1"})
     * @CustomAssert\VisitDate\ContainsVisitCloseFeries(groups={"step_1"})
     * @CustomAssert\VisitDate\ContainsVisitDateCloseHours(groups={"step_1"})
     * @CustomAssert\Tickets\ContainsTicketsSold(groups={"step_1"})
     */
    private $visitDate;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=255)
     * @Assert\NotBlank(message = "validator.step.1.duration.not_blank", groups={"step_1"})
     * @CustomAssert\Duration\ContainsDuration(groups={"step_1"})
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="access", type="string", length=255)
     */
    private $access;


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
        $this->visitDate = new \DateTime();
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
        $ticket->setOrderCustomer($this);

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

    /**
     * Set visitDate
     *
     * @param \DateTime $visitDate
     *
     * @return OrderCustomer
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set duration
     *
     * @param string $duration
     *
     * @return OrderCustomer
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set access
     *
     * @param string $access
     *
     * @return OrderCustomer
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }
}
