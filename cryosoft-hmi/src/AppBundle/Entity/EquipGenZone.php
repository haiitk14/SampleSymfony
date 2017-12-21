<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipGenZone
 *
 * @ORM\Table(name="equip_gen_zone", indexes={@ORM\Index(name="FK_EQUIP_GEN_ZONE_EQUIP_GENERATION", columns={"ID_EQUIPGENERATION"})})
 * @ORM\Entity
 */
class EquipGenZone
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ZONE_NUMBER", type="integer", nullable=true)
     */
    private $zoneNumber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TEMP_SENSOR", type="boolean", nullable=true)
     */
    private $tempSensor;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TOP_ADIABAT", type="boolean", nullable=true)
     */
    private $topAdiabat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="BOTTOM_ADIABAT", type="boolean", nullable=true)
     */
    private $bottomAdiabat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="LEFT_ADIABAT", type="boolean", nullable=true)
     */
    private $leftAdiabat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="RIGHT_ADIABAT", type="boolean", nullable=true)
     */
    private $rightAdiabat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="FRONT_ADIABAT", type="boolean", nullable=true)
     */
    private $frontAdiabat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="REAR_ADIABAT", type="boolean", nullable=true)
     */
    private $rearAdiabat;

    /**
     * @var integer
     *
     * @ORM\Column(name="TOP_CHANGE", type="smallint", nullable=true)
     */
    private $topChange;

    /**
     * @var float
     *
     * @ORM\Column(name="TOP_PRM1", type="float", precision=10, scale=0, nullable=true)
     */
    private $topPrm1;

    /**
     * @var float
     *
     * @ORM\Column(name="TOP_PRM2", type="float", precision=10, scale=0, nullable=true)
     */
    private $topPrm2;

    /**
     * @var float
     *
     * @ORM\Column(name="TOP_PRM3", type="float", precision=10, scale=0, nullable=true)
     */
    private $topPrm3;

    /**
     * @var integer
     *
     * @ORM\Column(name="BOTTOM_CHANGE", type="smallint", nullable=true)
     */
    private $bottomChange;

    /**
     * @var float
     *
     * @ORM\Column(name="BOTTOM_PRM1", type="float", precision=10, scale=0, nullable=true)
     */
    private $bottomPrm1;

    /**
     * @var float
     *
     * @ORM\Column(name="BOTTOM_PRM2", type="float", precision=10, scale=0, nullable=true)
     */
    private $bottomPrm2;

    /**
     * @var float
     *
     * @ORM\Column(name="BOTTOM_PRM3", type="float", precision=10, scale=0, nullable=true)
     */
    private $bottomPrm3;

    /**
     * @var integer
     *
     * @ORM\Column(name="LEFT_CHANGE", type="smallint", nullable=true)
     */
    private $leftChange;

    /**
     * @var float
     *
     * @ORM\Column(name="LEFT_PRM1", type="float", precision=10, scale=0, nullable=true)
     */
    private $leftPrm1;

    /**
     * @var float
     *
     * @ORM\Column(name="LEFT_PRM2", type="float", precision=10, scale=0, nullable=true)
     */
    private $leftPrm2;

    /**
     * @var float
     *
     * @ORM\Column(name="LEFT_PRM3", type="float", precision=10, scale=0, nullable=true)
     */
    private $leftPrm3;

    /**
     * @var integer
     *
     * @ORM\Column(name="RIGHT_CHANGE", type="smallint", nullable=true)
     */
    private $rightChange;

    /**
     * @var float
     *
     * @ORM\Column(name="RIGHT_PRM1", type="float", precision=10, scale=0, nullable=true)
     */
    private $rightPrm1;

    /**
     * @var float
     *
     * @ORM\Column(name="RIGHT_PRM2", type="float", precision=10, scale=0, nullable=true)
     */
    private $rightPrm2;

    /**
     * @var float
     *
     * @ORM\Column(name="RIGHT_PRM3", type="float", precision=10, scale=0, nullable=true)
     */
    private $rightPrm3;

    /**
     * @var integer
     *
     * @ORM\Column(name="FRONT_CHANGE", type="smallint", nullable=true)
     */
    private $frontChange;

    /**
     * @var float
     *
     * @ORM\Column(name="FRONT_PRM1", type="float", precision=10, scale=0, nullable=true)
     */
    private $frontPrm1;

    /**
     * @var float
     *
     * @ORM\Column(name="FRONT_PRM2", type="float", precision=10, scale=0, nullable=true)
     */
    private $frontPrm2;

    /**
     * @var float
     *
     * @ORM\Column(name="FRONT_PRM3", type="float", precision=10, scale=0, nullable=true)
     */
    private $frontPrm3;

    /**
     * @var integer
     *
     * @ORM\Column(name="REAR_CHANGE", type="smallint", nullable=true)
     */
    private $rearChange;

    /**
     * @var float
     *
     * @ORM\Column(name="REAR_PRM1", type="float", precision=10, scale=0, nullable=true)
     */
    private $rearPrm1;

    /**
     * @var float
     *
     * @ORM\Column(name="REAR_PRM2", type="float", precision=10, scale=0, nullable=true)
     */
    private $rearPrm2;

    /**
     * @var float
     *
     * @ORM\Column(name="REAR_PRM3", type="float", precision=10, scale=0, nullable=true)
     */
    private $rearPrm3;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_EQUIP_GEN_ZONE", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEquipGenZone;

    /**
     * @var \AppBundle\Entity\EquipGeneration
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EquipGeneration")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_EQUIPGENERATION", referencedColumnName="ID_EQUIPGENERATION")
     * })
     */
    private $idEquipgeneration;


}

