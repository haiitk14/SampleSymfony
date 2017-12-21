<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StudEquipprofile
 *
 * @ORM\Table(name="stud_equipprofile", indexes={@ORM\Index(name="ID_STUDY_EQP", columns={"ID_STUDY_EQUIPMENTS"})})
 * @ORM\Entity
 */
class StudEquipprofile
{
    /**
     * @var float
     *
     * @ORM\Column(name="EP_X_POSITION", type="float", precision=10, scale=0, nullable=true)
     */
    private $epXPosition;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_TEMP_REGUL", type="float", precision=10, scale=0, nullable=true)
     */
    private $epTempRegul;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_ALPHA_TOP", type="float", precision=10, scale=0, nullable=true)
     */
    private $epAlphaTop;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_ALPHA_BOTTOM", type="float", precision=10, scale=0, nullable=true)
     */
    private $epAlphaBottom;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_ALPHA_LEFT", type="float", precision=10, scale=0, nullable=true)
     */
    private $epAlphaLeft;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_ALPHA_RIGHT", type="float", precision=10, scale=0, nullable=true)
     */
    private $epAlphaRight;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_ALPHA_FRONT", type="float", precision=10, scale=0, nullable=true)
     */
    private $epAlphaFront;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_ALPHA_REAR", type="float", precision=10, scale=0, nullable=true)
     */
    private $epAlphaRear;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_TEMP_TOP", type="float", precision=10, scale=0, nullable=true)
     */
    private $epTempTop;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_TEMP_BOTTOM", type="float", precision=10, scale=0, nullable=true)
     */
    private $epTempBottom;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_TEMP_LEFT", type="float", precision=10, scale=0, nullable=true)
     */
    private $epTempLeft;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_TEMP_RIGHT", type="float", precision=10, scale=0, nullable=true)
     */
    private $epTempRight;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_TEMP_FRONT", type="float", precision=10, scale=0, nullable=true)
     */
    private $epTempFront;

    /**
     * @var float
     *
     * @ORM\Column(name="EP_TEMP_REAR", type="float", precision=10, scale=0, nullable=true)
     */
    private $epTempRear;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_STUD_EQUIPPROFILE", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idStudEquipprofile;

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

