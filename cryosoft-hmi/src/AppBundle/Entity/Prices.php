<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prices
 *
 * @ORM\Table(name="prices", indexes={@ORM\Index(name="FK_PRICES_STUDIES", columns={"ID_STUDY"})})
 * @ORM\Entity
 */
class Prices
{
    /**
     * @var float
     *
     * @ORM\Column(name="ENERGY", type="float", precision=10, scale=0, nullable=true)
     */
    private $energy;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_IN_CRYO1", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoInCryo1;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_IN_PBP1", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoInPbp1;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_IN_CRYO2", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoInCryo2;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_IN_PBP2", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoInPbp2;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_IN_CRYO3", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoInCryo3;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_IN_PBP3", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoInPbp3;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_IN_CRYO4", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoInCryo4;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_IN_MINMP", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoInMinmp;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_IN_MAXMP", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoInMaxmp;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PRICE", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPrice;

    /**
     * @var \AppBundle\Entity\Studies
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Studies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_STUDY", referencedColumnName="ID_STUDY")
     * })
     */
    private $idStudy;



    /**
     * @return float
     */
    public function getEnergy()
    {
        return $this->energy;
    }

    /**
     * @param float $energy
     *
     * @return self
     */
    public function setEnergy($energy)
    {
        $this->energy = $energy;

        return $this;
    }

    /**
     * @return float
     */
    public function getEcoInCryo1()
    {
        return $this->ecoInCryo1;
    }

    /**
     * @param float $ecoInCryo1
     *
     * @return self
     */
    public function setEcoInCryo1($ecoInCryo1)
    {
        $this->ecoInCryo1 = $ecoInCryo1;

        return $this;
    }

    /**
     * @return float
     */
    public function getEcoInPbp1()
    {
        return $this->ecoInPbp1;
    }

    /**
     * @param float $ecoInPbp1
     *
     * @return self
     */
    public function setEcoInPbp1($ecoInPbp1)
    {
        $this->ecoInPbp1 = $ecoInPbp1;

        return $this;
    }

    /**
     * @return float
     */
    public function getEcoInCryo2()
    {
        return $this->ecoInCryo2;
    }

    /**
     * @param float $ecoInCryo2
     *
     * @return self
     */
    public function setEcoInCryo2($ecoInCryo2)
    {
        $this->ecoInCryo2 = $ecoInCryo2;

        return $this;
    }

    /**
     * @return float
     */
    public function getEcoInPbp2()
    {
        return $this->ecoInPbp2;
    }

    /**
     * @param float $ecoInPbp2
     *
     * @return self
     */
    public function setEcoInPbp2($ecoInPbp2)
    {
        $this->ecoInPbp2 = $ecoInPbp2;

        return $this;
    }

    /**
     * @return float
     */
    public function getEcoInCryo3()
    {
        return $this->ecoInCryo3;
    }

    /**
     * @param float $ecoInCryo3
     *
     * @return self
     */
    public function setEcoInCryo3($ecoInCryo3)
    {
        $this->ecoInCryo3 = $ecoInCryo3;

        return $this;
    }

    /**
     * @return float
     */
    public function getEcoInPbp3()
    {
        return $this->ecoInPbp3;
    }

    /**
     * @param float $ecoInPbp3
     *
     * @return self
     */
    public function setEcoInPbp3($ecoInPbp3)
    {
        $this->ecoInPbp3 = $ecoInPbp3;

        return $this;
    }

    /**
     * @return float
     */
    public function getEcoInCryo4()
    {
        return $this->ecoInCryo4;
    }

    /**
     * @param float $ecoInCryo4
     *
     * @return self
     */
    public function setEcoInCryo4($ecoInCryo4)
    {
        $this->ecoInCryo4 = $ecoInCryo4;

        return $this;
    }

    /**
     * @return float
     */
    public function getEcoInMinmp()
    {
        return $this->ecoInMinmp;
    }

    /**
     * @param float $ecoInMinmp
     *
     * @return self
     */
    public function setEcoInMinmp($ecoInMinmp)
    {
        $this->ecoInMinmp = $ecoInMinmp;

        return $this;
    }

    /**
     * @return float
     */
    public function getEcoInMaxmp()
    {
        return $this->ecoInMaxmp;
    }

    /**
     * @param float $ecoInMaxmp
     *
     * @return self
     */
    public function setEcoInMaxmp($ecoInMaxmp)
    {
        $this->ecoInMaxmp = $ecoInMaxmp;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdPrice()
    {
        return $this->idPrice;
    }

    /**
     * @param integer $idPrice
     *
     * @return self
     */
    public function setIdPrice($idPrice)
    {
        $this->idPrice = $idPrice;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Studies
     */
    public function getIdStudy()
    {
        return $this->idStudy;
    }

    /**
     * @param \AppBundle\Entity\Studies $idStudy
     *
     * @return self
     */
    public function setIdStudy(\AppBundle\Entity\Studies $idStudy)
    {
        $this->idStudy = $idStudy;

        return $this;
    }
}

