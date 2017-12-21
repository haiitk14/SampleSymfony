<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipcharact
 *
 * @ORM\Table(name="equipcharact", indexes={@ORM\Index(name="IX_ID_EQUIP", columns={"ID_EQUIP"})})
 * @ORM\Entity
 */
class Equipcharact
{
    /**
     * @var float
     *
     * @ORM\Column(name="X_POSITION", type="float", precision=10, scale=0, nullable=true)
     */
    private $xPosition;

    /**
     * @var float
     *
     * @ORM\Column(name="TEMP_REGUL", type="float", precision=10, scale=0, nullable=true)
     */
    private $tempRegul;

    /**
     * @var float
     *
     * @ORM\Column(name="ALPHA_TOP", type="float", precision=10, scale=0, nullable=true)
     */
    private $alphaTop;

    /**
     * @var float
     *
     * @ORM\Column(name="ALPHA_BOTTOM", type="float", precision=10, scale=0, nullable=true)
     */
    private $alphaBottom;

    /**
     * @var float
     *
     * @ORM\Column(name="ALPHA_LEFT", type="float", precision=10, scale=0, nullable=true)
     */
    private $alphaLeft;

    /**
     * @var float
     *
     * @ORM\Column(name="ALPHA_RIGHT", type="float", precision=10, scale=0, nullable=true)
     */
    private $alphaRight;

    /**
     * @var float
     *
     * @ORM\Column(name="ALPHA_FRONT", type="float", precision=10, scale=0, nullable=true)
     */
    private $alphaFront;

    /**
     * @var float
     *
     * @ORM\Column(name="ALPHA_REAR", type="float", precision=10, scale=0, nullable=true)
     */
    private $alphaRear;

    /**
     * @var float
     *
     * @ORM\Column(name="TEMP_TOP", type="float", precision=10, scale=0, nullable=true)
     */
    private $tempTop;

    /**
     * @var float
     *
     * @ORM\Column(name="TEMP_BOTTOM", type="float", precision=10, scale=0, nullable=true)
     */
    private $tempBottom;

    /**
     * @var float
     *
     * @ORM\Column(name="TEMP_LEFT", type="float", precision=10, scale=0, nullable=true)
     */
    private $tempLeft;

    /**
     * @var float
     *
     * @ORM\Column(name="TEMP_RIGHT", type="float", precision=10, scale=0, nullable=true)
     */
    private $tempRight;

    /**
     * @var float
     *
     * @ORM\Column(name="TEMP_FRONT", type="float", precision=10, scale=0, nullable=true)
     */
    private $tempFront;

    /**
     * @var float
     *
     * @ORM\Column(name="TEMP_REAR", type="float", precision=10, scale=0, nullable=true)
     */
    private $tempRear;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_EQUIPCHARAC", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEquipcharac;

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

