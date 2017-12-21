<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CalculationParametersDef
 *
 * @ORM\Table(name="calculation_parameters_def", indexes={@ORM\Index(name="FK_CALCULATION_PARAMETERS_DEF_LN2USER", columns={"ID_USER"})})
 * @ORM\Entity
 */
class CalculationParametersDef
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="HORIZ_SCAN_DEF", type="boolean", nullable=true)
     */
    private $horizScanDef;

    /**
     * @var boolean
     *
     * @ORM\Column(name="VERT_SCAN_DEF", type="boolean", nullable=true)
     */
    private $vertScanDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="MAX_IT_NB_DEF", type="integer", nullable=true)
     */
    private $maxItNbDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="TIME_STEPS_NB_DEF", type="integer", nullable=true)
     */
    private $timeStepsNbDef;

    /**
     * @var float
     *
     * @ORM\Column(name="RELAX_COEFF_DEF", type="float", precision=24, scale=0, nullable=true)
     */
    private $relaxCoeffDef;

    /**
     * @var float
     *
     * @ORM\Column(name="TIME_STEP_DEF", type="float", precision=24, scale=0, nullable=true)
     */
    private $timeStepDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="STORAGE_STEP_DEF", type="integer", nullable=true)
     */
    private $storageStepDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="PRECISION_LOG_STEP_DEF", type="integer", nullable=true)
     */
    private $precisionLogStepDef;

    /**
     * @var float
     *
     * @ORM\Column(name="STOP_TOP_SURF_DEF", type="float", precision=24, scale=0, nullable=true)
     */
    private $stopTopSurfDef;

    /**
     * @var float
     *
     * @ORM\Column(name="STOP_INT_DEF", type="float", precision=24, scale=0, nullable=true)
     */
    private $stopIntDef;

    /**
     * @var float
     *
     * @ORM\Column(name="STOP_BOTTOM_SURF_DEF", type="float", precision=24, scale=0, nullable=true)
     */
    private $stopBottomSurfDef;

    /**
     * @var float
     *
     * @ORM\Column(name="STOP_AVG_DEF", type="float", precision=24, scale=0, nullable=true)
     */
    private $stopAvgDef;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_TOP_FIXED_DEF", type="boolean", nullable=true)
     */
    private $studyAlphaTopFixedDef;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_TOP_DEF", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaTopDef;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_BOTTOM_FIXED_DEF", type="boolean", nullable=true)
     */
    private $studyAlphaBottomFixedDef;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_BOTTOM_DEF", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaBottomDef;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_LEFT_FIXED_DEF", type="boolean", nullable=true)
     */
    private $studyAlphaLeftFixedDef;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_LEFT_DEF", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaLeftDef;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_RIGHT_FIXED_DEF", type="boolean", nullable=true)
     */
    private $studyAlphaRightFixedDef;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_RIGHT_DEF", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaRightDef;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_FRONT_FIXED_DEF", type="boolean", nullable=true)
     */
    private $studyAlphaFrontFixedDef;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_FRONT_DEF", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaFrontDef;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STUDY_ALPHA_REAR_FIXED_DEF", type="boolean", nullable=true)
     */
    private $studyAlphaRearFixedDef;

    /**
     * @var float
     *
     * @ORM\Column(name="STUDY_ALPHA_REAR_DEF", type="float", precision=10, scale=0, nullable=true)
     */
    private $studyAlphaRearDef;

    /**
     * @var float
     *
     * @ORM\Column(name="PRECISION_REQUEST_DEF", type="float", precision=10, scale=0, nullable=true)
     */
    private $precisionRequestDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_CALC_PARAMSDEF", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCalcParamsdef;

    /**
     * @var \AppBundle\Entity\Ln2user
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ln2user")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_USER", referencedColumnName="ID_USER")
     * })
     */
    private $idUser;



    /**
     * @return boolean
     */
    public function isHorizScanDef()
    {
        return $this->horizScanDef;
    }

    /**
     * @param boolean $horizScanDef
     *
     * @return self
     */
    public function setHorizScanDef($horizScanDef)
    {
        $this->horizScanDef = $horizScanDef;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isVertScanDef()
    {
        return $this->vertScanDef;
    }

    /**
     * @param boolean $vertScanDef
     *
     * @return self
     */
    public function setVertScanDef($vertScanDef)
    {
        $this->vertScanDef = $vertScanDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMaxItNbDef()
    {
        return $this->maxItNbDef;
    }

    /**
     * @param integer $maxItNbDef
     *
     * @return self
     */
    public function setMaxItNbDef($maxItNbDef)
    {
        $this->maxItNbDef = $maxItNbDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTimeStepsNbDef()
    {
        return $this->timeStepsNbDef;
    }

    /**
     * @param integer $timeStepsNbDef
     *
     * @return self
     */
    public function setTimeStepsNbDef($timeStepsNbDef)
    {
        $this->timeStepsNbDef = $timeStepsNbDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getRelaxCoeffDef()
    {
        return $this->relaxCoeffDef;
    }

    /**
     * @param float $relaxCoeffDef
     *
     * @return self
     */
    public function setRelaxCoeffDef($relaxCoeffDef)
    {
        $this->relaxCoeffDef = $relaxCoeffDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getTimeStepDef()
    {
        return $this->timeStepDef;
    }

    /**
     * @param float $timeStepDef
     *
     * @return self
     */
    public function setTimeStepDef($timeStepDef)
    {
        $this->timeStepDef = $timeStepDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getStorageStepDef()
    {
        return $this->storageStepDef;
    }

    /**
     * @param integer $storageStepDef
     *
     * @return self
     */
    public function setStorageStepDef($storageStepDef)
    {
        $this->storageStepDef = $storageStepDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPrecisionLogStepDef()
    {
        return $this->precisionLogStepDef;
    }

    /**
     * @param integer $precisionLogStepDef
     *
     * @return self
     */
    public function setPrecisionLogStepDef($precisionLogStepDef)
    {
        $this->precisionLogStepDef = $precisionLogStepDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getStopTopSurfDef()
    {
        return $this->stopTopSurfDef;
    }

    /**
     * @param float $stopTopSurfDef
     *
     * @return self
     */
    public function setStopTopSurfDef($stopTopSurfDef)
    {
        $this->stopTopSurfDef = $stopTopSurfDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getStopIntDef()
    {
        return $this->stopIntDef;
    }

    /**
     * @param float $stopIntDef
     *
     * @return self
     */
    public function setStopIntDef($stopIntDef)
    {
        $this->stopIntDef = $stopIntDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getStopBottomSurfDef()
    {
        return $this->stopBottomSurfDef;
    }

    /**
     * @param float $stopBottomSurfDef
     *
     * @return self
     */
    public function setStopBottomSurfDef($stopBottomSurfDef)
    {
        $this->stopBottomSurfDef = $stopBottomSurfDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getStopAvgDef()
    {
        return $this->stopAvgDef;
    }

    /**
     * @param float $stopAvgDef
     *
     * @return self
     */
    public function setStopAvgDef($stopAvgDef)
    {
        $this->stopAvgDef = $stopAvgDef;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaTopFixedDef()
    {
        return $this->studyAlphaTopFixedDef;
    }

    /**
     * @param boolean $studyAlphaTopFixedDef
     *
     * @return self
     */
    public function setStudyAlphaTopFixedDef($studyAlphaTopFixedDef)
    {
        $this->studyAlphaTopFixedDef = $studyAlphaTopFixedDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaTopDef()
    {
        return $this->studyAlphaTopDef;
    }

    /**
     * @param float $studyAlphaTopDef
     *
     * @return self
     */
    public function setStudyAlphaTopDef($studyAlphaTopDef)
    {
        $this->studyAlphaTopDef = $studyAlphaTopDef;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaBottomFixedDef()
    {
        return $this->studyAlphaBottomFixedDef;
    }

    /**
     * @param boolean $studyAlphaBottomFixedDef
     *
     * @return self
     */
    public function setStudyAlphaBottomFixedDef($studyAlphaBottomFixedDef)
    {
        $this->studyAlphaBottomFixedDef = $studyAlphaBottomFixedDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaBottomDef()
    {
        return $this->studyAlphaBottomDef;
    }

    /**
     * @param float $studyAlphaBottomDef
     *
     * @return self
     */
    public function setStudyAlphaBottomDef($studyAlphaBottomDef)
    {
        $this->studyAlphaBottomDef = $studyAlphaBottomDef;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaLeftFixedDef()
    {
        return $this->studyAlphaLeftFixedDef;
    }

    /**
     * @param boolean $studyAlphaLeftFixedDef
     *
     * @return self
     */
    public function setStudyAlphaLeftFixedDef($studyAlphaLeftFixedDef)
    {
        $this->studyAlphaLeftFixedDef = $studyAlphaLeftFixedDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaLeftDef()
    {
        return $this->studyAlphaLeftDef;
    }

    /**
     * @param float $studyAlphaLeftDef
     *
     * @return self
     */
    public function setStudyAlphaLeftDef($studyAlphaLeftDef)
    {
        $this->studyAlphaLeftDef = $studyAlphaLeftDef;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaRightFixedDef()
    {
        return $this->studyAlphaRightFixedDef;
    }

    /**
     * @param boolean $studyAlphaRightFixedDef
     *
     * @return self
     */
    public function setStudyAlphaRightFixedDef($studyAlphaRightFixedDef)
    {
        $this->studyAlphaRightFixedDef = $studyAlphaRightFixedDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaRightDef()
    {
        return $this->studyAlphaRightDef;
    }

    /**
     * @param float $studyAlphaRightDef
     *
     * @return self
     */
    public function setStudyAlphaRightDef($studyAlphaRightDef)
    {
        $this->studyAlphaRightDef = $studyAlphaRightDef;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaFrontFixedDef()
    {
        return $this->studyAlphaFrontFixedDef;
    }

    /**
     * @param boolean $studyAlphaFrontFixedDef
     *
     * @return self
     */
    public function setStudyAlphaFrontFixedDef($studyAlphaFrontFixedDef)
    {
        $this->studyAlphaFrontFixedDef = $studyAlphaFrontFixedDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaFrontDef()
    {
        return $this->studyAlphaFrontDef;
    }

    /**
     * @param float $studyAlphaFrontDef
     *
     * @return self
     */
    public function setStudyAlphaFrontDef($studyAlphaFrontDef)
    {
        $this->studyAlphaFrontDef = $studyAlphaFrontDef;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStudyAlphaRearFixedDef()
    {
        return $this->studyAlphaRearFixedDef;
    }

    /**
     * @param boolean $studyAlphaRearFixedDef
     *
     * @return self
     */
    public function setStudyAlphaRearFixedDef($studyAlphaRearFixedDef)
    {
        $this->studyAlphaRearFixedDef = $studyAlphaRearFixedDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getStudyAlphaRearDef()
    {
        return $this->studyAlphaRearDef;
    }

    /**
     * @param float $studyAlphaRearDef
     *
     * @return self
     */
    public function setStudyAlphaRearDef($studyAlphaRearDef)
    {
        $this->studyAlphaRearDef = $studyAlphaRearDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrecisionRequestDef()
    {
        return $this->precisionRequestDef;
    }

    /**
     * @param float $precisionRequestDef
     *
     * @return self
     */
    public function setPrecisionRequestDef($precisionRequestDef)
    {
        $this->precisionRequestDef = $precisionRequestDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdCalcParamsdef()
    {
        return $this->idCalcParamsdef;
    }

    /**
     * @param integer $idCalcParamsdef
     *
     * @return self
     */
    public function setIdCalcParamsdef($idCalcParamsdef)
    {
        $this->idCalcParamsdef = $idCalcParamsdef;

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

