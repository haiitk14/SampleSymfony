<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EconomicResults
 *
 * @ORM\Table(name="economic_results", indexes={@ORM\Index(name="FK_ECONOMIC_RESULTS_STUDY_EQUIPMENTS", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class EconomicResults
{
    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_PRODUCT", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidConsumptionProduct;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_MAT_PERM", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidConsumptionMatPerm;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_MAT_GETCOLD", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidConsumptionMatGetcold;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_LINE_PERM", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidConsumptionLinePerm;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_LINE_GETCOLD", type="float", precision=10, scale=0, nullable=true)
     */
    private $fluidConsumptionLineGetcold;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_TANK", type="float", precision=10, scale=0, nullable=true)
     */
    private $fluidConsumptionTank;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_TOTAL", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidConsumptionTotal;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_PER_KG", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidConsumptionPerKg;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_DAY", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidConsumptionDay;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_MONTH", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidConsumptionMonth;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_YEAR", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidConsumptionYear;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_HOUR", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidConsumptionHour;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_CONSUMPTION_WEEK", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidConsumptionWeek;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_PRODUCT", type="float", precision=24, scale=0, nullable=true)
     */
    private $costProduct;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_MAT_PERM", type="float", precision=24, scale=0, nullable=true)
     */
    private $costMatPerm;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_MAT_GETCOLD", type="float", precision=24, scale=0, nullable=true)
     */
    private $costMatGetcold;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_LINE_PERM", type="float", precision=24, scale=0, nullable=true)
     */
    private $costLinePerm;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_LINE_GETCOLD", type="float", precision=24, scale=0, nullable=true)
     */
    private $costLineGetcold;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_TANK", type="float", precision=24, scale=0, nullable=true)
     */
    private $costTank;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_TOTAL", type="float", precision=24, scale=0, nullable=true)
     */
    private $costTotal;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_KG", type="float", precision=24, scale=0, nullable=true)
     */
    private $costKg;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_DAY", type="float", precision=24, scale=0, nullable=true)
     */
    private $costDay;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_MONTH", type="float", precision=24, scale=0, nullable=true)
     */
    private $costMonth;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_YEAR", type="float", precision=24, scale=0, nullable=true)
     */
    private $costYear;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_HOUR", type="float", precision=24, scale=0, nullable=true)
     */
    private $costHour;

    /**
     * @var float
     *
     * @ORM\Column(name="COST_WEEK", type="float", precision=24, scale=0, nullable=true)
     */
    private $costWeek;

    /**
     * @var float
     *
     * @ORM\Column(name="PERCENT_PRODUCT", type="float", precision=24, scale=0, nullable=true)
     */
    private $percentProduct;

    /**
     * @var float
     *
     * @ORM\Column(name="PERCENT_EQUIPMENT_PERM", type="float", precision=24, scale=0, nullable=true)
     */
    private $percentEquipmentPerm;

    /**
     * @var float
     *
     * @ORM\Column(name="PERCENT_EQUIPMENT_DOWN", type="float", precision=24, scale=0, nullable=true)
     */
    private $percentEquipmentDown;

    /**
     * @var float
     *
     * @ORM\Column(name="PERCENT_LINE", type="float", precision=24, scale=0, nullable=true)
     */
    private $percentLine;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_ECONOMIC_RESULTS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEconomicResults;

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
    public function getFluidConsumptionProduct()
    {
        return $this->fluidConsumptionProduct;
    }

    /**
     * @param float $fluidConsumptionProduct
     *
     * @return self
     */
    public function setFluidConsumptionProduct($fluidConsumptionProduct)
    {
        $this->fluidConsumptionProduct = $fluidConsumptionProduct;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionMatPerm()
    {
        return $this->fluidConsumptionMatPerm;
    }

    /**
     * @param float $fluidConsumptionMatPerm
     *
     * @return self
     */
    public function setFluidConsumptionMatPerm($fluidConsumptionMatPerm)
    {
        $this->fluidConsumptionMatPerm = $fluidConsumptionMatPerm;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionMatGetcold()
    {
        return $this->fluidConsumptionMatGetcold;
    }

    /**
     * @param float $fluidConsumptionMatGetcold
     *
     * @return self
     */
    public function setFluidConsumptionMatGetcold($fluidConsumptionMatGetcold)
    {
        $this->fluidConsumptionMatGetcold = $fluidConsumptionMatGetcold;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionLinePerm()
    {
        return $this->fluidConsumptionLinePerm;
    }

    /**
     * @param float $fluidConsumptionLinePerm
     *
     * @return self
     */
    public function setFluidConsumptionLinePerm($fluidConsumptionLinePerm)
    {
        $this->fluidConsumptionLinePerm = $fluidConsumptionLinePerm;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionLineGetcold()
    {
        return $this->fluidConsumptionLineGetcold;
    }

    /**
     * @param float $fluidConsumptionLineGetcold
     *
     * @return self
     */
    public function setFluidConsumptionLineGetcold($fluidConsumptionLineGetcold)
    {
        $this->fluidConsumptionLineGetcold = $fluidConsumptionLineGetcold;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionTank()
    {
        return $this->fluidConsumptionTank;
    }

    /**
     * @param float $fluidConsumptionTank
     *
     * @return self
     */
    public function setFluidConsumptionTank($fluidConsumptionTank)
    {
        $this->fluidConsumptionTank = $fluidConsumptionTank;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionTotal()
    {
        return $this->fluidConsumptionTotal;
    }

    /**
     * @param float $fluidConsumptionTotal
     *
     * @return self
     */
    public function setFluidConsumptionTotal($fluidConsumptionTotal)
    {
        $this->fluidConsumptionTotal = $fluidConsumptionTotal;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionPerKg()
    {
        return $this->fluidConsumptionPerKg;
    }

    /**
     * @param float $fluidConsumptionPerKg
     *
     * @return self
     */
    public function setFluidConsumptionPerKg($fluidConsumptionPerKg)
    {
        $this->fluidConsumptionPerKg = $fluidConsumptionPerKg;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionDay()
    {
        return $this->fluidConsumptionDay;
    }

    /**
     * @param float $fluidConsumptionDay
     *
     * @return self
     */
    public function setFluidConsumptionDay($fluidConsumptionDay)
    {
        $this->fluidConsumptionDay = $fluidConsumptionDay;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionMonth()
    {
        return $this->fluidConsumptionMonth;
    }

    /**
     * @param float $fluidConsumptionMonth
     *
     * @return self
     */
    public function setFluidConsumptionMonth($fluidConsumptionMonth)
    {
        $this->fluidConsumptionMonth = $fluidConsumptionMonth;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionYear()
    {
        return $this->fluidConsumptionYear;
    }

    /**
     * @param float $fluidConsumptionYear
     *
     * @return self
     */
    public function setFluidConsumptionYear($fluidConsumptionYear)
    {
        $this->fluidConsumptionYear = $fluidConsumptionYear;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionHour()
    {
        return $this->fluidConsumptionHour;
    }

    /**
     * @param float $fluidConsumptionHour
     *
     * @return self
     */
    public function setFluidConsumptionHour($fluidConsumptionHour)
    {
        $this->fluidConsumptionHour = $fluidConsumptionHour;

        return $this;
    }

    /**
     * @return float
     */
    public function getFluidConsumptionWeek()
    {
        return $this->fluidConsumptionWeek;
    }

    /**
     * @param float $fluidConsumptionWeek
     *
     * @return self
     */
    public function setFluidConsumptionWeek($fluidConsumptionWeek)
    {
        $this->fluidConsumptionWeek = $fluidConsumptionWeek;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostProduct()
    {
        return $this->costProduct;
    }

    /**
     * @param float $costProduct
     *
     * @return self
     */
    public function setCostProduct($costProduct)
    {
        $this->costProduct = $costProduct;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostMatPerm()
    {
        return $this->costMatPerm;
    }

    /**
     * @param float $costMatPerm
     *
     * @return self
     */
    public function setCostMatPerm($costMatPerm)
    {
        $this->costMatPerm = $costMatPerm;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostMatGetcold()
    {
        return $this->costMatGetcold;
    }

    /**
     * @param float $costMatGetcold
     *
     * @return self
     */
    public function setCostMatGetcold($costMatGetcold)
    {
        $this->costMatGetcold = $costMatGetcold;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostLinePerm()
    {
        return $this->costLinePerm;
    }

    /**
     * @param float $costLinePerm
     *
     * @return self
     */
    public function setCostLinePerm($costLinePerm)
    {
        $this->costLinePerm = $costLinePerm;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostLineGetcold()
    {
        return $this->costLineGetcold;
    }

    /**
     * @param float $costLineGetcold
     *
     * @return self
     */
    public function setCostLineGetcold($costLineGetcold)
    {
        $this->costLineGetcold = $costLineGetcold;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostTank()
    {
        return $this->costTank;
    }

    /**
     * @param float $costTank
     *
     * @return self
     */
    public function setCostTank($costTank)
    {
        $this->costTank = $costTank;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostTotal()
    {
        return $this->costTotal;
    }

    /**
     * @param float $costTotal
     *
     * @return self
     */
    public function setCostTotal($costTotal)
    {
        $this->costTotal = $costTotal;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostKg()
    {
        return $this->costKg;
    }

    /**
     * @param float $costKg
     *
     * @return self
     */
    public function setCostKg($costKg)
    {
        $this->costKg = $costKg;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostDay()
    {
        return $this->costDay;
    }

    /**
     * @param float $costDay
     *
     * @return self
     */
    public function setCostDay($costDay)
    {
        $this->costDay = $costDay;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostMonth()
    {
        return $this->costMonth;
    }

    /**
     * @param float $costMonth
     *
     * @return self
     */
    public function setCostMonth($costMonth)
    {
        $this->costMonth = $costMonth;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostYear()
    {
        return $this->costYear;
    }

    /**
     * @param float $costYear
     *
     * @return self
     */
    public function setCostYear($costYear)
    {
        $this->costYear = $costYear;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostHour()
    {
        return $this->costHour;
    }

    /**
     * @param float $costHour
     *
     * @return self
     */
    public function setCostHour($costHour)
    {
        $this->costHour = $costHour;

        return $this;
    }

    /**
     * @return float
     */
    public function getCostWeek()
    {
        return $this->costWeek;
    }

    /**
     * @param float $costWeek
     *
     * @return self
     */
    public function setCostWeek($costWeek)
    {
        $this->costWeek = $costWeek;

        return $this;
    }

    /**
     * @return float
     */
    public function getPercentProduct()
    {
        return $this->percentProduct;
    }

    /**
     * @param float $percentProduct
     *
     * @return self
     */
    public function setPercentProduct($percentProduct)
    {
        $this->percentProduct = $percentProduct;

        return $this;
    }

    /**
     * @return float
     */
    public function getPercentEquipmentPerm()
    {
        return $this->percentEquipmentPerm;
    }

    /**
     * @param float $percentEquipmentPerm
     *
     * @return self
     */
    public function setPercentEquipmentPerm($percentEquipmentPerm)
    {
        $this->percentEquipmentPerm = $percentEquipmentPerm;

        return $this;
    }

    /**
     * @return float
     */
    public function getPercentEquipmentDown()
    {
        return $this->percentEquipmentDown;
    }

    /**
     * @param float $percentEquipmentDown
     *
     * @return self
     */
    public function setPercentEquipmentDown($percentEquipmentDown)
    {
        $this->percentEquipmentDown = $percentEquipmentDown;

        return $this;
    }

    /**
     * @return float
     */
    public function getPercentLine()
    {
        return $this->percentLine;
    }

    /**
     * @param float $percentLine
     *
     * @return self
     */
    public function setPercentLine($percentLine)
    {
        $this->percentLine = $percentLine;

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

