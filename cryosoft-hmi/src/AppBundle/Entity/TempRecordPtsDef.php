<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TempRecordPtsDef
 *
 * @ORM\Table(name="temp_record_pts_def", indexes={@ORM\Index(name="FK_TEMP_RECORD_PTS_DEF_LN2USER", columns={"ID_USER"})})
 * @ORM\Entity
 */
class TempRecordPtsDef
{
    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS1_PT_TOP_SURF_DEF", type="smallint", nullable=true)
     */
    private $axis1PtTopSurfDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS2_PT_TOP_SURF_DEF", type="smallint", nullable=true)
     */
    private $axis2PtTopSurfDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS3_PT_TOP_SURF_DEF", type="smallint", nullable=true)
     */
    private $axis3PtTopSurfDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS1_PT_INT_PT_DEF", type="smallint", nullable=true)
     */
    private $axis1PtIntPtDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS2_PT_INT_PT_DEF", type="smallint", nullable=true)
     */
    private $axis2PtIntPtDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS3_PT_INT_PT_DEF", type="smallint", nullable=true)
     */
    private $axis3PtIntPtDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS1_PT_BOT_SURF_DEF", type="smallint", nullable=true)
     */
    private $axis1PtBotSurfDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS2_PT_BOT_SURF_DEF", type="smallint", nullable=true)
     */
    private $axis2PtBotSurfDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS3_PT_BOT_SURF_DEF", type="smallint", nullable=true)
     */
    private $axis3PtBotSurfDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS2_AX_1_DEF", type="smallint", nullable=true)
     */
    private $axis2Ax1Def;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS3_AX_1_DEF", type="smallint", nullable=true)
     */
    private $axis3Ax1Def;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS1_AX_2_DEF", type="smallint", nullable=true)
     */
    private $axis1Ax2Def;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS3_AX_2_DEF", type="smallint", nullable=true)
     */
    private $axis3Ax2Def;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS1_AX_3_DEF", type="smallint", nullable=true)
     */
    private $axis1Ax3Def;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS2_AX_3_DEF", type="smallint", nullable=true)
     */
    private $axis2Ax3Def;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS1_PL_2_3_DEF", type="smallint", nullable=true)
     */
    private $axis1Pl23Def;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS2_PL_1_3_DEF", type="smallint", nullable=true)
     */
    private $axis2Pl13Def;

    /**
     * @var integer
     *
     * @ORM\Column(name="AXIS3_PL_1_2_DEF", type="smallint", nullable=true)
     */
    private $axis3Pl12Def;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB_STEPS_DEF", type="smallint", nullable=true)
     */
    private $nbStepsDef;

    /**
     * @var float
     *
     * @ORM\Column(name="CONTOUR2D_TEMP_MIN_DEF", type="float", precision=24, scale=0, nullable=true)
     */
    private $contour2dTempMinDef;

    /**
     * @var float
     *
     * @ORM\Column(name="CONTOUR2D_TEMP_MAX_DEF", type="float", precision=24, scale=0, nullable=true)
     */
    private $contour2dTempMaxDef;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_TEMP_RECORD_PTS_DEF", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTempRecordPtsDef;

    /**
     * @var \AppBundle\Entity\Ln2user
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ln2user")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_USER", referencedColumnName="ID_USER")
     * })
     */
    private $idUser;



    /**
     * @return integer
     */
    public function getAxis1PtTopSurfDef()
    {
        return $this->axis1PtTopSurfDef;
    }

