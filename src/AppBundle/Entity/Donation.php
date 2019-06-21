<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Donation
 *
 * @ORM\Table(name="donation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DonationRepository")
 */
class Donation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedonation", type="date", nullable=false)
     */
    private $datedonation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heuredonation", type="time", nullable=false)
     */
    private $heuredonation;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="donation")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Projet",inversedBy="donation")
     */
    private $projet;


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
     * Set datedonation
     *
     * @param \DateTime $datedonation
     *
     * @return Donation
     */
    public function setDatedonation($datedonation)
    {
        $this->datedonation = $datedonation;

        return $this;
    }

    /**
     * Get datedonation
     *
     * @return \DateTime
     */
    public function getDatedonation()
    {
        return $this->datedonation;
    }

    /**
     * Set heuredonation
     *
     * @param \DateTime $heuredonation
     *
     * @return Donation
     */
    public function setHeuredonation($heuredonation)
    {
        $this->heuredonation = $heuredonation;

        return $this;
    }

    /**
     * Get heuredonation
     *
     * @return \DateTime
     */
    public function getHeuredonation()
    {
        return $this->heuredonation;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getProjet()
    {
        return $this->projet;
    }

    /**
     * @param mixed $projet
     */
    public function setProjet($projet)
    {
        $this->projet = $projet;
    }


}

