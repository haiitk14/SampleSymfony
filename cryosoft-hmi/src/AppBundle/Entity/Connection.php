<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Connection
 *
 * @ORM\Table(name="connection", uniqueConstraints={@ORM\UniqueConstraint(name="unique", columns={"ID_USER", "DATE_CONNECTION"})}, indexes={@ORM\Index(name="IDX_29F77366F8371B55", columns={"ID_USER"})})
 * @ORM\Entity
 */
class Connection
{
    /**
     * @var integer
     *
     * @ORM\Column(name="DATE_DISCONNECTION", type="bigint", nullable=true)
     */
    private $dateDisconnection;

    /**
     * @var integer
     *
     * @ORM\Column(name="TYPE_DISCONNECTION", type="smallint", nullable=false)
     */
    private $typeDisconnection = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="DATE_CONNECTION", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $dateConnection;

    /**
     * @var \AppBundle\Entity\Ln2user
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Ln2user")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_USER", referencedColumnName="ID_USER")
     * })
     */
    private $idUser;



    /**
     * @return integer
     */
    public function getDateDisconnection()
    {
        return $this->dateDisconnection;
    }

    /**
     * @param integer $dateDisconnection
     *
     * @return self
     */
    public function setDateDisconnection($dateDisconnection)
    {
        $this->dateDisconnection = $dateDisconnection;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTypeDisconnection()
    {
        return $this->typeDisconnection;
    }

    /**
     * @param integer $typeDisconnection
     *
     * @return self
     */
    public function setTypeDisconnection($typeDisconnection)
    {
        $this->typeDisconnection = $typeDisconnection;

        return $this;
    }

    /**
     * @return integer
     */
    public function getDateConnection()
    {
        return $this->dateConnection;
    }

    /**
     * @param integer $dateConnection
     *
     * @return self
     */
    public function setDateConnection($dateConnection)
    {
        $this->dateConnection = $dateConnection;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Ln2user
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param \AppBundle\Entity\Ln2user $idUser
     *
     * @return self
     */
    public function setIdUser(\AppBundle\Entity\Ln2user $idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }
}

