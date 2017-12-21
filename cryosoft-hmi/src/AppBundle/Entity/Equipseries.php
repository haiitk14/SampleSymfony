<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipseries
 *
 * @ORM\Table(name="equipseries", indexes={@ORM\Index(name="FK_EQUIPSERIES_EQUIPFAMILY", columns={"ID_FAMILY"})})
 * @ORM\Entity
 */
class Equipseries
{
    /**
     * @var string
     *
     * @ORM\Column(name="SERIES_NAME", type="string", length=255, nullable=true)
     */
    private $seriesName;

    /**
     * @var string
     *
     * @ORM\Column(name="CONSTRUCTOR", type="string", length=32, nullable=true)
     */
    private $constructor;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_EQUIPSERIES", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEquipseries;

    /**
     * @var \AppBundle\Entity\Equipfamily
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equipfamily")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_FAMILY", referencedColumnName="ID_FAMILY")
     * })
     */
    private $idFamily;



    /**
     * @return string
     */
    public function getSeriesName()
    {
        return $this->seriesName;
    }

    /**
     * @param string $seriesName
     *
     * @return self
     */
    public function setSeriesName($seriesName)
    {
        $this->seriesName = $seriesName;

        return $this;
    }

    /**
     * @return string
     */
    public function getConstructor()
    {
        return $this->constructor;
    }

    /**
     * @param string $constructor
     *
     * @return self
     */
    public function setConstructor($constructor)
    {
        $this->constructor = $constructor;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdEquipseries()
    {
        return $this->idEquipseries;
    }

    /**
     * @param integer $idEquipseries
     *
     * @return self
     */
    public function setIdEquipseries($idEquipseries)
    {
        $this->idEquipseries = $idEquipseries;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Equipfamily
     */
    public function getIdFamily()
    {
        return $this->idFamily;
    }

    /**
     * @param \AppBundle\Entity\Equipfamily $idFamily
     *
     * @return self
     */
    public function setIdFamily(\AppBundle\Entity\Equipfamily $idFamily)
    {
        $this->idFamily = $idFamily;

        return $this;
    }
}

