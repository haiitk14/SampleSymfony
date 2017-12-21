<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoadLosses
 *
 * @ORM\Table(name="load_losses", indexes={@ORM\Index(name="FK_LOAD_LOSSES_COOLING_FAMILY", columns={"ID_COOLING_FAMILY"})})
 * @ORM\Entity
 */
class LoadLosses
{
    /**
     * @var float
     *
     * @ORM\Column(name="FLOW_RATE", type="float", precision=24, scale=0, nullable=true)
     */
    private $flowRate;

    /**
     * @var float
     *
     * @ORM\Column(name="LINE_DIAMETER", type="float", precision=10, scale=0, nullable=true)
     */
    private $lineDiameter;

    /**
     * @var float
     *
     * @ORM\Column(name="LOSSES", type="float", precision=24, scale=0, nullable=true)
     */
    private $losses;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_LOAD_LOSSES", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLoadLosses;

    /**
     * @var \AppBundle\Entity\CoolingFamily
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CoolingFamily")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_COOLING_FAMILY", referencedColumnName="ID_COOLING_FAMILY")
     * })
     */
    private $idCoolingFamily;


}

