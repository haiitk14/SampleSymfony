<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExhGen
 *
 * @ORM\Table(name="exh_gen", indexes={@ORM\Index(name="FK_EXH_GEN_STUDY_EQUIPMENTS", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class ExhGen
{
    /**
     * @var float
     *
     * @ORM\Column(name="DILUTION_AIR_TEMP", type="float", precision=10, scale=0, nullable=true)
     */
    private $dilutionAirTemp;

    /**
     * @var float
     *
     * @ORM\Column(name="DILUTION_AIR_HUMIDITY", type="float", precision=10, scale=0, nullable=true)
     */
    private $dilutionAirHumidity;

    /**
     * @var float
     *
     * @ORM\Column(name="MIXTURE_TEMP_DESIRED", type="float", precision=10, scale=0, nullable=true)
     */
    private $mixtureTempDesired;

    /**
     * @var float
     *
     * @ORM\Column(name="HEATING_POWER", type="float", precision=10, scale=0, nullable=true)
     */
    private $heatingPower;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_EXH_GEN", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idExhGen;

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

