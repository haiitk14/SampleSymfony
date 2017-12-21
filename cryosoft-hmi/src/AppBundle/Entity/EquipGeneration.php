<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipGeneration
 *
 * @ORM\Table(name="equip_generation", indexes={@ORM\Index(name="FK_EQUIP_GENERATION_EQUIPMENT", columns={"ID_EQUIP"})})
 * @ORM\Entity
 */
class EquipGeneration
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_ORIG_EQUIP1", type="integer", nullable=true)
     */
    private $idOrigEquip1;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_ORIG_EQUIP2", type="integer", nullable=true)
     */
    private $idOrigEquip2;

    /**
     * @var float
     *
     * @ORM\Column(name="AVG_PRODINTEMP", type="float", precision=10, scale=0, nullable=true)
     */
    private $avgProdintemp;

    /**
     * @var float
     *
     * @ORM\Column(name="TEMP_SETPOINT", type="float", precision=10, scale=0, nullable=true)
     */
    private $tempSetpoint;

    /**
     * @var float
     *
     * @ORM\Column(name="DWELLING_TIME", type="float", precision=10, scale=0, nullable=true)
     */
    private $dwellingTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="MOVING_CHANGE", type="smallint", nullable=true)
     */
    private $movingChange;

    /**
     * @var float
     *
     * @ORM\Column(name="MOVING_POS", type="float", precision=10, scale=0, nullable=true)
     */
    private $movingPos;

    /**
     * @var integer
     *
     * @ORM\Column(name="ROTATE", type="smallint", nullable=true)
     */
    private $rotate;

    /**
     * @var integer
     *
     * @ORM\Column(name="POS_CHANGE", type="smallint", nullable=true)
     */
    private $posChange;

    /**
     * @var float
     *
     * @ORM\Column(name="NEW_POS", type="float", precision=10, scale=0, nullable=true)
     */
    private $newPos;

    /**
     * @var integer
     *
     * @ORM\Column(name="EQP_GEN_STATUS", type="integer", nullable=true)
     */
    private $eqpGenStatus;

    /**
     * @var float
     *
     * @ORM\Column(name="EQP_GEN_LOADRATE", type="float", precision=10, scale=0, nullable=true)
     */
    private $eqpGenLoadrate;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_EQUIPGENERATION", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEquipgeneration;

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
     * @return integer
     */
    public function getIdOrigEquip1()
    {
        return $this->idOrigEquip1;
    }

    /**
     * @param integer $idOrigEquip1
     *
     * @return self
     */
    public function setIdOrigEquip1($idOrigEquip1)
    {
        $this->idOrigEquip1 = $idOrigEquip1;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdOrigEquip2()
    {
        return $this->idOrigEquip2;
    }

    /**
     * @param integer $idOrigEquip2
     *
     * @return self
     */
    public function setIdOrigEquip2($idOrigEquip2)
    {
        $this->idOrigEquip2 = $idOrigEquip2;

        return $this;
    }

    /**
     * @return float
     */
    public function getAvgProdintemp()
    {
        return $this->avgProdintemp;
    }

    /**
     * @param float $avgProdintemp
     *
     * @return self
     */
    public function setAvgProdintemp($avgProdintemp)
    {
        $this->avgProdintemp = $avgProdintemp;

        return $this;
    }

    /**
     * @return float
     */
    public function getTempSetpoint()
    {
        return $this->tempSetpoint;
    }

    /**
     * @param float $tempSetpoint
     *
     * @return self
     */
    public function setTempSetpoint($tempSetpoint)
    {
        $this->tempSetpoint = $tempSetpoint;

        return $this;
    }

    /**
     * @return float
     */
    public function getDwellingTime()
    {
        return $this->dwellingTime;
    }

    /**
     * @param float $dwellingTime
     *
     * @return self
     */
    public function setDwellingTime($dwellingTime)
    {
        $this->dwellingTime = $dwellingTime;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMovingChange()
    {
        return $this->movingChange;
    }

    /**
     * @param integer $movingChange
     *
     * @return self
     */
    public function setMovingChange($movingChange)
    {
        $this->movingChange = $movingChange;

        return $this;
    }

    /**
     * @return float
     */
    public function getMovingPos()
    {
        return $this->movingPos;
    }

    /**
     * @param float $movingPos
     *
     * @return self
     */
    public function setMovingPos($movingPos)
    {
        $this->movingPos = $movingPos;

        return $this;
    }

    /**
     * @return integer
     */
    public function getRotate()
    {
        return $this->rotate;
    }

    /**
     * @param integer $rotate
     *
     * @return self
     */
    public function setRotate($rotate)
    {
        $this->rotate = $rotate;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPosChange()
    {
        return $this->posChange;
    }

    /**
     * @param integer $posChange
     *
     * @return self
     */
    public function setPosChange($posChange)
    {
        $this->posChange = $posChange;

        return $this;
    }

    /**
     * @return float
     */
    public function getNewPos()
    {
        return $this->newPos;
    }

    /**
     * @param float $newPos
     *
     * @return self
     */
    public function setNewPos($newPos)
    {
        $this->newPos = $newPos;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEqpGenStatus()
    {
        return $this->eqpGenStatus;
    }

    /**
     * @param integer $eqpGenStatus
     *
     * @return self
     */
    public function setEqpGenStatus($eqpGenStatus)
    {
        $this->eqpGenStatus = $eqpGenStatus;

        return $this;
    }

    /**
     * @return float
     */
    public function getEqpGenLoadrate()
    {
        return $this->eqpGenLoadrate;
    }

    /**
     * @param float $eqpGenLoadrate
     *
     * @return self
     */
    public function setEqpGenLoadrate($eqpGenLoadrate)
    {
        $this->eqpGenLoadrate = $eqpGenLoadrate;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdEquipgeneration()
    {
        return $this->idEquipgeneration;
    }

    /**
     * @param integer $idEquipgeneration
     *
     * @return self
     */
    public function setIdEquipgeneration($idEquipgeneration)
    {
        $this->idEquipgeneration = $idEquipgeneration;

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
}

