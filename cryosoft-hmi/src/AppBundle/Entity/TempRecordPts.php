<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TempRecordPts
 *
 * @ORM\Table(name="temp_record_pts", indexes={@ORM\Index(name="FK_TEMP_RECORD_PTS_STUDIES", columns={"ID_STUDY"})})
 * @ORM\Entity
 */
class TempRecordPts
{
    /**
     * @var float
     *
     * @ORM\Column(name="AXIS1_PT_TOP_SURF", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis1PtTopSurf;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS2_PT_TOP_SURF", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis2PtTopSurf;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS3_PT_TOP_SURF", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis3PtTopSurf;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS1_PT_INT_PT", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis1PtIntPt;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS2_PT_INT_PT", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis2PtIntPt;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS3_PT_INT_PT", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis3PtIntPt;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS1_PT_BOT_SURF", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis1PtBotSurf;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS2_PT_BOT_SURF", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis2PtBotSurf;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS3_PT_BOT_SURF", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis3PtBotSurf;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS2_AX_1", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis2Ax1;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS3_AX_1", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis3Ax1;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS1_AX_2", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis1Ax2;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS3_AX_2", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis3Ax2;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS1_AX_3", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis1Ax3;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS2_AX_3", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis2Ax3;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS1_PL_2_3", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis1Pl23;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS2_PL_1_3", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis2Pl13;

    /**
     * @var float
     *
     * @ORM\Column(name="AXIS3_PL_1_2", type="float", precision=10, scale=0, nullable=true)
     */
    private $axis3Pl12;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB_STEPS", type="smallint", nullable=true)
     */
    private $nbSteps;

    /**
     * @var float
     *
     * @ORM\Column(name="CONTOUR2D_TEMP_MIN", type="float", precision=24, scale=0, nullable=true)
     */
    private $contour2dTempMin;

    /**
     * @var float
     *
     * @ORM\Column(name="CONTOUR2D_TEMP_MAX", type="float", precision=24, scale=0, nullable=true)
     */
    private $contour2dTempMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_TEMP_RECORD_PTS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTempRecordPts;

    /**
     * @var \AppBundle\Entity\Studies
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Studies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_STUDY", referencedColumnName="ID_STUDY")
     * })
     */
    private $idStudy;



    /**
     * @return float
     */
    public function getAxis1PtTopSurf()
    {
        return $this->axis1PtTopSurf;
    }

