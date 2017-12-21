<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipZone
 *
 * @ORM\Table(name="equip_zone", indexes={@ORM\Index(name="FK_EQUIP_ZONE_EQUIPMENT", columns={"ID_EQUIP"})})
 * @ORM\Entity
 */
class EquipZone
{
    /**
     * @var integer
     *
     * @ORM\Column(name="EQUIP_ZONE_NUMBER", type="integer", nullable=true)
     */
    private $equipZoneNumber;

    /**
     * @var float
     *
     * @ORM\Column(name="EQUIP_ZONE_LENGTH", type="float", precision=24, scale=0, nullable=true)
     */
    private $equipZoneLength;

    /**
     * @var string
     *
     * @ORM\Column(name="EQUIP_ZONE_NAME", type="string", length=80, nullable=true)
     */
    private $equipZoneName;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_EQUIP_ZONE", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEquipZone;

    /**
     * @var \AppBundle\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_EQUIP", referencedColumnName="ID_EQUIP")
     * })
     */
    private $idEquip;


}

