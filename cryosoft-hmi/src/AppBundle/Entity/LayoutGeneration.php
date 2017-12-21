<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LayoutGeneration
 *
 * @ORM\Table(name="layout_generation", indexes={@ORM\Index(name="FK_LAYOUT_GENERATION_STUDY_EQUIPMENTS", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class LayoutGeneration
{
    /**
     * @var float
     *
     * @ORM\Column(name="WIDTH_INTERVAL", type="float", precision=10, scale=0, nullable=true)
     */
    private $widthInterval;

    /**
     * @var float
     *
     * @ORM\Column(name="LENGTH_INTERVAL", type="float", precision=10, scale=0, nullable=true)
     */
    private $lengthInterval;

    /**
     * @var boolean
     *
     * @ORM\Column(name="PROD_POSITION", type="boolean", nullable=true)
     */
    private $prodPosition;

    /**
     * @var float
     *
     * @ORM\Column(name="SHELVES_WIDTH", type="float", precision=10, scale=0, nullable=true)
     */
    private $shelvesWidth;

    /**
     * @var float
     *
     * @ORM\Column(name="SHELVES_LENGTH", type="float", precision=10, scale=0, nullable=true)
     */
    private $shelvesLength;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB_SHELVES_PERSO", type="smallint", nullable=true)
     */
    private $nbShelvesPerso;

    /**
     * @var integer
     *
     * @ORM\Column(name="SHELVES_TYPE", type="smallint", nullable=true)
     */
    private $shelvesType;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_LAYOUT_GENERATION", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLayoutGeneration;

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
    public function getWidthInterval()
    {
        return $this->widthInterval;
    }

    /**
     * @param float $widthInterval
     *
     * @return self
     */
    public function setWidthInterval($widthInterval)
    {
        $this->widthInterval = $widthInterval;

        return $this;
    }

    /**
     * @return float
     */
    public function getLengthInterval()
    {
        return $this->lengthInterval;
    }

    /**
     * @param float $lengthInterval
     *
     * @return self
     */
    public function setLengthInterval($lengthInterval)
    {
        $this->lengthInterval = $lengthInterval;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isProdPosition()
    {
        return $this->prodPosition;
    }

    /**
     * @param boolean $prodPosition
     *
     * @return self
     */
    public function setProdPosition($prodPosition)
    {
        $this->prodPosition = $prodPosition;

        return $this;
    }

    /**
     * @return float
     */
    public function getShelvesWidth()
    {
        return $this->shelvesWidth;
    }

    /**
     * @param float $shelvesWidth
     *
     * @return self
     */
    public function setShelvesWidth($shelvesWidth)
    {
        $this->shelvesWidth = $shelvesWidth;

        return $this;
    }

    /**
     * @return float
     */
    public function getShelvesLength()
    {
        return $this->shelvesLength;
    }

    /**
     * @param float $shelvesLength
     *
     * @return self
     */
    public function setShelvesLength($shelvesLength)
    {
        $this->shelvesLength = $shelvesLength;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbShelvesPerso()
    {
        return $this->nbShelvesPerso;
    }

    /**
     * @param integer $nbShelvesPerso
     *
     * @return self
     */
    public function setNbShelvesPerso($nbShelvesPerso)
    {
        $this->nbShelvesPerso = $nbShelvesPerso;

        return $this;
    }

    /**
     * @return integer
     */
    public function getShelvesType()
    {
        return $this->shelvesType;
    }

    /**
     * @param integer $shelvesType
     *
     * @return self
     */
    public function setShelvesType($shelvesType)
    {
        $this->shelvesType = $shelvesType;

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
    public function __toString()
    {
        return $this->idLayoutGeneration.'';
    }
}

