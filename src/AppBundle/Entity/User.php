<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var \string
     *
     * @ORM\Column(name="dateNaissance", type="string",length=255)
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="lieuRes", type="string", length=255)
     */
    private $lieuRes;

    /**
     * @var int
     *
     * @ORM\Column(name="nbClick", type="integer", nullable=true)
     */
    private $nbClick;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="pwd", type="string", length=255)
     */
    private $pwd;



    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Donation",mappedBy="user")
     */
    private $donation;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \string $dateNaissance
     *
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \string
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set lieuRes
     *
     * @param string $lieuRes
     *
     * @return User
     */
    public function setLieuRes($lieuRes)
    {
        $this->lieuRes = $lieuRes;

        return $this;
    }

    /**
     * Get lieuRes
     *
     * @return string
     */
    public function getLieuRes()
    {
        return $this->lieuRes;
    }

    /**
     * Set nbClick
     *
     * @param integer $nbClick
     *
     * @return User
     */
    public function setNbClick($nbClick)
    {
        $this->nbClick = $nbClick;

        return $this;
    }

    /**
     * Get nbClick
     *
     * @return int
     */
    public function getNbClick()
    {
        return $this->nbClick;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
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
     * Set pwd
     *
     * @param string $pwd
     *
     * @return User
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;

        return $this;
    }

    /**
     * Get pwd
     *
     * @return string
     */
    public function getPwd()
    {
        return $this->pwd;
    }
}