    /**
     * @param float $axis1PtTopSurf
     *
     * @return self
     */
    public function setAxis1PtTopSurf($axis1PtTopSurf)
    {
        $this->axis1PtTopSurf = $axis1PtTopSurf;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis2PtTopSurf()
    {
        return $this->axis2PtTopSurf;
    }

    /**
     * @param float $axis2PtTopSurf
     *
     * @return self
     */
    public function setAxis2PtTopSurf($axis2PtTopSurf)
    {
        $this->axis2PtTopSurf = $axis2PtTopSurf;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis3PtTopSurf()
    {
        return $this->axis3PtTopSurf;
    }

    /**
     * @param float $axis3PtTopSurf
     *
     * @return self
     */
    public function setAxis3PtTopSurf($axis3PtTopSurf)
    {
        $this->axis3PtTopSurf = $axis3PtTopSurf;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis1PtIntPt()
    {
        return $this->axis1PtIntPt;
    }

    /**
     * @param float $axis1PtIntPt
     *
     * @return self
     */
    public function setAxis1PtIntPt($axis1PtIntPt)
    {
        $this->axis1PtIntPt = $axis1PtIntPt;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis2PtIntPt()
    {
        return $this->axis2PtIntPt;
    }

    /**
     * @param float $axis2PtIntPt
     *
     * @return self
     */
    public function setAxis2PtIntPt($axis2PtIntPt)
    {
        $this->axis2PtIntPt = $axis2PtIntPt;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis3PtIntPt()
    {
        return $this->axis3PtIntPt;
    }

    /**
     * @param float $axis3PtIntPt
     *
     * @return self
     */
    public function setAxis3PtIntPt($axis3PtIntPt)
    {
        $this->axis3PtIntPt = $axis3PtIntPt;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis1PtBotSurf()
    {
        return $this->axis1PtBotSurf;
    }

    /**
     * @param float $axis1PtBotSurf
     *
     * @return self
     */
    public function setAxis1PtBotSurf($axis1PtBotSurf)
    {
        $this->axis1PtBotSurf = $axis1PtBotSurf;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis2PtBotSurf()
    {
        return $this->axis2PtBotSurf;
    }

    /**
     * @param float $axis2PtBotSurf
     *
     * @return self
     */
    public function setAxis2PtBotSurf($axis2PtBotSurf)
    {
        $this->axis2PtBotSurf = $axis2PtBotSurf;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis3PtBotSurf()
    {
        return $this->axis3PtBotSurf;
    }

    /**
     * @param float $axis3PtBotSurf
     *
     * @return self
     */
    public function setAxis3PtBotSurf($axis3PtBotSurf)
    {
        $this->axis3PtBotSurf = $axis3PtBotSurf;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis2Ax1()
    {
        return $this->axis2Ax1;
    }

    /**
     * @param float $axis2Ax1
     *
     * @return self
     */
    public function setAxis2Ax1($axis2Ax1)
    {
        $this->axis2Ax1 = $axis2Ax1;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis3Ax1()
    {
        return $this->axis3Ax1;
    }

    /**
     * @param float $axis3Ax1
     *
     * @return self
     */
    public function setAxis3Ax1($axis3Ax1)
    {
        $this->axis3Ax1 = $axis3Ax1;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis1Ax2()
    {
        return $this->axis1Ax2;
    }

    /**
     * @param float $axis1Ax2
     *
     * @return self
     */
    public function setAxis1Ax2($axis1Ax2)
    {
        $this->axis1Ax2 = $axis1Ax2;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis3Ax2()
    {
        return $this->axis3Ax2;
    }

    /**
     * @param float $axis3Ax2
     *
     * @return self
     */
    public function setAxis3Ax2($axis3Ax2)
    {
        $this->axis3Ax2 = $axis3Ax2;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis1Ax3()
    {
        return $this->axis1Ax3;
    }

    /**
     * @param float $axis1Ax3
     *
     * @return self
     */
    public function setAxis1Ax3($axis1Ax3)
    {
        $this->axis1Ax3 = $axis1Ax3;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis2Ax3()
    {
        return $this->axis2Ax3;
    }

    /**
     * @param float $axis2Ax3
     *
     * @return self
     */
    public function setAxis2Ax3($axis2Ax3)
    {
        $this->axis2Ax3 = $axis2Ax3;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis1Pl23()
    {
        return $this->axis1Pl23;
    }

    /**
     * @param float $axis1Pl23
     *
     * @return self
     */
    public function setAxis1Pl23($axis1Pl23)
    {
        $this->axis1Pl23 = $axis1Pl23;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis2Pl13()
    {
        return $this->axis2Pl13;
    }

    /**
     * @param float $axis2Pl13
     *
     * @return self
     */
    public function setAxis2Pl13($axis2Pl13)
    {
        $this->axis2Pl13 = $axis2Pl13;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxis3Pl12()
    {
        return $this->axis3Pl12;
    }

    /**
     * @param float $axis3Pl12
     *
     * @return self
     */
    public function setAxis3Pl12($axis3Pl12)
    {
        $this->axis3Pl12 = $axis3Pl12;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbSteps()
    {
        return $this->nbSteps;
    }

    /**
     * @param integer $nbSteps
     *
     * @return self
     */
    public function setNbSteps($nbSteps)
    {
        $this->nbSteps = $nbSteps;

        return $this;
    }

    /**
     * @return float
     */
    public function getContour2dTempMin()
    {
        return $this->contour2dTempMin;
    }

    /**
     * @param float $contour2dTempMin
     *
     * @return self
     */
    public function setContour2dTempMin($contour2dTempMin)
    {
        $this->contour2dTempMin = $contour2dTempMin;

        return $this;
    }

    /**
     * @return float
     */
    public function getContour2dTempMax()
    {
        return $this->contour2dTempMax;
    }

    /**
     * @param float $contour2dTempMax
     *
     * @return self
     */
    public function setContour2dTempMax($contour2dTempMax)
    {
        $this->contour2dTempMax = $contour2dTempMax;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdTempRecordPts()
    {
        return $this->idTempRecordPts;
    }

    /**
     * @param integer $idTempRecordPts
     *
     * @return self
     */
    public function setIdTempRecordPts($idTempRecordPts)
    {
        $this->idTempRecordPts = $idTempRecordPts;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Studies
     */
    public function getIdStudy()
    {
        return $this->idStudy;
    }

    /**
     * @param \AppBundle\Entity\Studies $idStudy
     *
     * @return self
     */
    public function setIdStudy(\AppBundle\Entity\Studies $idStudy)
    {
        $this->idStudy = $idStudy;

        return $this;
    }
}

