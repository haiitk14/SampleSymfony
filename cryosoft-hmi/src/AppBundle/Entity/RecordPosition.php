<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecordPosition
 *
 * @ORM\Table(name="record_position", indexes={@ORM\Index(name="IX_ID_STUDY_EQP", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class RecordPosition
{
    /**
     * @var float
     *
     * @ORM\Column(name="RECORD_TIME", type="float", precision=24, scale=0, nullable=true)
     */
    private $recordTime;

    /**
     * @var float
     *
     * @ORM\Column(name="AVERAGE_TEMP", type="float", precision=10, scale=0, nullable=true)
     */
    private $averageTemp;

    /**
     * @var float
     *
     * @ORM\Column(name="AVERAGE_ENTH_VAR", type="float", precision=10, scale=0, nullable=true)
     */
    private $averageEnthVar;

    /**
     * @var float
     *
     * @ORM\Column(name="ENTHALPY_VAR", type="float", precision=10, scale=0, nullable=true)
     */
    private $enthalpyVar;

    /**
     * @var boolean
     *
     * @ORM\Column(name="RECORD_BUFFER", type="boolean", nullable=true)
     */
    private $recordBuffer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="RECORD_STATE", type="boolean", nullable=true)
     */
    private $recordState;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_REC_POS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRecPos;

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

