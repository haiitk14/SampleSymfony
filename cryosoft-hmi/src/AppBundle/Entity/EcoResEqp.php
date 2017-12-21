<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcoResEqp
 *
 * @ORM\Table(name="eco_res_eqp", indexes={@ORM\Index(name="FK_ECO_RES_EQP_STUDY_EQUIPMENTS", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class EcoResEqp
{
    /**
     * @var float
     *
     * @ORM\Column(name="ECO_RES_EQP_MP", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoResEqpMp;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_RES_EQP_TC", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoResEqpTc;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_RES_EQP_CC1", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoResEqpCc1;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_RES_EQP_CC2", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoResEqpCc2;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_RES_EQP_CC3", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoResEqpCc3;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_RES_EQP_CC4", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoResEqpCc4;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_RES_EQP_TCPK", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoResEqpTcpk;

    /**
     * @var float
     *
     * @ORM\Column(name="ECO_RES_EQP_TMFC", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoResEqpTmfc;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_ECO_RES_EQP", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEcoResEqp;

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

