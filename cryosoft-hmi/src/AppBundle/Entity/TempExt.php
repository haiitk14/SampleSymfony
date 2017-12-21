<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TempExt
 *
 * @ORM\Table(name="temp_ext", indexes={@ORM\Index(name="FK_TEMP_EXT_EQUIPSERIES", columns={"ID_EQUIPSERIES"})})
 * @ORM\Entity
 */
class TempExt
{
    /**
     * @var float
     *
     * @ORM\Column(name="TR", type="float", precision=10, scale=0, nullable=true)
     */
    private $tr;

    /**
     * @var float
     *
     * @ORM\Column(name="T_EXT", type="float", precision=10, scale=0, nullable=true)
     */
    private $tExt;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_TEMP_EXT", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTempExt;

    /**
     * @var \AppBundle\Entity\Equipseries
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equipseries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_EQUIPSERIES", referencedColumnName="ID_EQUIPSERIES")
     * })
     */
    private $idEquipseries;

    

    /**
     * @return float
     */
    public function getTr()
    {
        return $this->tr;
    }

    /**
     * @param float $tr
     *
     * @return self
     */
    public function setTr($tr)
    {
        $this->tr = $tr;

        return $this;
    }

    /**
     * @return float
     */
    public function getExt()
    {
        return $this->tExt;
    }

    /**
     * @param float $tExt
     *
     * @return self
     */
    public function setExt($tExt)
    {
        $this->tExt = $tExt;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdTempExt()
    {
        return $this->idTempExt;
    }

    /**
     * @param integer $idTempExt
     *
     * @return self
     */
    public function setIdTempExt($idTempExt)
    {
        $this->idTempExt = $idTempExt;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Equipseries
     */
    public function getIdEquipseries()
    {
        return $this->idEquipseries;
    }

    /**
     * @param \AppBundle\Entity\Equipseries $idEquipseries
     *
     * @return self
     */
    public function setIdEquipseries(\AppBundle\Entity\Equipseries $idEquipseries)
    {
        $this->idEquipseries = $idEquipseries;

        return $this;
    }
}

