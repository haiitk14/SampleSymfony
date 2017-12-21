<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HaverageResults
 *
 * @ORM\Table(name="haverage_results", indexes={@ORM\Index(name="FK_HAVERAGE_RESULTS_STUDIES", columns={"ID_STUDY"})})
 * @ORM\Entity
 */
class HaverageResults
{
    /**
     * @var float
     *
     * @ORM\Column(name="AVG_TEMP", type="float", precision=10, scale=0, nullable=true)
     */
    private $avgTemp;

    /**
     * @var float
     *
     * @ORM\Column(name="ENTHALPY", type="float", precision=10, scale=0, nullable=true)
     */
    private $enthalpy;

    /**
     * @var float
     *
     * @ORM\Column(name="CONDUCTIVITY", type="float", precision=10, scale=0, nullable=true)
     */
    private $conductivity;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_HAVERAGE_RESULTS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idHaverageResults;

    /**
     * @var \AppBundle\Entity\Studies
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Studies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_STUDY", referencedColumnName="ID_STUDY")
     * })
     */
    private $idStudy;


}

