<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Studies
 *
 * @ORM\Table(name="studies", indexes={@ORM\Index(name="FK_STUDIES_LN2USER", columns={"ID_USER"})})
 * @ORM\Entity
 * @UniqueEntity("studyName")
 */
class Studies
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_TEMP_RECORD_PTS", type="integer", nullable=true)
     */
    private $idTempRecordPts;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PRODUCTION", type="integer", nullable=true)
     */
    private $idProduction;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PACKING", type="integer", nullable=true)
     */
    private $idPacking;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_STUDY_RESULTS", type="integer", nullable=true)
     */
    private $idStudyResults;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PROD", type="integer", nullable=true)
     */
    private $idProd;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PRICE", type="integer", nullable=true)
     */
    private $idPrice;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_HAVERAGE_RESULTS", type="integer", nullable=true)
     */
    private $idHaverageResults;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_REPORT", type="integer", nullable=true)
     */
    private $idReport;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PRECALC_LDG_RATE_PRM", type="integer", nullable=true)
     */
    private $idPrecalcLdgRatePrm;

    /**
     * @var integer
     *
     * @ORM\Column(name="CALCULATION_MODE", type="integer", nullable=true)
     */
    private $calculationMode;

    /**
     * @var integer
     *
     * @ORM\Column(name="CALCULATION_STATUS", type="integer", nullable=true)
     */
    private $calculationStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="STUDY_NAME", type="string", length=250, nullable=true, unique=true)
     */
    private $studyName;

    /**
     * @var string
     *
     * @ORM\Column(name="CUSTOMER", type="string", length=100, nullable=true)
     */
    private $customer;

    /**
     * @var string
     *
     * @ORM\Column(name="COMMENT_TXT", type="string", length=2000, nullable=true)
     */
    private $commentTxt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="OPTION_CRYOPIPELINE", type="boolean", nullable=true)
     */
    private $optionCryopipeline;

    /**
     * @var boolean
     *
     * @ORM\Column(name="OPTION_EXHAUSTPIPELINE", type="boolean", nullable=true)
     */
    private $optionExhaustpipeline;

    /**
     * @var boolean
     *
     * @ORM\Column(name="OPTION_ECO", type="boolean", nullable=true)
     */
    private $optionEco;

    /**
     * @var boolean
     *
     * @ORM\Column(name="CHAINING_CONTROLS", type="boolean", nullable=true)
     */
    private $chainingControls;

    /**
     * @var boolean
     *
     * @ORM\Column(name="CHAINING_ADD_COMP_ENABLE", type="boolean", nullable=true)
     */
    private $chainingAddCompEnable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="CHAINING_NODE_DECIM_ENABLE", type="boolean", nullable=true)
     */
    private $chainingNodeDecimEnable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TO_RECALCULATE", type="boolean", nullable=true)
     */
    private $toRecalculate;

    /**
     * @var integer
     *
     * @ORM\Column(name="PARENT_ID", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="PARENT_STUD_EQP_ID", type="integer", nullable=true)
     */
    private $parentStudEqpId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="HAS_CHILD", type="boolean", nullable=true)
     */
    private $hasChild;

    /**
     * @var boolean
     *
     * @ORM\Column(name="OPEN_BY_OWNER", type="boolean", nullable=true)
     */
    private $openByOwner = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_STUDY", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStudy;

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
     * @return integer
     */
    public function getIdTempRecordPts()
    {
        return $this->idTempRecordPts;
    }

    /**
     * @param integer $idTempRecordPts
     *
     * @return self
     */
    public function setIdTempRecordPts($idTempRecordPts)
    {
        $this->idTempRecordPts = $idTempRecordPts;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdProduction()
    {
        return $this->idProduction;
    }

    /**
     * @param integer $idProduction
     *
     * @return self
     */
    public function setIdProduction($idProduction)
    {
        $this->idProduction = $idProduction;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdPacking()
    {
        return $this->idPacking;
    }

    /**
     * @param integer $idPacking
     *
     * @return self
     */
    public function setIdPacking($idPacking)
    {
        $this->idPacking = $idPacking;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdStudyResults()
    {
        return $this->idStudyResults;
    }

    /**
     * @param integer $idStudyResults
     *
     * @return self
     */
    public function setIdStudyResults($idStudyResults)
    {
        $this->idStudyResults = $idStudyResults;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdProd()
    {
        return $this->idProd;
    }

    /**
     * @param integer $idProd
     *
     * @return self
     */
    public function setIdProd($idProd)
    {
        $this->idProd = $idProd;

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
     * @return integer
     */
    public function getIdHaverageResults()
    {
        return $this->idHaverageResults;
    }

    /**
     * @param integer $idHaverageResults
     *
     * @return self
     */
    public function setIdHaverageResults($idHaverageResults)
    {
        $this->idHaverageResults = $idHaverageResults;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdReport()
    {
        return $this->idReport;
    }

    /**
     * @param integer $idReport
     *
     * @return self
     */
    public function setIdReport($idReport)
    {
        $this->idReport = $idReport;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdPrecalcLdgRatePrm()
    {
        return $this->idPrecalcLdgRatePrm;
    }

    /**
     * @param integer $idPrecalcLdgRatePrm
     *
     * @return self
     */
    public function setIdPrecalcLdgRatePrm($idPrecalcLdgRatePrm)
    {
        $this->idPrecalcLdgRatePrm = $idPrecalcLdgRatePrm;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCalculationMode()
    {
        return $this->calculationMode;
    }

    /**
     * @param integer $calculationMode
     *
     * @return self
     */
    public function setCalculationMode($calculationMode)
    {
        $this->calculationMode = $calculationMode;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCalculationStatus()
    {
        return $this->calculationStatus;
    }

    /**
     * @param integer $calculationStatus
     *
     * @return self
     */
    public function setCalculationStatus($calculationStatus)
    {
        $this->calculationStatus = $calculationStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getStudyName()
    {
        return $this->studyName;
    }

    /**
     * @param string $studyName
     *
     * @return self
     */
    public function setStudyName($studyName)
    {
        $this->studyName = $studyName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param string $customer
     *
     * @return self
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return string
     */
    public function getCommentTxt()
    {
        return $this->commentTxt;
    }

    /**
     * @param string $commentTxt
     *
     * @return self
     */
    public function setCommentTxt($commentTxt)
    {
        $this->commentTxt = $commentTxt;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getOptionCryopipeline()
    {
        return $this->optionCryopipeline;
    }

    /**
     * @param boolean $optionCryopipeline
     *
     * @return self
     */
    public function setOptionCryopipeline($optionCryopipeline)
    {
        $this->optionCryopipeline = $optionCryopipeline;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isOptionExhaustpipeline()
    {
        return $this->optionExhaustpipeline;
    }

    /**
     * @param boolean $optionExhaustpipeline
     *
     * @return self
     */
    public function setOptionExhaustpipeline($optionExhaustpipeline)
    {
        $this->optionExhaustpipeline = $optionExhaustpipeline;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getOptionEco()
    {
        return $this->optionEco;
    }

    /**
     * @param boolean $optionEco
     *
     * @return self
     */
    public function setOptionEco($optionEco)
    {
        $this->optionEco = $optionEco;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getChainingControls()
    {
        return $this->chainingControls;
    }

    /**
     * @param boolean $chainingControls
     *
     * @return self
     */
    public function setChainingControls($chainingControls)
    {
        $this->chainingControls = $chainingControls;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getChainingAddCompEnable()
    {
        return $this->chainingAddCompEnable;
    }

    /**
     * @param boolean $chainingAddCompEnable
     *
     * @return self
     */
    public function setChainingAddCompEnable($chainingAddCompEnable)
    {
        $this->chainingAddCompEnable = $chainingAddCompEnable;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getChainingNodeDecimEnable()
    {
        return $this->chainingNodeDecimEnable;
    }

    /**
     * @param boolean $chainingNodeDecimEnable
     *
     * @return self
     */
    public function setChainingNodeDecimEnable($chainingNodeDecimEnable)
    {
        $this->chainingNodeDecimEnable = $chainingNodeDecimEnable;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getToRecalculate()
    {
        return $this->toRecalculate;
    }

    /**
     * @param boolean $toRecalculate
     *
     * @return self
     */
    public function setToRecalculate($toRecalculate)
    {
        $this->toRecalculate = $toRecalculate;

        return $this;
    }

    /**
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param integer $parentId
     *
     * @return self
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @return integer
     */
    public function getParentStudEqpId()
    {
        return $this->parentStudEqpId;
    }

    /**
     * @param integer $parentStudEqpId
     *
     * @return self
     */
    public function setParentStudEqpId($parentStudEqpId)
    {
        $this->parentStudEqpId = $parentStudEqpId;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getHasChild()
    {
        return $this->hasChild;
    }

    /**
     * @param boolean $hasChild
     *
     * @return self
     */
    public function setHasChild($hasChild)
    {
        $this->hasChild = $hasChild;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getOpenByOwner()
    {
        return $this->openByOwner;
    }

    /**
     * @param boolean $openByOwner
     *
     * @return self
     */
    public function setOpenByOwner($openByOwner)
    {
        $this->openByOwner = $openByOwner;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdStudy()
    {
        return $this->idStudy;
    }

    /**
     * @param integer $idStudy
     *
     * @return self
     */
    public function setIdStudy($idStudy)
    {
        $this->idStudy = $idStudy;

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
    public function __toString() {
        return $this->studyName;
    }
}

