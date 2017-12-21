<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CalculationParameters
 *
 * @ORM\Table(name="calculation_parameters", indexes={@ORM\Index(name="FK_CALCULATION_PARAMETERS_STUDY_EQUIPMENTS", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class CalculationParameters
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="HORIZ_SCAN", type="boolean", nullable=true)
     */
    private $horizScan;

    /**
     * @var boolean
     *
     * @ORM\Column(name="VERT_SCAN", type="boolean", nullable=true)
     */
    private $vertScan;

    /**
     * @var integer
     *
     * @ORM\Column(name="MAX_IT_NB", type="integer", nullable=true)
     */
    private $maxItNb;

    /**
     * @var integer
     *
     * @ORM\Column(name="TIME_STEPS_NB", type="integer", nullable=true)
     */
    private $timeStepsNb;

    /**
     * @var float
     *
     * @ORM\Column(name="RELAX_COEFF", type="float", precision=24, scale=0, nullable=true)
     */
    private $relaxCoeff;

    /**
     * @var float
     *
     * @ORM\Column(name="TIME_STEP", type="float", precision=24, scale=0, nullable=true)
     */
    private $timeStep;

    /**
     * @var integer
     *
     * @ORM\Column(name="STORAGE_STEP", type="integer", nullable=true)
     */
    private $storageStep;

    /**
     * @var integer
     *
     * @ORM\Column(name="PRECISION_LOG_STEP", type="integer", nullable=true)
     */
    private $precisionLogStep;

    /**
     * @var float
     *
     * @ORM\Column(name="STOP_TOP_SURF", type="float", precision=24, scale=0, nullable=true)
     */
    private $stopTopSurf;

    /**
     * @var float
     *
     * @ORM\Column(name="STOP_INT", type="float", precision=24, scale=0, nullable=true)
     */
    private $stopInt;

    /**
     * @var float
     *
     * @ORM\Column(name="STOP_BOTTOM_SURF", type="float", precision=24, scale=0, nullable=true)
     */
    private $stopBottomSurf;

    /**
     * @var float
     *
     * @ORM\Column(name="STOP_AVG", type="float", precision=24, scale=0, nullable=true)
     */
    private $stopAvg;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_TOP_FIXED", type="boolean", nullable=true)
     */
    private $studyAlphaTopFixed;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_TOP", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaTop;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_BOTTOM_FIXED", type="boolean", nullable=true)
     */
    private $studyAlphaBottomFixed;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_BOTTOM", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaBottom;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_LEFT_FIXED", type="boolean", nullable=true)
     */
    private $studyAlphaLeftFixed;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_LEFT", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaLeft;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_RIGHT_FIXED", type="boolean", nullable=true)
     */
    private $studyAlphaRightFixed;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_RIGHT", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaRight;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_FRONT_FIXED", type="boolean", nullable=true)
     */
    private $studyAlphaFrontFixed;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_FRONT", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaFront;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_REAR_FIXED", type="boolean", nullable=true)
     */
    private $studyAlphaRearFixed;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_REAR", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaRear;

    /**
     * @var float
     *
     * @ORM\Column(name="PRECISION_REQUEST", type="float", precision=10, scale=0, nullable=true)
     */
    private $precisionRequest;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB_OPTIM", type="integer", nullable=true)
     */
    private $nbOptim;

    /**
     * @var float
     *
     * @ORM\Column(name="ERROR_T", type="float", precision=10, scale=0, nullable=true)
     */
    private $errorT;

    /**
     * @var float
     *
     * @ORM\Column(name="ERROR_H", type="float", precision=10, scale=0, nullable=true)
     */
    private $errorH;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_CALC_PARAMS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCalcParams;

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
     * @return boolean
     */
    public function isHorizScan()
    {
        return $this->horizScan;
    }

    /**
     * @param boolean $horizScan
     *
     * @return self
     */
    public function setHorizScan($horizScan)
    {
        $this->horizScan = $horizScan;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isVertScan()
    {
        return $this->vertScan;
    }

    /**
     * @param boolean $vertScan
     *
     * @return self
     */
    public function setVertScan($vertScan)
    {
        $this->vertScan = $vertScan;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMaxItNb()
    {
        return $this->maxItNb;
    }

    /**
     * @param integer $maxItNb
     *
     * @return self
     */
    public function setMaxItNb($maxItNb)
    {
        $this->maxItNb = $maxItNb;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTimeStepsNb()
    {
        return $this->timeStepsNb;
    }

    /**
     * @param integer $timeStepsNb
     *
     * @return self
     */
    public function setTimeStepsNb($timeStepsNb)
    {
        $this->timeStepsNb = $timeStepsNb;

        return $this;
    }

    /**
     * @return float
     */
    public function getRelaxCoeff()
    {
        return $this->relaxCoeff;
    }

    /**
     * @param float $relaxCoeff
     *
     * @return self
     */
    public function setRelaxCoeff($relaxCoeff)
    {
        $this->relaxCoeff = $relaxCoeff;

        return $this;
    }

    /**
     * @return float
     */
    public function getTimeStep()
    {
        return $this->timeStep;
    }

    /**
     * @param float $timeStep
     *
     * @return self
     */
    public function setTimeStep($timeStep)
    {
        $this->timeStep = $timeStep;

        return $this;
    }

    /**
     * @return integer
     */
    public function getStorageStep()
    {
        return $this->storageStep;
    }

    /**
     * @param integer $storageStep
     *
     * @return self
     */
    public function setStorageStep($storageStep)
    {
        $this->storageStep = $storageStep;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPrecisionLogStep()
    {
        return $this->precisionLogStep;
    }

    /**
     * @param integer $precisionLogStep
     *
     * @return self
     */
    public function setPrecisionLogStep($precisionLogStep)
    {
        $this->precisionLogStep = $precisionLogStep;

        return $this;
    }

    /**
     * @return float
     */
    public function getStopTopSurf()
    {
        return $this->stopTopSurf;
    }

    /**
     * @param float $stopTopSurf
     *
     * @return self
     */
    public function setStopTopSurf($stopTopSurf)
    {
        $this->stopTopSurf = $stopTopSurf;

        return $this;
    }

    /**
     * @return float
     */
    public function getStopInt()
    {
        return $this->stopInt;
    }

    /**
     * @param float $stopInt
     *
     * @return self
     */
    public function setStopInt($stopInt)
    {
        $this->stopInt = $stopInt;

        return $this;
    }

    /**
     * @return float
     */
    public function getStopBottomSurf()
    {
        return $this->stopBottomSurf;
    }

    /**
     * @param float $stopBottomSurf
     *
     * @return self
     */
    public function setStopBottomSurf($stopBottomSurf)
    {
        $this->stopBottomSurf = $stopBottomSurf;

        return $this;
    }

    /**
     * @return float
     */
    public function getStopAvg()
    {
        return $this->stopAvg;
    }

    /**
     * @param float $stopAvg
     *
     * @return self
     */
    public function setStopAvg($stopAvg)
    {
        $this->stopAvg = $stopAvg;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaTopFixed()
    {
        return $this->studyAlphaTopFixed;
    }

    /**
     * @param boolean $studyAlphaTopFixed
     *
     * @return self
     */
    public function setStudyAlphaTopFixed($studyAlphaTopFixed)
    {
        $this->studyAlphaTopFixed = $studyAlphaTopFixed;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaTop()
    {
        return $this->studyAlphaTop;
    }

    /**
     * @param float $studyAlphaTop
     *
     * @return self
     */
    public function setStudyAlphaTop($studyAlphaTop)
    {
        $this->studyAlphaTop = $studyAlphaTop;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaBottomFixed()
    {
        return $this->studyAlphaBottomFixed;
    }

    /**
     * @param boolean $studyAlphaBottomFixed
     *
     * @return self
     */
    public function setStudyAlphaBottomFixed($studyAlphaBottomFixed)
    {
        $this->studyAlphaBottomFixed = $studyAlphaBottomFixed;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaBottom()
    {
        return $this->studyAlphaBottom;
    }

    /**
     * @param float $studyAlphaBottom
     *
     * @return self
     */
    public function setStudyAlphaBottom($studyAlphaBottom)
    {
        $this->studyAlphaBottom = $studyAlphaBottom;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaLeftFixed()
    {
        return $this->studyAlphaLeftFixed;
    }

    /**
     * @param boolean $studyAlphaLeftFixed
     *
     * @return self
     */
    public function setStudyAlphaLeftFixed($studyAlphaLeftFixed)
    {
        $this->studyAlphaLeftFixed = $studyAlphaLeftFixed;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaLeft()
    {
        return $this->studyAlphaLeft;
    }

    /**
     * @param float $studyAlphaLeft
     *
     * @return self
     */
    public function setStudyAlphaLeft($studyAlphaLeft)
    {
        $this->studyAlphaLeft = $studyAlphaLeft;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaRightFixed()
    {
        return $this->studyAlphaRightFixed;
    }

    /**
     * @param boolean $studyAlphaRightFixed
     *
     * @return self
     */
    public function setStudyAlphaRightFixed($studyAlphaRightFixed)
    {
        $this->studyAlphaRightFixed = $studyAlphaRightFixed;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaRight()
    {
        return $this->studyAlphaRight;
    }

    /**
     * @param float $studyAlphaRight
     *
     * @return self
     */
    public function setStudyAlphaRight($studyAlphaRight)
    {
        $this->studyAlphaRight = $studyAlphaRight;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaFrontFixed()
    {
        return $this->studyAlphaFrontFixed;
    }

    /**
     * @param boolean $studyAlphaFrontFixed
     *
     * @return self
     */
    public function setStudyAlphaFrontFixed($studyAlphaFrontFixed)
    {
        $this->studyAlphaFrontFixed = $studyAlphaFrontFixed;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaFront()
    {
        return $this->studyAlphaFront;
    }

    /**
     * @param float $studyAlphaFront
     *
     * @return self
     */
    public function setStudyAlphaFront($studyAlphaFront)
    {
        $this->studyAlphaFront = $studyAlphaFront;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaRearFixed()
    {
        return $this->studyAlphaRearFixed;
    }

    /**
     * @param boolean $studyAlphaRearFixed
     *
     * @return self
     */
    public function setStudyAlphaRearFixed($studyAlphaRearFixed)
    {
        $this->studyAlphaRearFixed = $studyAlphaRearFixed;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaRear()
    {
        return $this->studyAlphaRear;
    }

    /**
     * @param float $studyAlphaRear
     *
     * @return self
     */
    public function setStudyAlphaRear($studyAlphaRear)
    {
        $this->studyAlphaRear = $studyAlphaRear;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrecisionRequest()
    {
        return $this->precisionRequest;
    }

    /**
     * @param float $precisionRequest
     *
     * @return self
     */
    public function setPrecisionRequest($precisionRequest)
    {
        $this->precisionRequest = $precisionRequest;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbOptim()
    {
        return $this->nbOptim;
    }

    /**
     * @param integer $nbOptim
     *
     * @return self
     */
    public function setNbOptim($nbOptim)
    {
        $this->nbOptim = $nbOptim;

        return $this;
    }

    /**
     * @return float
     */
    public function getErrorT()
    {
        return $this->errorT;
    }

    /**
     * @param float $errorT
     *
     * @return self
     */
    public function setErrorT($errorT)
    {
        $this->errorT = $errorT;

        return $this;
    }

    /**
     * @return float
     */
    public function getErrorH()
    {
        return $this->errorH;
    }

    /**
     * @param float $errorH
     *
     * @return self
     */
    public function setErrorH($errorH)
    {
        $this->errorH = $errorH;

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

