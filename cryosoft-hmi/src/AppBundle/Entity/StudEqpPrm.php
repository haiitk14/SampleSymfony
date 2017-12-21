<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudEqpPrm
 *
 * @ORM\Table(name="stud_eqp_prm", indexes={@ORM\Index(name="FK_STUD_EQP_PRM_STUDY_EQUIPMENTS", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class StudEqpPrm
{
    /**
     * @var integer
     *
     * @ORM\Column(name="VALUE_TYPE", type="smallint", nullable=true)
     */
    private $valueType;

    /**
     * @var float
     *
     * @ORM\Column(name="VALUE", type="float", precision=10, scale=0, nullable=true)
     */
    private $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_STUD_EQP_PRM", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStudEqpPrm;

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
     * @return integer
     */
    public function getValueType()
    {
        return $this->valueType;
    }

    /**
     * @param integer $valueType
     *
     * @return self
     */
    public function setValueType($valueType)
    {
        $this->valueType = $valueType;

        return $this;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float $value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdStudEquipPrm()
    {
        return $this->idStudEquipPrm;
    }

    /**
     * @param integer $idStudEquipPrm
     *
     * @return self
     */
    public function setIdStudEquipPrm($idStudEquipPrm)
    {
        $this->idStudEquipPrm = $idStudEquipPrm;

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

