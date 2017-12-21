<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudyResults
 *
 * @ORM\Table(name="study_results", indexes={@ORM\Index(name="FK_STUDY_RESULTS_STUDIES", columns={"ID_STUDY"})})
 * @ORM\Entity
 */
class StudyResults
{
    /**
     * @var integer
     *
     * @ORM\Column(name="BEST_EQUIPMENT", type="integer", nullable=true)
     */
    private $bestEquipment;

    /**
     * @var float
     *
     * @ORM\Column(name="TOTAL_DWELLINGTIME", type="float", precision=10, scale=0, nullable=true)
     */
    private $totalDwellingtime;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_STUDY_RESULTS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStudyResults;

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

