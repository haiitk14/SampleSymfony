<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PipeRes
 *
 * @ORM\Table(name="pipe_res", indexes={@ORM\Index(name="FK_PIPE_RES_STUDY_EQUIPMENTS", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class PipeRes
{
    /**
     * @var float
     *
     * @ORM\Column(name="EQUIVAL_LEN", type="float", precision=24, scale=0, nullable=true)
     */
    private $equivalLen;

    /**
     * @var float
     *
     * @ORM\Column(name="FLUID_FLOW", type="float", precision=24, scale=0, nullable=true)
     */
    private $fluidFlow;

    /**
     * @var float
     *
     * @ORM\Column(name="HEAT_ENTRY", type="float", precision=24, scale=0, nullable=true)
     */
    private $heatEntry;

    /**
     * @var float
     *
     * @ORM\Column(name="LOAD_LOSS", type="float", precision=24, scale=0, nullable=true)
     */
    private $loadLoss;

    /**
     * @var float
     *
     * @ORM\Column(name="DIPHASIQ", type="float", precision=24, scale=0, nullable=true)
     */
    private $diphasiq;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PIPE_RES", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPipeRes;

    /**
     * @var \AppBundle\Entity\StudyEquipments
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\StudyEquipments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_STUDY_EQUIPMENTS", referencedColumnName="ID_STUDY_EQUIPMENTS")
     * })
     */
    private $idStudyEquipments;


}