    /**
     * @param integer $axis1PtTopSurfDef
     *
     * @return self
     */
    public function setAxis1PtTopSurfDef($axis1PtTopSurfDef)
    {
        $this->axis1PtTopSurfDef = $axis1PtTopSurfDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis2PtTopSurfDef()
    {
        return $this->axis2PtTopSurfDef;
    }

    /**
     * @param integer $axis2PtTopSurfDef
     *
     * @return self
     */
    public function setAxis2PtTopSurfDef($axis2PtTopSurfDef)
    {
        $this->axis2PtTopSurfDef = $axis2PtTopSurfDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis3PtTopSurfDef()
    {
        return $this->axis3PtTopSurfDef;
    }

    /**
     * @param integer $axis3PtTopSurfDef
     *
     * @return self
     */
    public function setAxis3PtTopSurfDef($axis3PtTopSurfDef)
    {
        $this->axis3PtTopSurfDef = $axis3PtTopSurfDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis1PtIntPtDef()
    {
        return $this->axis1PtIntPtDef;
    }

    /**
     * @param integer $axis1PtIntPtDef
     *
     * @return self
     */
    public function setAxis1PtIntPtDef($axis1PtIntPtDef)
    {
        $this->axis1PtIntPtDef = $axis1PtIntPtDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis2PtIntPtDef()
    {
        return $this->axis2PtIntPtDef;
    }

    /**
     * @param integer $axis2PtIntPtDef
     *
     * @return self
     */
    public function setAxis2PtIntPtDef($axis2PtIntPtDef)
    {
        $this->axis2PtIntPtDef = $axis2PtIntPtDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis3PtIntPtDef()
    {
        return $this->axis3PtIntPtDef;
    }

    /**
     * @param integer $axis3PtIntPtDef
     *
     * @return self
     */
    public function setAxis3PtIntPtDef($axis3PtIntPtDef)
    {
        $this->axis3PtIntPtDef = $axis3PtIntPtDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis1PtBotSurfDef()
    {
        return $this->axis1PtBotSurfDef;
    }

    /**
     * @param integer $axis1PtBotSurfDef
     *
     * @return self
     */
    public function setAxis1PtBotSurfDef($axis1PtBotSurfDef)
    {
        $this->axis1PtBotSurfDef = $axis1PtBotSurfDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis2PtBotSurfDef()
    {
        return $this->axis2PtBotSurfDef;
    }

    /**
     * @param integer $axis2PtBotSurfDef
     *
     * @return self
     */
    public function setAxis2PtBotSurfDef($axis2PtBotSurfDef)
    {
        $this->axis2PtBotSurfDef = $axis2PtBotSurfDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis3PtBotSurfDef()
    {
        return $this->axis3PtBotSurfDef;
    }

    /**
     * @param integer $axis3PtBotSurfDef
     *
     * @return self
     */
    public function setAxis3PtBotSurfDef($axis3PtBotSurfDef)
    {
        $this->axis3PtBotSurfDef = $axis3PtBotSurfDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis2Ax1Def()
    {
        return $this->axis2Ax1Def;
    }

    /**
     * @param integer $axis2Ax1Def
     *
     * @return self
     */
    public function setAxis2Ax1Def($axis2Ax1Def)
    {
        $this->axis2Ax1Def = $axis2Ax1Def;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis3Ax1Def()
    {
        return $this->axis3Ax1Def;
    }

    /**
     * @param integer $axis3Ax1Def
     *
     * @return self
     */
    public function setAxis3Ax1Def($axis3Ax1Def)
    {
        $this->axis3Ax1Def = $axis3Ax1Def;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis1Ax2Def()
    {
        return $this->axis1Ax2Def;
    }

    /**
     * @param integer $axis1Ax2Def
     *
     * @return self
     */
    public function setAxis1Ax2Def($axis1Ax2Def)
    {
        $this->axis1Ax2Def = $axis1Ax2Def;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis3Ax2Def()
    {
        return $this->axis3Ax2Def;
    }

    /**
     * @param integer $axis3Ax2Def
     *
     * @return self
     */
    public function setAxis3Ax2Def($axis3Ax2Def)
    {
        $this->axis3Ax2Def = $axis3Ax2Def;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis1Ax3Def()
    {
        return $this->axis1Ax3Def;
    }

    /**
     * @param integer $axis1Ax3Def
     *
     * @return self
     */
    public function setAxis1Ax3Def($axis1Ax3Def)
    {
        $this->axis1Ax3Def = $axis1Ax3Def;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis2Ax3Def()
    {
        return $this->axis2Ax3Def;
    }

    /**
     * @param integer $axis2Ax3Def
     *
     * @return self
     */
    public function setAxis2Ax3Def($axis2Ax3Def)
    {
        $this->axis2Ax3Def = $axis2Ax3Def;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis1Pl23Def()
    {
        return $this->axis1Pl23Def;
    }

    /**
     * @param integer $axis1Pl23Def
     *
     * @return self
     */
    public function setAxis1Pl23Def($axis1Pl23Def)
    {
        $this->axis1Pl23Def = $axis1Pl23Def;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis2Pl13Def()
    {
        return $this->axis2Pl13Def;
    }

    /**
     * @param integer $axis2Pl13Def
     *
     * @return self
     */
    public function setAxis2Pl13Def($axis2Pl13Def)
    {
        $this->axis2Pl13Def = $axis2Pl13Def;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAxis3Pl12Def()
    {
        return $this->axis3Pl12Def;
    }

    /**
     * @param integer $axis3Pl12Def
     *
     * @return self
     */
    public function setAxis3Pl12Def($axis3Pl12Def)
    {
        $this->axis3Pl12Def = $axis3Pl12Def;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbStepsDef()
    {
        return $this->nbStepsDef;
    }

    /**
     * @param integer $nbStepsDef
     *
     * @return self
     */
    public function setNbStepsDef($nbStepsDef)
    {
        $this->nbStepsDef = $nbStepsDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getContour2dTempMinDef()
    {
        return $this->contour2dTempMinDef;
    }

    /**
     * @param float $contour2dTempMinDef
     *
     * @return self
     */
    public function setContour2dTempMinDef($contour2dTempMinDef)
    {
        $this->contour2dTempMinDef = $contour2dTempMinDef;

        return $this;
    }

    /**
     * @return float
     */
    public function getContour2dTempMaxDef()
    {
        return $this->contour2dTempMaxDef;
    }

    /**
     * @param float $contour2dTempMaxDef
     *
     * @return self
     */
    public function setContour2dTempMaxDef($contour2dTempMaxDef)
    {
        $this->contour2dTempMaxDef = $contour2dTempMaxDef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdTempRecordPtsDef()
    {
        return $this->idTempRecordPtsDef;
    }

    /**
     * @param integer $idTempRecordPtsDef
     *
     * @return self
     */
    public function setIdTempRecordPtsDef($idTempRecordPtsDef)
    {
        $this->idTempRecordPtsDef = $idTempRecordPtsDef;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Ln2user
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param \AppBundle\Entity\Ln2user $idUser
     *
     * @return self
     */
    public function setIdUser(\AppBundle\Entity\Ln2user $idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }
}

