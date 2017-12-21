<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudyEquipments
 *
 * @ORM\Table(name="study_equipments", indexes={@ORM\Index(name="FK_STUDY_EQUIPMENTS_STUDIES", columns={"ID_STUDY"}), @ORM\Index(name="FK_STUDY_EQUIPMENTS_EQUIPMENT", columns={"ID_EQUIP"})})
 * @ORM\Entity
 */
class StudyEquipments
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_EXH_GEN", type="integer", nullable=true)
     */
    private $idExhGen;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_EXH_RES", type="integer", nullable=true)
     */
    private $idExhRes;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PIPE_GEN", type="integer", nullable=true)
     */
    private $idPipeGen;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PIPE_RES", type="integer", nullable=true)
     */
    private $idPipeRes;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_ECONOMIC_RESULTS", type="integer", nullable=true)
     */
    private $idEconomicResults;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_STUD_EQUIPPROFILE", type="integer", nullable=true)
     */
    private $idStudEquipprofile;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_LAYOUT_GENERATION", type="integer", nullable=true)
     */
    private $idLayoutGeneration;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_LAYOUT_RESULTS", type="integer", nullable=true)
     */
    private $idLayoutResults;

    /**
     * @var integer
     *
     * @ORM\Column(name="LINE_ORDER", type="smallint", nullable=true)
     */
    private $lineOrder;

    /**
     * @var float
     *
     * @ORM\Column(name="STDEQP_LENGTH", type="float", precision=10, scale=0, nullable=true)
     */
    private $stdeqpLength;

    /**
     * @var float
     *
     * @ORM\Column(name="STDEQP_WIDTH", type="float", precision=10, scale=0, nullable=true)
     */
    private $stdeqpWidth;

    /**
     * @var float
     *
     * @ORM\Column(name="EQP_INST", type="float", precision=10, scale=0, nullable=true)
     */
    private $eqpInst;

    /**
     * @var float
     *
     * @ORM\Column(name="AVERAGE_PRODUCT_TEMP", type="float", precision=10, scale=0, nullable=true)
     */
    private $averageProductTemp;

    /**
     * @var float
     *
     * @ORM\Column(name="AVERAGE_PRODUCT_ENTHALPY", type="float", precision=10, scale=0, nullable=true)
     */
    private $averageProductEnthalpy;

    /**
     * @var float
     *
     * @ORM\Column(name="ENTHALPY_VARIATION", type="float", precision=10, scale=0, nullable=true)
     */
    private $enthalpyVariation;

    /**
     * @var float
     *
     * @ORM\Column(name="PRECIS", type="float", precision=10, scale=0, nullable=true)
     */
    private $precis;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB_MODUL", type="smallint", nullable=true)
     */
    private $nbModul;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STACKING_WARNING", type="boolean", nullable=true)
     */
    private $stackingWarning;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ENABLE_CONS_PIE", type="boolean", nullable=true)
     */
    private $enableConsPie;

    /**
     * @var integer
     *
     * @ORM\Column(name="EQUIP_STATUS", type="integer", nullable=true)
     */
    private $equipStatus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="RUN_CALCULATE", type="boolean", nullable=true)
     */
    private $runCalculate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="BRAIN_SAVETODB", type="boolean", nullable=true)
     */
    private $brainSavetodb;

    /**
     * @var integer
     *
     * @ORM\Column(name="BRAIN_TYPE", type="integer", nullable=true)
     */
    private $brainType;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_STUDY_EQUIPMENTS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStudyEquipments;

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
     * @var \AppBundle\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_EQUIP", referencedColumnName="ID_EQUIP")
     * })
     */
    private $idEquip;


    /**
     * @var \AppBundle\Entity\CalculationParameters
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CalculationParameters")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_CALC_PARAMS", referencedColumnName="ID_CALC_PARAMS")
     * })
     */
    private $idCalcParams;
        
    /**
     * @return integer
     */
    public function getIdExhGen()
    {
        return $this->idExhGen;
    }

    /**
     * @param integer $idExhGen
     *
     * @return self
     */
    public function setIdExhGen($idExhGen)
    {
        $this->idExhGen = $idExhGen;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdExhRes()
    {
        return $this->idExhRes;
    }

    /**
     * @param integer $idExhRes
     *
     * @return self
     */
    public function setIdExhRes($idExhRes)
    {
        $this->idExhRes = $idExhRes;

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
     * @return integer
     */
    public function getIdPipeRes()
    {
        return $this->idPipeRes;
    }

    /**
     * @param integer $idPipeRes
     *
     * @return self
     */
    public function setIdPipeRes($idPipeRes)
    {
        $this->idPipeRes = $idPipeRes;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdEconomicResults()
    {
        return $this->idEconomicResults;
    }

    /**
     * @param integer $idEconomicResults
     *
     * @return self
     */
    public function setIdEconomicResults($idEconomicResults)
    {
        $this->idEconomicResults = $idEconomicResults;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdStudEquipprofile()
    {
        return $this->idStudEquipprofile;
    }

    /**
     * @param integer $idStudEquipprofile
     *
     * @return self
     */
    public function setIdStudEquipprofile($idStudEquipprofile)
    {
        $this->idStudEquipprofile = $idStudEquipprofile;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdLayoutGeneration()
    {
        return $this->idLayoutGeneration;
    }

    /**
     * @param integer $idLayoutGeneration
     *
     * @return self
     */
    public function setIdLayoutGeneration($idLayoutGeneration)
    {
        $this->idLayoutGeneration = $idLayoutGeneration;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdLayoutResults()
    {
        return $this->idLayoutResults;
    }

    /**
     * @param integer $idLayoutResults
     *
     * @return self
     */
    public function setIdLayoutResults($idLayoutResults)
    {
        $this->idLayoutResults = $idLayoutResults;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdCalcParams()
    {
        return $this->idCalcParams;
    }


    /**
     * @param integer $idCalcParams
     *
     * @return self
     */
    public function setIdCalcParams($idCalcParams)
    {
        $this->idCalcParams = $idCalcParams;

        return $this;
    }

    /**
     * @return integer
     */
    public function getLineOrder()
    {
        return $this->lineOrder;
    }

    /**
     * @param integer $lineOrder
     *
     * @return self
     */
    public function setLineOrder($lineOrder)
    {
        $this->lineOrder = $lineOrder;

        return $this;
    }

    /**
     * @return float
     */
    public function getStdeqpLength()
    {
        return $this->stdeqpLength;
    }

    /**
     * @param float $stdeqpLength
     *
     * @return self
     */
    public function setStdeqpLength($stdeqpLength)
    {
        $this->stdeqpLength = $stdeqpLength;

        return $this;
    }

    /**
     * @return float
     */
    public function getStdeqpWidth()
    {
        return $this->stdeqpWidth;
    }

    /**
     * @param float $stdeqpWidth
     *
     * @return self
     */
    public function setStdeqpWidth($stdeqpWidth)
    {
        $this->stdeqpWidth = $stdeqpWidth;

        return $this;
    }

    /**
     * @return float
     */
    public function getEqpInst()
    {
        return $this->eqpInst;
    }

    /**
     * @param float $eqpInst
     *
     * @return self
     */
    public function setEqpInst($eqpInst)
    {
        $this->eqpInst = $eqpInst;

        return $this;
    }

    /**
     * @return float
     */
    public function getAverageProductTemp()
    {
        return $this->averageProductTemp;
    }

    /**
     * @param float $averageProductTemp
     *
     * @return self
     */
    public function setAverageProductTemp($averageProductTemp)
    {
        $this->averageProductTemp = $averageProductTemp;

        return $this;
    }

    /**
     * @return float
     */
    public function getAverageProductEnthalpy()
    {
        return $this->averageProductEnthalpy;
    }

    /**
     * @param float $averageProductEnthalpy
     *
     * @return self
     */
    public function setAverageProductEnthalpy($averageProductEnthalpy)
    {
        $this->averageProductEnthalpy = $averageProductEnthalpy;

        return $this;
    }

    /**
     * @return float
     */
    public function getEnthalpyVariation()
    {
        return $this->enthalpyVariation;
    }

    /**
     * @param float $enthalpyVariation
     *
     * @return self
     */
    public function setEnthalpyVariation($enthalpyVariation)
    {
        $this->enthalpyVariation = $enthalpyVariation;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrecis()
    {
        return $this->precis;
    }

    /**
     * @param float $precis
     *
     * @return self
     */
    public function setPrecis($precis)
    {
        $this->precis = $precis;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbModul()
    {
        return $this->nbModul;
    }

    /**
     * @param integer $nbModul
     *
     * @return self
     */
    public function setNbModul($nbModul)
    {
        $this->nbModul = $nbModul;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStackingWarning()
    {
        return $this->stackingWarning;
    }

    /**
     * @param boolean $stackingWarning
     *
     * @return self
     */
    public function setStackingWarning($stackingWarning)
    {
        $this->stackingWarning = $stackingWarning;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableConsPie()
    {
        return $this->enableConsPie;
    }

    /**
     * @param boolean $enableConsPie
     *
     * @return self
     */
    public function setEnableConsPie($enableConsPie)
    {
        $this->enableConsPie = $enableConsPie;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEquipStatus()
    {
        return $this->equipStatus;
    }

    /**
     * @param integer $equipStatus
     *
     * @return self
     */
    public function setEquipStatus($equipStatus)
    {
        $this->equipStatus = $equipStatus;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRunCalculate()
    {
        return $this->runCalculate;
    }

    /**
     * @param boolean $runCalculate
     *
     * @return self
     */
    public function setRunCalculate($runCalculate)
    {
        $this->runCalculate = $runCalculate;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isBrainSavetodb()
    {
        return $this->brainSavetodb;
    }

    /**
     * @param boolean $brainSavetodb
     *
     * @return self
     */
    public function setBrainSavetodb($brainSavetodb)
    {
        $this->brainSavetodb = $brainSavetodb;

        return $this;
    }

    /**
     * @return integer
     */
    public function getBrainType()
    {
        return $this->brainType;
    }

    /**
     * @param integer $brainType
     *
     * @return self
     */
    public function setBrainType($brainType)
    {
        $this->brainType = $brainType;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdStudyEquipments()
    {
        return $this->idStudyEquipments;
    }

    /**
     * @param integer $idStudyEquipments
     *
     * @return self
     */
    public function setIdStudyEquipments($idStudyEquipments)
    {
        $this->idStudyEquipments = $idStudyEquipments;

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

    /**
     * @return \AppBundle\Entity\Equipment
     */
    public function getIdEquip()
    {
        return $this->idEquip;
    }

    /**
     * @param \AppBundle\Entity\Equipment $idEquip
     *
     * @return self
     */
    public function setIdEquip(\AppBundle\Entity\Equipment $idEquip)
    {
        $this->idEquip = $idEquip;

        return $this;
    }

// add element display table equipment  
    private $studyEquipName;
     public function getStudyEquipName()
    {
        return $this->studyEquipName;
    }
    public function setStudyEquipName($studyEquipName)
    {
        $this->studyEquipName = $studyEquipName;
        return $this;
    }
    private $checkModalName;
    public function getCheckModalName()
    {
        return $this->checkModalName;
    }
    public function setCheckModalName($checkModalName)
    {
        $this->checkModalName = $checkModalName;
        return $this;
    }

    private $orientation;
     public function getOrientation()
    {
        return $this->orientation;
    }
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
        return $this;
    }
    private $checkModalOrientation;
    public function getCheckModalOrientation()
    {
        return $this->checkModalOrientation;
    }
    public function setCheckModalOrientation($checkModalOrientation)
    {
        $this->checkModalOrientation = $checkModalOrientation;
        return $this;
    }

    private $tr;
    public function getTR(){
        return $this->tr;
    }
    public function setTR($tr){
        $this->tr=$tr;
        return $this;
    }

    private $ts;
    public function getTS(){
        return $this->ts;
    }
    public function setTS($ts){
        $this->ts=$ts;
        return $this;
    }

    private $vc;
    public function getVC(){
        return $this->vc;
    }
    public function setVC($vc){
        $this->vc=$vc;
        return $this;
    }
// column Conveyor coverage or quantity of product per batch:
    private $conveyor;
    public function getConveyor(){
        return $this->conveyor;
    }
    public function setConveyor($conveyor){
        $this->conveyor=$conveyor;
        return $this;
    }
    private $checkModalConveyor;
    public function getCheckModalConveyor()
    {
        return $this->checkModalConveyor;
    }
    public function setCheckModalConveyor($checkModalConveyor)
    {
        $this->checkModalConveyor = $checkModalConveyor;
        return $this;
    }
    private $batch;
    public function isBatch()
    {
        return $this->batch;
    }
    
    public function setBatch($batCh)
    {
        $this->batch = $batCh;
        return $this;
    }
}

