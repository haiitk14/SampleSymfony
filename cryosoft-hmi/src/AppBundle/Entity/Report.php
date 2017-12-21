<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table(name="report", indexes={@ORM\Index(name="FK_REPORT_STUDIES", columns={"ID_STUDY"})})
 * @ORM\Entity
 */
class Report
{
    /**
     * @var integer
     *
     * @ORM\Column(name="REP_CUSTOMER", type="smallint", nullable=false)
     */
    private $repCustomer = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="PROD_LIST", type="smallint", nullable=false)
     */
    private $prodList = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="PROD_TEMP", type="smallint", nullable=false)
     */
    private $prodTemp = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="PROD_3D", type="smallint", nullable=false)
     */
    private $prod3d = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="PACKING", type="smallint", nullable=false)
     */
    private $packing = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="EQUIP_LIST", type="smallint", nullable=false)
     */
    private $equipList = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="EQUIP_PARAM", type="smallint", nullable=false)
     */
    private $equipParam = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="PIPELINE", type="smallint", nullable=false)
     */
    private $pipeline = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ASSES_TERMAL", type="smallint", nullable=false)
     */
    private $assesTermal = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ASSES_CONSUMP", type="smallint", nullable=false)
     */
    private $assesConsump = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ASSES_ECO", type="smallint", nullable=false)
     */
    private $assesEco = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ASSES_TR", type="smallint", nullable=true)
     */
    private $assesTr;

    /**
     * @var integer
     *
     * @ORM\Column(name="ASSES_TR_MIN", type="smallint", nullable=true)
     */
    private $assesTrMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="ASSES_TR_MAX", type="smallint", nullable=true)
     */
    private $assesTrMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="SIZING_TR", type="smallint", nullable=false)
     */
    private $sizingTr = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="SIZING_TR_MIN", type="smallint", nullable=false)
     */
    private $sizingTrMin = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="SIZING_TR_MAX", type="smallint", nullable=false)
     */
    private $sizingTrMax = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="SIZING_VALUES", type="smallint", nullable=false)
     */
    private $sizingValues = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="SIZING_GRAPHE", type="smallint", nullable=false)
     */
    private $sizingGraphe = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="SIZING_TEMP_G", type="smallint", nullable=false)
     */
    private $sizingTempG = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="SIZING_TEMP_V", type="smallint", nullable=false)
     */
    private $sizingTempV = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="SIZING_TEMP_SAMPLE", type="smallint", nullable=false)
     */
    private $sizingTempSample = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="AXE1_X", type="float", precision=24, scale=0, nullable=false)
     */
    private $axe1X = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="AXE1_Y", type="float", precision=24, scale=0, nullable=false)
     */
    private $axe1Y = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="AXE2_X", type="float", precision=24, scale=0, nullable=false)
     */
    private $axe2X = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="AXE2_Z", type="float", precision=24, scale=0, nullable=false)
     */
    private $axe2Z = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="AXE3_Y", type="float", precision=24, scale=0, nullable=false)
     */
    private $axe3Y = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="AXE3_Z", type="float", precision=24, scale=0, nullable=false)
     */
    private $axe3Z = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ISOCHRONE_G", type="smallint", nullable=false)
     */
    private $isochroneG = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ISOCHRONE_V", type="smallint", nullable=false)
     */
    private $isochroneV = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ISOCHRONE_SAMPLE", type="smallint", nullable=false)
     */
    private $isochroneSample = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="POINT1_X", type="float", precision=24, scale=0, nullable=false)
     */
    private $point1X = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="POINT1_Y", type="float", precision=24, scale=0, nullable=false)
     */
    private $point1Y = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="POINT1_Z", type="float", precision=24, scale=0, nullable=false)
     */
    private $point1Z = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="POINT2_X", type="float", precision=24, scale=0, nullable=false)
     */
    private $point2X = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="POINT2_Y", type="float", precision=24, scale=0, nullable=false)
     */
    private $point2Y = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="POINT2_Z", type="float", precision=24, scale=0, nullable=false)
     */
    private $point2Z = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="POINT3_X", type="float", precision=24, scale=0, nullable=false)
     */
    private $point3X = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="POINT3_Y", type="float", precision=24, scale=0, nullable=false)
     */
    private $point3Y = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="POINT3_Z", type="float", precision=24, scale=0, nullable=false)
     */
    private $point3Z = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ISOVALUE_G", type="smallint", nullable=false)
     */
    private $isovalueG = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ISOVALUE_V", type="smallint", nullable=false)
     */
    private $isovalueV = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ISOVALUE_SAMPLE", type="smallint", nullable=false)
     */
    private $isovalueSample = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="PLAN_X", type="float", precision=24, scale=0, nullable=false)
     */
    private $planX = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="PLAN_Y", type="float", precision=24, scale=0, nullable=false)
     */
    private $planY = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="PLAN_Z", type="float", precision=24, scale=0, nullable=false)
     */
    private $planZ = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="CONTOUR2D_G", type="smallint", nullable=false)
     */
    private $contour2dG = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="CONTOUR2D_SAMPLE", type="smallint", nullable=false)
     */
    private $contour2dSample = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="CONTOUR2D_TEMP_STEP", type="float", precision=24, scale=0, nullable=true)
     */
    private $contour2dTempStep;

    /**
     * @var integer
     *
     * @ORM\Column(name="ENTHALPY_V", type="smallint", nullable=true)
     */
    private $enthalpyV;

    /**
     * @var integer
     *
     * @ORM\Column(name="ENTHALPY_G", type="smallint", nullable=true)
     */
    private $enthalpyG;

    /**
     * @var integer
     *
     * @ORM\Column(name="ENTHALPY_SAMPLE", type="smallint", nullable=true)
     */
    private $enthalpySample;

    /**
     * @var string
     *
     * @ORM\Column(name="DEST_SURNAME", type="string", length=255, nullable=true)
     */
    private $destSurname;

    /**
     * @var string
     *
     * @ORM\Column(name="DEST_NAME", type="string", length=255, nullable=true)
     */
    private $destName;

    /**
     * @var string
     *
     * @ORM\Column(name="DEST_FUNCTION", type="string", length=255, nullable=true)
     */
    private $destFunction;

    /**
     * @var string
     *
     * @ORM\Column(name="DEST_COORD", type="string", length=255, nullable=true)
     */
    private $destCoord;

    /**
     * @var string
     *
     * @ORM\Column(name="PHOTO_PATH", type="string", length=255, nullable=true)
     */
    private $photoPath;

    /**
     * @var string
     *
     * @ORM\Column(name="CUSTOMER_LOGO", type="string", length=255, nullable=true)
     */
    private $customerLogo;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONS_SPECIFIC", type="smallint", nullable=true)
     */
    private $consSpecific;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONS_OVERALL", type="smallint", nullable=true)
     */
    private $consOverall;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONS_TOTAL", type="smallint", nullable=true)
     */
    private $consTotal;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONS_HOUR", type="smallint", nullable=true)
     */
    private $consHour;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONS_DAY", type="smallint", nullable=true)
     */
    private $consDay;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONS_WEEK", type="smallint", nullable=true)
     */
    private $consWeek;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONS_MONTH", type="smallint", nullable=true)
     */
    private $consMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONS_YEAR", type="smallint", nullable=true)
     */
    private $consYear;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONS_EQUIP", type="smallint", nullable=true)
     */
    private $consEquip;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONS_PIPE", type="smallint", nullable=true)
     */
    private $consPipe;

    /**
     * @var integer
     *
     * @ORM\Column(name="CONS_TANK", type="smallint", nullable=true)
     */
    private $consTank;

    /**
     * @var float
     *
     * @ORM\Column(name="CONTOUR2D_OUTLINE_TIME", type="float", precision=10, scale=0, nullable=true)
     */
    private $contour2dOutlineTime;

    /**
     * @var string
     *
     * @ORM\Column(name="REPORT_COMMENT", type="text", nullable=true)
     */
    private $reportComment;

    /**
     * @var string
     *
     * @ORM\Column(name="WRITER_SURNAME", type="string", length=255, nullable=true)
     */
    private $writerSurname;

    /**
     * @var string
     *
     * @ORM\Column(name="WRITER_NAME", type="string", length=255, nullable=true)
     */
    private $writerName;

    /**
     * @var string
     *
     * @ORM\Column(name="WRITER_FUNCTION", type="string", length=255, nullable=true)
     */
    private $writerFunction;

    /**
     * @var string
     *
     * @ORM\Column(name="WRITER_COORD", type="string", length=255, nullable=true)
     */
    private $writerCoord;

    /**
     * @var boolean
     *
     * @ORM\Column(name="REP_CONS_PIE", type="boolean", nullable=true)
     */
    private $repConsPie;

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
     * @ORM\Column(name="ID_REPORT", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReport;

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
     * @return integer
     */
    public function getRepCustomer()
    {
        return $this->repCustomer;
    }

    /**
     * @param integer $repCustomer
     *
     * @return self
     */
    public function setRepCustomer($repCustomer)
    {
        $this->repCustomer = $repCustomer;

        return $this;
    }

    /**
     * @return integer
     */
    public function getProdList()
    {
        return $this->prodList;
    }

    /**
     * @param integer $prodList
     *
     * @return self
     */
    public function setProdList($prodList)
    {
        $this->prodList = $prodList;

        return $this;
    }

    /**
     * @return integer
     */
    public function getProdTemp()
    {
        return $this->prodTemp;
    }

    /**
     * @param integer $prodTemp
     *
     * @return self
     */
    public function setProdTemp($prodTemp)
    {
        $this->prodTemp = $prodTemp;

        return $this;
    }

    /**
     * @return integer
     */
    public function getProd3d()
    {
        return $this->prod3d;
    }

    /**
     * @param integer $prod3d
     *
     * @return self
     */
    public function setProd3d($prod3d)
    {
        $this->prod3d = $prod3d;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPacking()
    {
        return $this->packing;
    }

    /**
     * @param integer $packing
     *
     * @return self
     */
    public function setPacking($packing)
    {
        $this->packing = $packing;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEquipList()
    {
        return $this->equipList;
    }

    /**
     * @param integer $equipList
     *
     * @return self
     */
    public function setEquipList($equipList)
    {
        $this->equipList = $equipList;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEquipParam()
    {
        return $this->equipParam;
    }

    /**
     * @param integer $equipParam
     *
     * @return self
     */
    public function setEquipParam($equipParam)
    {
        $this->equipParam = $equipParam;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPipeline()
    {
        return $this->pipeline;
    }

    /**
     * @param integer $pipeline
     *
     * @return self
     */
    public function setPipeline($pipeline)
    {
        $this->pipeline = $pipeline;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAssesTermal()
    {
        return $this->assesTermal;
    }

    /**
     * @param integer $assesTermal
     *
     * @return self
     */
    public function setAssesTermal($assesTermal)
    {
        $this->assesTermal = $assesTermal;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAssesConsump()
    {
        return $this->assesConsump;
    }

    /**
     * @param integer $assesConsump
     *
     * @return self
     */
    public function setAssesConsump($assesConsump)
    {
        $this->assesConsump = $assesConsump;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAssesEco()
    {
        return $this->assesEco;
    }

    /**
     * @param integer $assesEco
     *
     * @return self
     */
    public function setAssesEco($assesEco)
    {
        $this->assesEco = $assesEco;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAssesTr()
    {
        return $this->assesTr;
    }

    /**
     * @param integer $assesTr
     *
     * @return self
     */
    public function setAssesTr($assesTr)
    {
        $this->assesTr = $assesTr;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAssesTrMin()
    {
        return $this->assesTrMin;
    }

    /**
     * @param integer $assesTrMin
     *
     * @return self
     */
    public function setAssesTrMin($assesTrMin)
    {
        $this->assesTrMin = $assesTrMin;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAssesTrMax()
    {
        return $this->assesTrMax;
    }

    /**
     * @param integer $assesTrMax
     *
     * @return self
     */
    public function setAssesTrMax($assesTrMax)
    {
        $this->assesTrMax = $assesTrMax;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSizingTr()
    {
        return $this->sizingTr;
    }

    /**
     * @param integer $sizingTr
     *
     * @return self
     */
    public function setSizingTr($sizingTr)
    {
        $this->sizingTr = $sizingTr;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSizingTrMin()
    {
        return $this->sizingTrMin;
    }

    /**
     * @param integer $sizingTrMin
     *
     * @return self
     */
    public function setSizingTrMin($sizingTrMin)
    {
        $this->sizingTrMin = $sizingTrMin;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSizingTrMax()
    {
        return $this->sizingTrMax;
    }

    /**
     * @param integer $sizingTrMax
     *
     * @return self
     */
    public function setSizingTrMax($sizingTrMax)
    {
        $this->sizingTrMax = $sizingTrMax;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSizingValues()
    {
        return $this->sizingValues;
    }

    /**
     * @param integer $sizingValues
     *
     * @return self
     */
    public function setSizingValues($sizingValues)
    {
        $this->sizingValues = $sizingValues;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSizingGraphe()
    {
        return $this->sizingGraphe;
    }

    /**
     * @param integer $sizingGraphe
     *
     * @return self
     */
    public function setSizingGraphe($sizingGraphe)
    {
        $this->sizingGraphe = $sizingGraphe;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSizingTempG()
    {
        return $this->sizingTempG;
    }

    /**
     * @param integer $sizingTempG
     *
     * @return self
     */
    public function setSizingTempG($sizingTempG)
    {
        $this->sizingTempG = $sizingTempG;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSizingTempV()
    {
        return $this->sizingTempV;
    }

    /**
     * @param integer $sizingTempV
     *
     * @return self
     */
    public function setSizingTempV($sizingTempV)
    {
        $this->sizingTempV = $sizingTempV;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSizingTempSample()
    {
        return $this->sizingTempSample;
    }

    /**
     * @param integer $sizingTempSample
     *
     * @return self
     */
    public function setSizingTempSample($sizingTempSample)
    {
        $this->sizingTempSample = $sizingTempSample;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxe1X()
    {
        return $this->axe1X;
    }

    /**
     * @param float $axe1X
     *
     * @return self
     */
    public function setAxe1X($axe1X)
    {
        $this->axe1X = $axe1X;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxe1Y()
    {
        return $this->axe1Y;
    }

    /**
     * @param float $axe1Y
     *
     * @return self
     */
    public function setAxe1Y($axe1Y)
    {
        $this->axe1Y = $axe1Y;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxe2X()
    {
        return $this->axe2X;
    }

    /**
     * @param float $axe2X
     *
     * @return self
     */
    public function setAxe2X($axe2X)
    {
        $this->axe2X = $axe2X;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxe2Z()
    {
        return $this->axe2Z;
    }

    /**
     * @param float $axe2Z
     *
     * @return self
     */
    public function setAxe2Z($axe2Z)
    {
        $this->axe2Z = $axe2Z;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxe3Y()
    {
        return $this->axe3Y;
    }

    /**
     * @param float $axe3Y
     *
     * @return self
     */
    public function setAxe3Y($axe3Y)
    {
        $this->axe3Y = $axe3Y;

        return $this;
    }

    /**
     * @return float
     */
    public function getAxe3Z()
    {
        return $this->axe3Z;
    }

    /**
     * @param float $axe3Z
     *
     * @return self
     */
    public function setAxe3Z($axe3Z)
    {
        $this->axe3Z = $axe3Z;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIsochroneG()
    {
        return $this->isochroneG;
    }

    /**
     * @param integer $isochroneG
     *
     * @return self
     */
    public function setIsochroneG($isochroneG)
    {
        $this->isochroneG = $isochroneG;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIsochroneV()
    {
        return $this->isochroneV;
    }

    /**
     * @param integer $isochroneV
     *
     * @return self
     */
    public function setIsochroneV($isochroneV)
    {
        $this->isochroneV = $isochroneV;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIsochroneSample()
    {
        return $this->isochroneSample;
    }

    /**
     * @param integer $isochroneSample
     *
     * @return self
     */
    public function setIsochroneSample($isochroneSample)
    {
        $this->isochroneSample = $isochroneSample;

        return $this;
    }

    /**
     * @return float
     */
    public function getPoint1X()
    {
        return $this->point1X;
    }

    /**
     * @param float $point1X
     *
     * @return self
     */
    public function setPoint1X($point1X)
    {
        $this->point1X = $point1X;

        return $this;
    }

    /**
     * @return float
     */
    public function getPoint1Y()
    {
        return $this->point1Y;
    }

    /**
     * @param float $point1Y
     *
     * @return self
     */
    public function setPoint1Y($point1Y)
    {
        $this->point1Y = $point1Y;

        return $this;
    }

    /**
     * @return float
     */
    public function getPoint1Z()
    {
        return $this->point1Z;
    }

    /**
     * @param float $point1Z
     *
     * @return self
     */
    public function setPoint1Z($point1Z)
    {
        $this->point1Z = $point1Z;

        return $this;
    }

    /**
     * @return float
     */
    public function getPoint2X()
    {
        return $this->point2X;
    }

    /**
     * @param float $point2X
     *
     * @return self
     */
    public function setPoint2X($point2X)
    {
        $this->point2X = $point2X;

        return $this;
    }

    /**
     * @return float
     */
    public function getPoint2Y()
    {
        return $this->point2Y;
    }

    /**
     * @param float $point2Y
     *
     * @return self
     */
    public function setPoint2Y($point2Y)
    {
        $this->point2Y = $point2Y;

        return $this;
    }

    /**
     * @return float
     */
    public function getPoint2Z()
    {
        return $this->point2Z;
    }

    /**
     * @param float $point2Z
     *
     * @return self
     */
    public function setPoint2Z($point2Z)
    {
        $this->point2Z = $point2Z;

        return $this;
    }

    /**
     * @return float
     */
    public function getPoint3X()
    {
        return $this->point3X;
    }

    /**
     * @param float $point3X
     *
     * @return self
     */
    public function setPoint3X($point3X)
    {
        $this->point3X = $point3X;

        return $this;
    }

    /**
     * @return float
     */
    public function getPoint3Y()
    {
        return $this->point3Y;
    }

    /**
     * @param float $point3Y
     *
     * @return self
     */
    public function setPoint3Y($point3Y)
    {
        $this->point3Y = $point3Y;

        return $this;
    }

    /**
     * @return float
     */
    public function getPoint3Z()
    {
        return $this->point3Z;
    }

    /**
     * @param float $point3Z
     *
     * @return self
     */
    public function setPoint3Z($point3Z)
    {
        $this->point3Z = $point3Z;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIsovalueG()
    {
        return $this->isovalueG;
    }

    /**
     * @param integer $isovalueG
     *
     * @return self
     */
    public function setIsovalueG($isovalueG)
    {
        $this->isovalueG = $isovalueG;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIsovalueV()
    {
        return $this->isovalueV;
    }

    /**
     * @param integer $isovalueV
     *
     * @return self
     */
    public function setIsovalueV($isovalueV)
    {
        $this->isovalueV = $isovalueV;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIsovalueSample()
    {
        return $this->isovalueSample;
    }

    /**
     * @param integer $isovalueSample
     *
     * @return self
     */
    public function setIsovalueSample($isovalueSample)
    {
        $this->isovalueSample = $isovalueSample;

        return $this;
    }

    /**
     * @return float
     */
    public function getPlanX()
    {
        return $this->planX;
    }

    /**
     * @param float $planX
     *
     * @return self
     */
    public function setPlanX($planX)
    {
        $this->planX = $planX;

        return $this;
    }

    /**
     * @return float
     */
    public function getPlanY()
    {
        return $this->planY;
    }

    /**
     * @param float $planY
     *
     * @return self
     */
    public function setPlanY($planY)
    {
        $this->planY = $planY;

        return $this;
    }

    /**
     * @return float
     */
    public function getPlanZ()
    {
        return $this->planZ;
    }

    /**
     * @param float $planZ
     *
     * @return self
     */
    public function setPlanZ($planZ)
    {
        $this->planZ = $planZ;

        return $this;
    }

    /**
     * @return integer
     */
    public function getContour2dG()
    {
        return $this->contour2dG;
    }

    /**
     * @param integer $contour2dG
     *
     * @return self
     */
    public function setContour2dG($contour2dG)
    {
        $this->contour2dG = $contour2dG;

        return $this;
    }

    /**
     * @return integer
     */
    public function getContour2dSample()
    {
        return $this->contour2dSample;
    }

    /**
     * @param integer $contour2dSample
     *
     * @return self
     */
    public function setContour2dSample($contour2dSample)
    {
        $this->contour2dSample = $contour2dSample;

        return $this;
    }

    /**
     * @return float
     */
    public function getContour2dTempStep()
    {
        return $this->contour2dTempStep;
    }

    /**
     * @param float $contour2dTempStep
     *
     * @return self
     */
    public function setContour2dTempStep($contour2dTempStep)
    {
        $this->contour2dTempStep = $contour2dTempStep;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEnthalpyV()
    {
        return $this->enthalpyV;
    }

    /**
     * @param integer $enthalpyV
     *
     * @return self
     */
    public function setEnthalpyV($enthalpyV)
    {
        $this->enthalpyV = $enthalpyV;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEnthalpyG()
    {
        return $this->enthalpyG;
    }

    /**
     * @param integer $enthalpyG
     *
     * @return self
     */
    public function setEnthalpyG($enthalpyG)
    {
        $this->enthalpyG = $enthalpyG;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEnthalpySample()
    {
        return $this->enthalpySample;
    }

    /**
     * @param integer $enthalpySample
     *
     * @return self
     */
    public function setEnthalpySample($enthalpySample)
    {
        $this->enthalpySample = $enthalpySample;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestSurname()
    {
        return $this->destSurname;
    }

    /**
     * @param string $destSurname
     *
     * @return self
     */
    public function setDestSurname($destSurname)
    {
        $this->destSurname = $destSurname;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestName()
    {
        return $this->destName;
    }

    /**
     * @param string $destName
     *
     * @return self
     */
    public function setDestName($destName)
    {
        $this->destName = $destName;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestFunction()
    {
        return $this->destFunction;
    }

    /**
     * @param string $destFunction
     *
     * @return self
     */
    public function setDestFunction($destFunction)
    {
        $this->destFunction = $destFunction;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestCoord()
    {
        return $this->destCoord;
    }

    /**
     * @param string $destCoord
     *
     * @return self
     */
    public function setDestCoord($destCoord)
    {
        $this->destCoord = $destCoord;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhotoPath()
    {
        return $this->photoPath;
    }

    /**
     * @param string $photoPath
     *
     * @return self
     */
    public function setPhotoPath($photoPath)
    {
        $this->photoPath = $photoPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerLogo()
    {
        return $this->customerLogo;
    }

    /**
     * @param string $customerLogo
     *
     * @return self
     */
    public function setCustomerLogo($customerLogo)
    {
        $this->customerLogo = $customerLogo;

        return $this;
    }

    /**
     * @return integer
     */
    public function getConsSpecific()
    {
        return $this->consSpecific;
    }

    /**
     * @param integer $consSpecific
     *
     * @return self
     */
    public function setConsSpecific($consSpecific)
    {
        $this->consSpecific = $consSpecific;

        return $this;
    }

    /**
     * @return integer
     */
    public function getConsOverall()
    {
        return $this->consOverall;
    }

    /**
     * @param integer $consOverall
     *
     * @return self
     */
    public function setConsOverall($consOverall)
    {
        $this->consOverall = $consOverall;

        return $this;
    }

    /**
     * @return integer
     */
    public function getConsTotal()
    {
        return $this->consTotal;
    }

    /**
     * @param integer $consTotal
     *
     * @return self
     */
    public function setConsTotal($consTotal)
    {
        $this->consTotal = $consTotal;

        return $this;
    }

    /**
     * @return integer
     */
    public function getConsHour()
    {
        return $this->consHour;
    }

    /**
     * @param integer $consHour
     *
     * @return self
     */
    public function setConsHour($consHour)
    {
        $this->consHour = $consHour;

        return $this;
    }

    /**
     * @return integer
     */
    public function getConsDay()
    {
        return $this->consDay;
    }

    /**
     * @param integer $consDay
     *
     * @return self
     */
    public function setConsDay($consDay)
    {
        $this->consDay = $consDay;

        return $this;
    }

    /**
     * @return integer
     */
    public function getConsWeek()
    {
        return $this->consWeek;
    }

    /**
     * @param integer $consWeek
     *
     * @return self
     */
    public function setConsWeek($consWeek)
    {
        $this->consWeek = $consWeek;

        return $this;
    }

    /**
     * @return integer
     */
    public function getConsMonth()
    {
        return $this->consMonth;
    }

    /**
     * @param integer $consMonth
     *
     * @return self
     */
    public function setConsMonth($consMonth)
    {
        $this->consMonth = $consMonth;

        return $this;
    }

    /**
     * @return integer
     */
    public function getConsYear()
    {
        return $this->consYear;
    }

    /**
     * @param integer $consYear
     *
     * @return self
     */
    public function setConsYear($consYear)
    {
        $this->consYear = $consYear;

        return $this;
    }

    /**
     * @return integer
     */
    public function getConsEquip()
    {
        return $this->consEquip;
    }

    /**
     * @param integer $consEquip
     *
     * @return self
     */
    public function setConsEquip($consEquip)
    {
        $this->consEquip = $consEquip;

        return $this;
    }

    /**
     * @return integer
     */
    public function getConsPipe()
    {
        return $this->consPipe;
    }

    /**
     * @param integer $consPipe
     *
     * @return self
     */
    public function setConsPipe($consPipe)
    {
        $this->consPipe = $consPipe;

        return $this;
    }

    /**
     * @return integer
     */
    public function getConsTank()
    {
        return $this->consTank;
    }

    /**
     * @param integer $consTank
     *
     * @return self
     */
    public function setConsTank($consTank)
    {
        $this->consTank = $consTank;

        return $this;
    }

    /**
     * @return float
     */
    public function getContour2dOutlineTime()
    {
        return $this->contour2dOutlineTime;
    }

    /**
     * @param float $contour2dOutlineTime
     *
     * @return self
     */
    public function setContour2dOutlineTime($contour2dOutlineTime)
    {
        $this->contour2dOutlineTime = $contour2dOutlineTime;

        return $this;
    }

    /**
     * @return string
     */
    public function getReportComment()
    {
        return $this->reportComment;
    }

    /**
     * @param string $reportComment
     *
     * @return self
     */
    public function setReportComment($reportComment)
    {
        $this->reportComment = $reportComment;

        return $this;
    }

    /**
     * @return string
     */
    public function getWriterSurname()
    {
        return $this->writerSurname;
    }

    /**
     * @param string $writerSurname
     *
     * @return self
     */
    public function setWriterSurname($writerSurname)
    {
        $this->writerSurname = $writerSurname;

        return $this;
    }

    /**
     * @return string
     */
    public function getWriterName()
    {
        return $this->writerName;
    }

    /**
     * @param string $writerName
     *
     * @return self
     */
    public function setWriterName($writerName)
    {
        $this->writerName = $writerName;

        return $this;
    }

    /**
     * @return string
     */
    public function getWriterFunction()
    {
        return $this->writerFunction;
    }

    /**
     * @param string $writerFunction
     *
     * @return self
     */
    public function setWriterFunction($writerFunction)
    {
        $this->writerFunction = $writerFunction;

        return $this;
    }

    /**
     * @return string
     */
    public function getWriterCoord()
    {
        return $this->writerCoord;
    }

    /**
     * @param string $writerCoord
     *
     * @return self
     */
    public function setWriterCoord($writerCoord)
    {
        $this->writerCoord = $writerCoord;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRepConsPie()
    {
        return $this->repConsPie;
    }

    /**
     * @param boolean $repConsPie
     *
     * @return self
     */
    public function setRepConsPie($repConsPie)
    {
        $this->repConsPie = $repConsPie;

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
    public function getIdReport()
    {
        return $this->idReport;
    }

    /**
     * @param integer $idReport
     *
     * @return self
     */
    public function setIdReport($idReport)
    {
        $this->idReport = $idReport;

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

