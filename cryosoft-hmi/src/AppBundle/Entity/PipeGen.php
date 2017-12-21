<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PipeGen
 *
 * @ORM\Table(name="pipe_gen", indexes={@ORM\Index(name="FK_PIPE_GEN_STUDY_EQUIPMENTS", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class PipeGen
{
    /**
     * @var float
     *
     * @ORM\Column(name="INSULLINE_LENGHT", type="float", precision=24, scale=0, nullable=true)
     */
    private $insullineLenght;

    /**
     * @var float
     *
     * @ORM\Column(name="NOINSULLINE_LENGHT", type="float", precision=24, scale=0, nullable=true)
     */
    private $noinsullineLenght;

    /**
     * @var integer
     *
     * @ORM\Column(name="ELBOWS", type="integer", nullable=true)
     */
    private $elbows;

    /**
     * @var integer
     *
     * @ORM\Column(name="TEES", type="integer", nullable=true)
     */
    private $tees;

    /**
     * @var integer
     *
     * @ORM\Column(name="INSUL_VALVES", type="integer", nullable=false)
     */
    private $insulValves;

    /**
     * @var integer
     *
     * @ORM\Column(name="NOINSUL_VALVES", type="integer", nullable=true)
     */
    private $noinsulValves;

    /**
     * @var boolean
     *
     * @ORM\Column(name="MATHIGHER", type="boolean", nullable=false)
     */
    private $mathigher;

    /**
     * @var float
     *
     * @ORM\Column(name="HEIGHT", type="float", precision=24, scale=0, nullable=true)
     */
    private $height;

    /**
     * @var integer
     *
     * @ORM\Column(name="FLUID", type="integer", nullable=true)
     */
    private $fluid;

    /**
     * @var float
     *
     * @ORM\Column(name="PRESSURE", type="float", precision=10, scale=0, nullable=true)
     */
    private $pressure;

    /**
     * @var float
     *
     * @ORM\Column(name="GAS_TEMP", type="float", precision=24, scale=0, nullable=true)
     */
    private $gasTemp;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PIPE_GEN", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPipeGen;

    /**
     * @var \AppBundle\Entity\StudyEquipments
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\StudyEquipments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_STUDY_EQUIPMENTS", referencedColumnName="ID_STUDY_EQUIPMENTS")
     * })
     */
    private $idStudyEquipments;

    

    /**
     * @return float
     */
    public function getInsullineLenght()
    {
        return $this->insullineLenght;
    }

    /**
     * @param float $insullineLenght
     *
     * @return self
     */
    public function setInsullineLenght($insullineLenght)
    {
        $this->insullineLenght = $insullineLenght;

        return $this;
    }

    /**
     * @return float
     */
    public function getNoinsullineLenght()
    {
        return $this->noinsullineLenght;
    }

    /**
     * @param float $noinsullineLenght
     *
     * @return self
     */
    public function setNoinsullineLenght($noinsullineLenght)
    {
        $this->noinsullineLenght = $noinsullineLenght;

        return $this;
    }

    /**
     * @return integer
     */
    public function getElbows()
    {
        return $this->elbows;
    }

    /**
     * @param integer $elbows
     *
     * @return self
     */
    public function setElbows($elbows)
    {
        $this->elbows = $elbows;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTees()
    {
        return $this->tees;
    }

    /**
     * @param integer $tees
     *
     * @return self
     */
    public function setTees($tees)
    {
        $this->tees = $tees;

        return $this;
    }

    /**
     * @return integer
     */
    public function getInsulValves()
    {
        return $this->insulValves;
    }

    /**
     * @param integer $insulValves
     *
     * @return self
     */
    public function setInsulValves($insulValves)
    {
        $this->insulValves = $insulValves;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNoinsulValves()
    {
        return $this->noinsulValves;
    }

    /**
     * @param integer $noinsulValves
     *
     * @return self
     */
    public function setNoinsulValves($noinsulValves)
    {
        $this->noinsulValves = $noinsulValves;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isMathigher()
    {
        return $this->mathigher;
    }

    /**
     * @param boolean $mathigher
     *
     * @return self
     */
    public function setMathigher($mathigher)
    {
        $this->mathigher = $mathigher;

        return $this;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     *
     * @return self
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return integer
     */
    public function getFluid()
    {
        return $this->fluid;
    }

    /**
     * @param integer $fluid
     *
     * @return self
     */
    public function setFluid($fluid)
    {
        $this->fluid = $fluid;

        return $this;
    }

    /**
     * @return float
     */
    public function getPressure()
    {
        return $this->pressure;
    }

    /**
     * @param float $pressure
     *
     * @return self
     */
    public function setPressure($pressure)
    {
        $this->pressure = $pressure;

        return $this;
    }

    /**
     * @return float
     */
    public function getGasTemp()
    {
        return $this->gasTemp;
    }

    /**
     * @param float $gasTemp
     *
     * @return self
     */
    public function setGasTemp($gasTemp)
    {
        $this->gasTemp = $gasTemp;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdPipeGen()
    {
        return $this->idPipeGen;
    }

    /**
     * @param integer $idPipeGen
     *
     * @return self
     */
    public function setIdPipeGen($idPipeGen)
    {
        $this->idPipeGen = $idPipeGen;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\StudyEquipments
     */
    public function getIdStudyEquipments()
    {
        return $this->idStudyEquipments;
    }

    /**
     * @param \AppBundle\Entity\StudyEquipments $idStudyEquipments
     *
     * @return self
     */
    public function setIdStudyEquipments(\AppBundle\Entity\StudyEquipments $idStudyEquipments)
    {
        $this->idStudyEquipments = $idStudyEquipments;

        return $this;
    }
}

