<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DimaResults
 *
 * @ORM\Table(name="dima_results", indexes={@ORM\Index(name="FK_DIMA_RESULTS_STUDY_EQUIPMENTS", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class DimaResults
{
    /**
     * @var float
     *
     * @ORM\Column(name="SETPOINT", type="float", precision=10, scale=0, nullable=true)
     */
    private $setpoint;

    /**
     * @var integer
     *
     * @ORM\Column(name="DIMA_STATUS", type="integer", nullable=true)
     */
    private $dimaStatus;

    /**
     * @var float
     *
     * @ORM\Column(name="DIMA_TS", type="float", precision=10, scale=0, nullable=true)
     */
    private $dimaTs;

    /**
     * @var float
     *
     * @ORM\Column(name="DIMA_TFP", type="float", precision=10, scale=0, nullable=true)
     */
    private $dimaTfp;

    /**
     * @var float
     *
     * @ORM\Column(name="DIMA_VEP", type="float", precision=10, scale=0, nullable=true)
     */
    private $dimaVep;

    /**
     * @var float
     *
     * @ORM\Column(name="DIMA_VC", type="float", precision=10, scale=0, nullable=true)
     */
    private $dimaVc;

    /**
     * @var integer
     *
     * @ORM\Column(name="DIMA_TYPE", type="smallint", nullable=true)
     */
    private $dimaType;

    /**
     * @var float
     *
     * @ORM\Column(name="DIMA_PRECIS", type="float", precision=10, scale=0, nullable=true)
     */
    private $dimaPrecis;

    /**
     * @var float
     *
     * @ORM\Column(name="CRYOCONSPROD", type="float", precision=10, scale=0, nullable=true)
     */
    private $cryoconsprod;

    /**
     * @var float
     *
     * @ORM\Column(name="HOURLYOUTPUTMAX", type="float", precision=10, scale=0, nullable=true)
     */
    private $hourlyoutputmax;

    /**
     * @var float
     *
     * @ORM\Column(name="CONSUM", type="float", precision=10, scale=0, nullable=true)
     */
    private $consum;

    /**
     * @var float
     *
     * @ORM\Column(name="USERATE", type="float", precision=10, scale=0, nullable=true)
     */
    private $userate;

    /**
     * @var float
     *
     * @ORM\Column(name="CONSUMMAX", type="float", precision=10, scale=0, nullable=true)
     */
    private $consummax;

    /**
     * @var float
     *
     * @ORM\Column(name="USERATEMAX", type="float", precision=10, scale=0, nullable=true)
     */
    private $useratemax;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_DIMA_RESULTS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDimaResults;

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
    public function getSetpoint()
    {
        return $this->setpoint;
    }

    /**
     * @param float $setpoint
     *
     * @return self
     */
    public function setSetpoint($setpoint)
    {
        $this->setpoint = $setpoint;

        return $this;
    }

    /**
     * @return integer
     */
    public function getDimaStatus()
    {
        return $this->dimaStatus;
    }

    /**
     * @param integer $dimaStatus
     *
     * @return self
     */
    public function setDimaStatus($dimaStatus)
    {
        $this->dimaStatus = $dimaStatus;

        return $this;
    }

    /**
     * @return float
     */
    public function getDimaTs()
    {
        return $this->dimaTs;
    }

    /**
     * @param float $dimaTs
     *
     * @return self
     */
    public function setDimaTs($dimaTs)
    {
        $this->dimaTs = $dimaTs;

        return $this;
    }

    /**
     * @return float
     */
    public function getDimaTfp()
    {
        return $this->dimaTfp;
    }

    /**
     * @param float $dimaTfp
     *
     * @return self
     */
    public function setDimaTfp($dimaTfp)
    {
        $this->dimaTfp = $dimaTfp;

        return $this;
    }

    /**
     * @return float
     */
    public function getDimaVep()
    {
        return $this->dimaVep;
    }

    /**
     * @param float $dimaVep
     *
     * @return self
     */
    public function setDimaVep($dimaVep)
    {
        $this->dimaVep = $dimaVep;

        return $this;
    }

    /**
     * @return float
     */
    public function getDimaVc()
    {
        return $this->dimaVc;
    }

    /**
     * @param float $dimaVc
     *
     * @return self
     */
    public function setDimaVc($dimaVc)
    {
        $this->dimaVc = $dimaVc;

        return $this;
    }

    /**
     * @return integer
     */
    public function getDimaType()
    {
        return $this->dimaType;
    }

    /**
     * @param integer $dimaType
     *
     * @return self
     */
    public function setDimaType($dimaType)
    {
        $this->dimaType = $dimaType;

        return $this;
    }

    /**
     * @return float
     */
    public function getDimaPrecis()
    {
        return $this->dimaPrecis;
    }

    /**
     * @param float $dimaPrecis
     *
     * @return self
     */
    public function setDimaPrecis($dimaPrecis)
    {
        $this->dimaPrecis = $dimaPrecis;

        return $this;
    }

    /**
     * @return float
     */
    public function getCryoconsprod()
    {
        return $this->cryoconsprod;
    }

    /**
     * @param float $cryoconsprod
     *
     * @return self
     */
    public function setCryoconsprod($cryoconsprod)
    {
        $this->cryoconsprod = $cryoconsprod;

        return $this;
    }

    /**
     * @return float
     */
    public function getHourlyoutputmax()
    {
        return $this->hourlyoutputmax;
    }

    /**
     * @param float $hourlyoutputmax
     *
     * @return self
     */
    public function setHourlyoutputmax($hourlyoutputmax)
    {
        $this->hourlyoutputmax = $hourlyoutputmax;

        return $this;
    }

    /**
     * @return float
     */
    public function getConsum()
    {
        return $this->consum;
    }

    /**
     * @param float $consum
     *
     * @return self
     */
    public function setConsum($consum)
    {
        $this->consum = $consum;

        return $this;
    }

    /**
     * @return float
     */
    public function getUserate()
    {
        return $this->userate;
    }

    /**
     * @param float $userate
     *
     * @return self
     */
    public function setUserate($userate)
    {
        $this->userate = $userate;

        return $this;
    }

    /**
     * @return float
     */
    public function getConsummax()
    {
        return $this->consummax;
    }

    /**
     * @param float $consummax
     *
     * @return self
     */
    public function setConsummax($consummax)
    {
        $this->consummax = $consummax;

        return $this;
    }

    /**
     * @return float
     */
    public function getUseratemax()
    {
        return $this->useratemax;
    }

    /**
     * @param float $useratemax
     *
     * @return self
     */
    public function setUseratemax($useratemax)
    {
        $this->useratemax = $useratemax;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdDimaResults()
    {
        return $this->idDimaResults;
    }

    /**
     * @param integer $idDimaResults
     *
     * @return self
     */
    public function setIdDimaResults($idDimaResults)
    {
        $this->idDimaResults = $idDimaResults;

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

