<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Consumptions
 *
 * @ORM\Table(name="consumptions", indexes={@ORM\Index(name="FK_CONSUMPTIONS_EQUIPMENT", columns={"ID_EQUIP"})})
 * @ORM\Entity
 */
class Consumptions
{
    /**
     * @var binary
     *
     * @ORM\Column(name="TEMPERATURE", type="binary", nullable=true)
     */
    private $temperature;

    /**
     * @var binary
     *
     * @ORM\Column(name="CONSUMPTION_PERM", type="binary", nullable=true)
     */
    private $consumptionPerm;

    /**
     * @var binary
     *
     * @ORM\Column(name="CONSUMPTION_GETCOLD", type="binary", nullable=true)
     */
    private $consumptionGetcold;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_CONSUMPTIONS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idConsumptions;

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

