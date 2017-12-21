<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Unit
 *
 * @ORM\Table(name="unit")
 * @ORM\Entity
 */
class Unit
{
    /**
     * @var integer
     *
     * @ORM\Column(name="TYPE_UNIT", type="integer", nullable=true)
     */
    private $typeUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="SYMBOL", type="string", length=20, nullable=true)
     */
    private $symbol;

    /**
     * @var float
     *
     * @ORM\Column(name="COEFF_A", type="float", precision=10, scale=0, nullable=true)
     */
    private $coeffA;

    /**
     * @var float
     *
     * @ORM\Column(name="COEFF_B", type="float", precision=10, scale=0, nullable=true)
     */
    private $coeffB;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_UNIT", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUnit;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Ln2user", mappedBy="idUnit")
     */
    private $idUser;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idUser = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * @return integer
     */
    public function getTypeUnit()
    {
        return $this->typeUnit;
    }

    /**
     * @param integer $typeUnit
     *
     * @return self
     */
    public function setTypeUnit($typeUnit)
    {
        $this->typeUnit = $typeUnit;

        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     *
     * @return self
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return float
     */
    public function getCoeffA()
    {
        return $this->coeffA;
    }

    /**
     * @param float $coeffA
     *
     * @return self
     */
    public function setCoeffA($coeffA)
    {
        $this->coeffA = $coeffA;

        return $this;
    }

    /**
     * @return float
     */
    public function getCoeffB()
    {
        return $this->coeffB;
    }

    /**
     * @param float $coeffB
     *
     * @return self
     */
    public function setCoeffB($coeffB)
    {
        $this->coeffB = $coeffB;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdUnit()
    {
        return $this->idUnit;
    }

    /**
     * @param integer $idUnit
     *
     * @return self
     */
    public function setIdUnit($idUnit)
    {
        $this->idUnit = $idUnit;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $idUser
     *
     * @return self
     */
    public function setIdUser(\Doctrine\Common\Collections\Collection $idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }
}

