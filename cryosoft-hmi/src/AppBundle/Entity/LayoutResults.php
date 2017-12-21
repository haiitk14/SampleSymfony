<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LayoutResults
 *
 * @ORM\Table(name="layout_results", indexes={@ORM\Index(name="FK_LAYOUT_RESULTS_STUDY_EQUIPMENTS", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class LayoutResults
{
    /**
     * @var float
     *
     * @ORM\Column(name="NUMBER_PER_M", type="float", precision=24, scale=0, nullable=true)
     */
    private $numberPerM;

    /**
     * @var integer
     *
     * @ORM\Column(name="NUMBER_IN_WIDTH", type="integer", nullable=true)
     */
    private $numberInWidth;

    /**
     * @var float
     *
     * @ORM\Column(name="LEFT_RIGHT_INTERVAL", type="float", precision=10, scale=0, nullable=true)
     */
    private $leftRightInterval;

    /**
     * @var float
     *
     * @ORM\Column(name="LOADING_RATE", type="float", precision=10, scale=0, nullable=true)
     */
    private $loadingRate;

    /**
     * @var float
     *
     * @ORM\Column(name="QUANTITY_PER_BATCH", type="float", precision=10, scale=0, nullable=true)
     */
    private $quantityPerBatch;

    /**
     * @var float
     *
     * @ORM\Column(name="LOADING_RATE_MAX", type="float", precision=10, scale=0, nullable=true)
     */
    private $loadingRateMax;

    /**
     * @var float
     *
     * @ORM\Column(name="QUANTITY_PER_BATCH_MAX", type="float", precision=10, scale=0, nullable=true)
     */
    private $quantityPerBatchMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB_SHELVES", type="smallint", nullable=true)
     */
    private $nbShelves;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB_SHELVES_MAX", type="smallint", nullable=true)
     */
    private $nbShelvesMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_LAYOUT_RESULTS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLayoutResults;

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
    public function getNumberPerM()
    {
        return $this->numberPerM;
    }

    /**
     * @param float $numberPerM
     *
     * @return self
     */
    public function setNumberPerM($numberPerM)
    {
        $this->numberPerM = $numberPerM;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNumberInWidth()
    {
        return $this->numberInWidth;
    }

    /**
     * @param integer $numberInWidth
     *
     * @return self
     */
    public function setNumberInWidth($numberInWidth)
    {
        $this->numberInWidth = $numberInWidth;

        return $this;
    }

    /**
     * @return float
     */
    public function getLeftRightInterval()
    {
        return $this->leftRightInterval;
    }

    /**
     * @param float $leftRightInterval
     *
     * @return self
     */
    public function setLeftRightInterval($leftRightInterval)
    {
        $this->leftRightInterval = $leftRightInterval;

        return $this;
    }

    /**
     * @return float
     */
    public function getLoadingRate()
    {
        return $this->loadingRate;
    }

    /**
     * @param float $loadingRate
     *
     * @return self
     */
    public function setLoadingRate($loadingRate)
    {
        $this->loadingRate = $loadingRate;

        return $this;
    }

    /**
     * @return float
     */
    public function getQuantityPerBatch()
    {
        return $this->quantityPerBatch;
    }

    /**
     * @param float $quantityPerBatch
     *
     * @return self
     */
    public function setQuantityPerBatch($quantityPerBatch)
    {
        $this->quantityPerBatch = $quantityPerBatch;

        return $this;
    }

    /**
     * @return float
     */
    public function getLoadingRateMax()
    {
        return $this->loadingRateMax;
    }

    /**
     * @param float $loadingRateMax
     *
     * @return self
     */
    public function setLoadingRateMax($loadingRateMax)
    {
        $this->loadingRateMax = $loadingRateMax;

        return $this;
    }

    /**
     * @return float
     */
    public function getQuantityPerBatchMax()
    {
        return $this->quantityPerBatchMax;
    }

    /**
     * @param float $quantityPerBatchMax
     *
     * @return self
     */
    public function setQuantityPerBatchMax($quantityPerBatchMax)
    {
        $this->quantityPerBatchMax = $quantityPerBatchMax;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbShelves()
    {
        return $this->nbShelves;
    }

    /**
     * @param integer $nbShelves
     *
     * @return self
     */
    public function setNbShelves($nbShelves)
    {
        $this->nbShelves = $nbShelves;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbShelvesMax()
    {
        return $this->nbShelvesMax;
    }

    /**
     * @param integer $nbShelvesMax
     *
     * @return self
     */
    public function setNbShelvesMax($nbShelvesMax)
    {
        $this->nbShelvesMax = $nbShelvesMax;

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

