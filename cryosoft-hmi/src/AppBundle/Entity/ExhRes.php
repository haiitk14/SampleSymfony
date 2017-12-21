<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExhRes
 *
 * @ORM\Table(name="exh_res", indexes={@ORM\Index(name="FK_EXH_RES_STUDY_EQUIPMENTS", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class ExhRes
{
    /**
     * @var float
     *
     * @ORM\Column(name="DILUTION_AIR_ENTH", type="float", precision=10, scale=0, nullable=true)
     */
    private $dilutionAirEnth;

    /**
     * @var float
     *
     * @ORM\Column(name="MIXTURE_ENTH", type="float", precision=10, scale=0, nullable=true)
     */
    private $mixtureEnth;

    /**
     * @var float
     *
     * @ORM\Column(name="CRYOGEN_ENTH_VARIATION", type="float", precision=10, scale=0, nullable=true)
     */
    private $cryogenEnthVariation;

    /**
     * @var float
     *
     * @ORM\Column(name="GAS_CRYOGEN_FLOW_RATE", type="float", precision=10, scale=0, nullable=true)
     */
    private $gasCryogenFlowRate;

    /**
     * @var float
     *
     * @ORM\Column(name="DILUTION_AIR_FLOW_RATE", type="float", precision=10, scale=0, nullable=true)
     */
    private $dilutionAirFlowRate;

    /**
     * @var float
     *
     * @ORM\Column(name="TOTAL_FLOW_RATE", type="float", precision=10, scale=0, nullable=true)
     */
    private $totalFlowRate;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_EXH_RES", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idExhRes;

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

