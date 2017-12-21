<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrecalcLdgRate
 *
 * @ORM\Table(name="precalc_ldg_rate", indexes={@ORM\Index(name="FK_PRECALC_LDG_RATE_EQUIPMENT", columns={"ID_EQUIP"}), @ORM\Index(name="FK_PRECALC_LDG_RATE_STUDIES", columns={"ID_STUDY"})})
 * @ORM\Entity
 */
class PrecalcLdgRate
{
    /**
     * @var float
     *
     * @ORM\Column(name="DEF_LOADING_RATE", type="float", precision=10, scale=0, nullable=true)
     */
    private $defLoadingRate;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PRECALC_LDG_RATE", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPrecalcLdgRate;

    /**
     * @var \AppBundle\Entity\Studies
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Studies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_STUDY", referencedColumnName="ID_STUDY")
     * })
     */
    private $idStudy;

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

