<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shelves
 *
 * @ORM\Table(name="shelves", indexes={@ORM\Index(name="FK_SHELVES_EQUIPMENT", columns={"ID_EQUIP"})})
 * @ORM\Entity
 */
class Shelves
{
    /**
     * @var float
     *
     * @ORM\Column(name="SPACE", type="float", precision=10, scale=0, nullable=true)
     */
    private $space;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB", type="integer", nullable=true)
     */
    private $nb;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_SHELVES", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idShelves;

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

