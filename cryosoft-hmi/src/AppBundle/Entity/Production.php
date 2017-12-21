<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Production
 *
 * @ORM\Table(name="production", indexes={@ORM\Index(name="FK_PRODUCTION_STUDIES", columns={"ID_STUDY"})})
 * @ORM\Entity
 */
class Production
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PRODUCTION", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProduction;

    /**
     * @var float
     *
     * @ORM\Column(name="DAILY_PROD", type="float", precision=24, scale=0, nullable=true)
     */
    private $dailyProd;

    /**
     * @var float
     *
     * @ORM\Column(name="DAILY_STARTUP", type="float", precision=24, scale=0, nullable=true)
     */
    private $dailyStartup;

    /**
     * @var float
     *
     * @ORM\Column(name="WEEKLY_PROD", type="float", precision=24, scale=0, nullable=true)
     */
    private $weeklyProd;

    /**
     * @var float
     *
     * @ORM\Column(name="PROD_FLOW_RATE", type="float", precision=24, scale=0, nullable=true)
     */
    private $prodFlowRate;

    /**
     * @var float
     *
     * @ORM\Column(name="NB_PROD_WEEK_PER_YEAR", type="float", precision=24, scale=0, nullable=true)
     */
    private $nbProdWeekPerYear;

    /**
     * @var float
     *
     * @ORM\Column(name="AMBIENT_TEMP", type="float", precision=24, scale=0, nullable=true)
     */
    private $ambientTemp;

    /**
     * @var float
     *
     * @ORM\Column(name="AMBIENT_HUM", type="float", precision=24, scale=0, nullable=true)
     */
    private $ambientHum;

    /**
     * @var float
     *
     * @ORM\Column(name="AVG_T_DESIRED", type="float", precision=24, scale=0, nullable=true)
     */
    private $avgTDesired;

    /**
     * @var float
     *
     * @ORM\Column(name="AVG_T_INITIAL", type="float", precision=24, scale=0, nullable=true)
     */
    private $avgTInitial;

    /**
     * @var float
     *
     * @ORM\Column(name="APPROX_DWELLING_TIME", type="float", precision=24, scale=0, nullable=true)
     */
    private $approxDwellingTime;

    /**
     * @var \Studies
     *
     * @ORM\ManyToOne(targetEntity="Studies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_STUDY", referencedColumnName="ID_STUDY")
     * })
     */
    private $idStudy;
    /**
    * 
    @ORM\OneToMany(targetEntity="InitialTemperature", mappedBy="idProduction")      
    */     
    private $idInitialTemp;


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
     * @return float
     */
    public function getDailyProd()
    {
        return $this->dailyProd;
    }

    /**
     * @param float $dailyProd
     *
     * @return self
     */
    public function setDailyProd($dailyProd)
    {
        $this->dailyProd = $dailyProd;

        return $this;
    }

    /**
     * @return float
     */
    public function getDailyStartup()
    {
        return $this->dailyStartup;
    }

    /**
     * @param float $dailyStartup
     *
     * @return self
     */
    public function setDailyStartup($dailyStartup)
    {
        $this->dailyStartup = $dailyStartup;

        return $this;
    }

    /**
     * @return float
     */
    public function getWeeklyProd()
    {
        return $this->weeklyProd;
    }

    /**
     * @param float $weeklyProd
     *
     * @return self
     */
    public function setWeeklyProd($weeklyProd)
    {
        $this->weeklyProd = $weeklyProd;

        return $this;
    }

    /**
     * @return float
     */
    public function getProdFlowRate()
    {
        return $this->prodFlowRate;
    }

    /**
     * @param float $prodFlowRate
     *
     * @return self
     */
    public function setProdFlowRate($prodFlowRate)
    {
        $this->prodFlowRate = $prodFlowRate;

        return $this;
    }

    /**
     * @return float
     */
    public function getNbProdWeekPerYear()
    {
        return $this->nbProdWeekPerYear;
    }

    /**
     * @param float $nbProdWeekPerYear
     *
     * @return self
     */
    public function setNbProdWeekPerYear($nbProdWeekPerYear)
    {
        $this->nbProdWeekPerYear = $nbProdWeekPerYear;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmbientTemp()
    {
        return $this->ambientTemp;
    }

    /**
     * @param float $ambientTemp
     *
     * @return self
     */
    public function setAmbientTemp($ambientTemp)
    {
        $this->ambientTemp = $ambientTemp;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmbientHum()
    {
        return $this->ambientHum;
    }

    /**
     * @param float $ambientHum
     *
     * @return self
     */
    public function setAmbientHum($ambientHum)
    {
        $this->ambientHum = $ambientHum;

        return $this;
    }

    /**
     * @return float
     */
    public function getAvgTDesired()
    {
        return $this->avgTDesired;
    }

    /**
     * @param float $avgTDesired
     *
     * @return self
     */
    public function setAvgTDesired($avgTDesired)
    {
        $this->avgTDesired = $avgTDesired;

        return $this;
    }

    /**
     * @return float
     */
    public function getAvgTInitial()
    {
        return $this->avgTInitial;
    }

    /**
     * @param float $avgTInitial
     *
     * @return self
     */
    public function setAvgTInitial($avgTInitial)
    {
        $this->avgTInitial = $avgTInitial;

        return $this;
    }

    /**
     * @return float
     */
    public function getApproxDwellingTime()
    {
        return $this->approxDwellingTime;
    }

    /**
     * @param float $approxDwellingTime
     *
     * @return self
     */
    public function setApproxDwellingTime($approxDwellingTime)
    {
        $this->approxDwellingTime = $approxDwellingTime;

        return $this;
    }

    /**
     * @return \Studies
     */
    public function getIdStudy()
    {
        return $this->idStudy;
    }

    /**
     * @param \Studies $idStudy
     *
     * @return self
     */
    public function setIdStudy(Studies $idStudy)
    {
        $this->idStudy = $idStudy;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getIdInitialTemp()
    {
        return $this->idInitialTemp;
    }

    /**
     * @param mixed $idInitialTemp
     *
     * @return self
     */
    public function setIdInitialTemp($idInitialTemp)
    {
        $this->idInitialTemp = $idInitialTemp;

        return $this;
    }
}

