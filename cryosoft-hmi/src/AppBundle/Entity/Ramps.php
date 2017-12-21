<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ramps
 *
 * @ORM\Table(name="ramps", indexes={@ORM\Index(name="FK_RAMPS_EQUIPMENT", columns={"ID_EQUIP"})})
 * @ORM\Entity
 */
class Ramps
{
    /**
     * @var float
     *
     * @ORM\Column(name="POSITION", type="float", precision=10, scale=0, nullable=true)
     */
    private $position;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_RAMPS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRamps;

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

