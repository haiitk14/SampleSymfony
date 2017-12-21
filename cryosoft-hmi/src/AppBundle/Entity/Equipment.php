<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipment
 *
 * @ORM\Table(name="equipment", indexes={@ORM\Index(name="FK_EQUIPMENT_EQUIPSERIES", columns={"ID_EQUIPSERIES"}), @ORM\Index(name="FK_EQUIPMENT_COOLING_FAMILY", columns={"ID_COOLING_FAMILY"}), @ORM\Index(name="FK_EQUIPMENT_LN2USER", columns={"ID_USER"})})
 * @ORM\Entity
 */
class Equipment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_EQUIPGENERATION", type="integer", nullable=true)
     */
    private $idEquipgeneration;

    /**
     * @var string
     *
     * @ORM\Column(name="EQUIP_NAME", type="string", length=255, nullable=true)
     */
    private $equipName;

    /**
     * @var float
     *
     * @ORM\Column(name="EQUIP_VERSION", type="float", precision=24, scale=0, nullable=true)
     */
    private $equipVersion;

    /**
     * @var integer
     *
     * @ORM\Column(name="EQUIP_RELEASE", type="smallint", nullable=true)
     */
    private $equipRelease;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="EQUIP_DATE", type="datetime", nullable=true)
     */
    private $equipDate;

    /**
     * @var string
     *
     * @ORM\Column(name="EQUIP_COMMENT", type="string", length=2000, nullable=true)
     */
    private $equipComment;

    /**
     * @var string
     *
     * @ORM\Column(name="EQUIPPICT", type="string", length=32, nullable=true)
     */
    private $equippict;

    /**
     * @var boolean
     *
     * @ORM\Column(name="STD", type="boolean", nullable=true)
     */
    private $std;

    /**
     * @var float
     *
     * @ORM\Column(name="EQP_LENGTH", type="float", precision=24, scale=0, nullable=true)
     */
    private $eqpLength;

    /**
     * @var float
     *
     * @ORM\Column(name="EQP_WIDTH", type="float", precision=24, scale=0, nullable=true)
     */
    private $eqpWidth;

    /**
     * @var float
     *
     * @ORM\Column(name="EQP_HEIGHT", type="float", precision=24, scale=0, nullable=true)
     */
    private $eqpHeight;

    /**
     * @var float
     *
     * @ORM\Column(name="MODUL_LENGTH", type="float", precision=24, scale=0, nullable=true)
     */
    private $modulLength;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB_MAX_MODUL", type="smallint", nullable=true)
     */
    private $nbMaxModul;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB_TR", type="smallint", nullable=true)
     */
    private $nbTr;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB_TS", type="smallint", nullable=true)
     */
    private $nbTs;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB_VC", type="smallint", nullable=true)
     */
    private $nbVc;

    /**
     * @var float
     *
     * @ORM\Column(name="BUYING_COST", type="float", precision=24, scale=0, nullable=true)
     */
    private $buyingCost;

    /**
     * @var float
     *
     * @ORM\Column(name="RENTAL_COST", type="float", precision=24, scale=0, nullable=true)
     */
    private $rentalCost;

    /**
     * @var float
     *
     * @ORM\Column(name="INSTALL_COST", type="float", precision=24, scale=0, nullable=true)
     */
    private $installCost;

    /**
     * @var float
     *
     * @ORM\Column(name="MAX_FLOW_RATE", type="float", precision=24, scale=0, nullable=true)
     */
    private $maxFlowRate;

    /**
     * @var integer
     *
     * @ORM\Column(name="MAX_NOZZLES_BY_RAMP", type="smallint", nullable=true)
     */
    private $maxNozzlesByRamp;

    /**
     * @var integer
     *
     * @ORM\Column(name="MAX_RAMPS", type="smallint", nullable=true)
     */
    private $maxRamps;

    /**
     * @var integer
     *
     * @ORM\Column(name="NUMBER_OF_ZONES", type="integer", nullable=true)
     */
    private $numberOfZones;

    /**
     * @var float
     *
     * @ORM\Column(name="TMP_REGUL_MIN", type="float", precision=24, scale=0, nullable=true)
     */
    private $tmpRegulMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="CAPABILITIES", type="bigint", nullable=true)
     */
    private $capabilities;

    /**
     * @var integer
     *
     * @ORM\Column(name="ITEM_TR", type="integer", nullable=true)
     */
    private $itemTr;

    /**
     * @var integer
     *
     * @ORM\Column(name="ITEM_TS", type="integer", nullable=true)
     */
    private $itemTs;

    /**
     * @var integer
     *
     * @ORM\Column(name="ITEM_VC", type="integer", nullable=true)
     */
    private $itemVc;

    /**
     * @var integer
     *
     * @ORM\Column(name="ITEM_PRECIS", type="integer", nullable=true)
     */
    private $itemPrecis;

    /**
     * @var integer
     *
     * @ORM\Column(name="ITEM_TIMESTEP", type="integer", nullable=true)
     */
    private $itemTimestep;

    /**
     * @var binary
     *
     * @ORM\Column(name="DLL_IDX", type="binary", nullable=true)
     */
    private $dllIdx;

    /**
     * @var binary
     *
     * @ORM\Column(name="FATHER_DLL_IDX", type="binary", nullable=true)
     */
    private $fatherDllIdx;

    /**
     * @var integer
     *
     * @ORM\Column(name="EQP_IMP_ID_STUDY", type="integer", nullable=true)
     */
    private $eqpImpIdStudy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="OPEN_BY_OWNER", type="boolean", nullable=true)
     */
    private $openByOwner = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_EQUIP", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEquip;

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
     * @var \AppBundle\Entity\Equipseries
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equipseries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_EQUIPSERIES", referencedColumnName="ID_EQUIPSERIES")
     * })
     */
    private $idEquipseries;

    /**
     * @var \AppBundle\Entity\CoolingFamily
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CoolingFamily")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_COOLING_FAMILY", referencedColumnName="ID_COOLING_FAMILY")
     * )
     */
    private $idCoolingFamily;

    /**
     * @return integer
     */
    public function getIdEquipgeneration()
    {
        return $this->idEquipgeneration;
    }

    /**
     * @param integer $idEquipgeneration
     *
     * @return self
     */
    public function setIdEquipgeneration($idEquipgeneration)
    {
        $this->idEquipgeneration = $idEquipgeneration;

        return $this;
    }

    /**
     * @return string
     */
    public function getEquipName()
    {
        return $this->equipName;
    }

    /**
     * @param string $equipName
     *
     * @return self
     */
    public function setEquipName($equipName)
    {
        $this->equipName = $equipName;

        return $this;
    }

    /**
     * @return float
     */
    public function getEquipVersion()
    {
        return $this->equipVersion;
    }

    /**
     * @param float $equipVersion
     *
     * @return self
     */
    public function setEquipVersion($equipVersion)
    {
        $this->equipVersion = $equipVersion;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEquipRelease()
    {
        return $this->equipRelease;
    }

    /**
     * @param integer $equipRelease
     *
     * @return self
     */
    public function setEquipRelease($equipRelease)
    {
        $this->equipRelease = $equipRelease;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEquipDate()
    {
        return $this->equipDate;
    }

    /**
     * @param \DateTime $equipDate
     *
     * @return self
     */
    public function setEquipDate(\DateTime $equipDate)
    {
        $this->equipDate = $equipDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getEquipComment()
    {
        return $this->equipComment;
    }

    /**
     * @param string $equipComment
     *
     * @return self
     */
    public function setEquipComment($equipComment)
    {
        $this->equipComment = $equipComment;

        return $this;
    }

    /**
     * @return string
     */
    public function getEquippict()
    {
        return $this->equippict;
    }

    /**
     * @param string $equippict
     *
     * @return self
     */
    public function setEquippict($equippict)
    {
        $this->equippict = $equippict;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isStd()
    {
        return $this->std;
    }

    /**
     * @param boolean $std
     *
     * @return self
     */
    public function setStd($std)
    {
        $this->std = $std;

        return $this;
    }

    /**
     * @return float
     */
    public function getEqpLength()
    {
        return $this->eqpLength;
    }

    /**
     * @param float $eqpLength
     *
     * @return self
     */
    public function setEqpLength($eqpLength)
    {
        $this->eqpLength = $eqpLength;

        return $this;
    }

    /**
     * @return float
     */
    public function getEqpWidth()
    {
        return $this->eqpWidth;
    }

    /**
     * @param float $eqpWidth
     *
     * @return self
     */
    public function setEqpWidth($eqpWidth)
    {
        $this->eqpWidth = $eqpWidth;

        return $this;
    }

    /**
     * @return float
     */
    public function getEqpHeight()
    {
        return $this->eqpHeight;
    }

    /**
     * @param float $eqpHeight
     *
     * @return self
     */
    public function setEqpHeight($eqpHeight)
    {
        $this->eqpHeight = $eqpHeight;

        return $this;
    }

    /**
     * @return float
     */
    public function getModulLength()
    {
        return $this->modulLength;
    }

    /**
     * @param float $modulLength
     *
     * @return self
     */
    public function setModulLength($modulLength)
    {
        $this->modulLength = $modulLength;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbMaxModul()
    {
        return $this->nbMaxModul;
    }

    /**
     * @param integer $nbMaxModul
     *
     * @return self
     */
    public function setNbMaxModul($nbMaxModul)
    {
        $this->nbMaxModul = $nbMaxModul;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbTr()
    {
        return $this->nbTr;
    }

    /**
     * @param integer $nbTr
     *
     * @return self
     */
    public function setNbTr($nbTr)
    {
        $this->nbTr = $nbTr;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbTs()
    {
        return $this->nbTs;
    }

    /**
     * @param integer $nbTs
     *
     * @return self
     */
    public function setNbTs($nbTs)
    {
        $this->nbTs = $nbTs;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNbVc()
    {
        return $this->nbVc;
    }

    /**
     * @param integer $nbVc
     *
     * @return self
     */
    public function setNbVc($nbVc)
    {
        $this->nbVc = $nbVc;

        return $this;
    }

    /**
     * @return float
     */
    public function getBuyingCost()
    {
        return $this->buyingCost;
    }

    /**
     * @param float $buyingCost
     *
     * @return self
     */
    public function setBuyingCost($buyingCost)
    {
        $this->buyingCost = $buyingCost;

        return $this;
    }

    /**
     * @return float
     */
    public function getRentalCost()
    {
        return $this->rentalCost;
    }

    /**
     * @param float $rentalCost
     *
     * @return self
     */
    public function setRentalCost($rentalCost)
    {
        $this->rentalCost = $rentalCost;

        return $this;
    }

    /**
     * @return float
     */
    public function getInstallCost()
    {
        return $this->installCost;
    }

    /**
     * @param float $installCost
     *
     * @return self
     */
    public function setInstallCost($installCost)
    {
        $this->installCost = $installCost;

        return $this;
    }

    /**
     * @return float
     */
    public function getMaxFlowRate()
    {
        return $this->maxFlowRate;
    }

    /**
     * @param float $maxFlowRate
     *
     * @return self
     */
    public function setMaxFlowRate($maxFlowRate)
    {
        $this->maxFlowRate = $maxFlowRate;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMaxNozzlesByRamp()
    {
        return $this->maxNozzlesByRamp;
    }

    /**
     * @param integer $maxNozzlesByRamp
     *
     * @return self
     */
    public function setMaxNozzlesByRamp($maxNozzlesByRamp)
    {
        $this->maxNozzlesByRamp = $maxNozzlesByRamp;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMaxRamps()
    {
        return $this->maxRamps;
    }

    /**
     * @param integer $maxRamps
     *
     * @return self
     */
    public function setMaxRamps($maxRamps)
    {
        $this->maxRamps = $maxRamps;

        return $this;
    }

    /**
     * @return integer
     */
    public function getNumberOfZones()
    {
        return $this->numberOfZones;
    }

    /**
     * @param integer $numberOfZones
     *
     * @return self
     */
    public function setNumberOfZones($numberOfZones)
    {
        $this->numberOfZones = $numberOfZones;

        return $this;
    }

    /**
     * @return float
     */
    public function getTmpRegulMin()
    {
        return $this->tmpRegulMin;
    }

    /**
     * @param float $tmpRegulMin
     *
     * @return self
     */
    public function setTmpRegulMin($tmpRegulMin)
    {
        $this->tmpRegulMin = $tmpRegulMin;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCapabilities()
    {
        return $this->capabilities;
    }

    /**
     * @param integer $capabilities
     *
     * @return self
     */
    public function setCapabilities($capabilities)
    {
        $this->capabilities = $capabilities;

        return $this;
    }

    /**
     * @return integer
     */
    public function getItemTr()
    {
        return $this->itemTr;
    }

    /**
     * @param integer $itemTr
     *
     * @return self
     */
    public function setItemTr($itemTr)
    {
        $this->itemTr = $itemTr;

        return $this;
    }

    /**
     * @return integer
     */
    public function getItemTs()
    {
        return $this->itemTs;
    }

    /**
     * @param integer $itemTs
     *
     * @return self
     */
    public function setItemTs($itemTs)
    {
        $this->itemTs = $itemTs;

        return $this;
    }

    /**
     * @return integer
     */
    public function getItemVc()
    {
        return $this->itemVc;
    }

    /**
     * @param integer $itemVc
     *
     * @return self
     */
    public function setItemVc($itemVc)
    {
        $this->itemVc = $itemVc;

        return $this;
    }

    /**
     * @return integer
     */
    public function getItemPrecis()
    {
        return $this->itemPrecis;
    }

    /**
     * @param integer $itemPrecis
     *
     * @return self
     */
    public function setItemPrecis($itemPrecis)
    {
        $this->itemPrecis = $itemPrecis;

        return $this;
    }

    /**
     * @return integer
     */
    public function getItemTimestep()
    {
        return $this->itemTimestep;
    }

    /**
     * @param integer $itemTimestep
     *
     * @return self
     */
    public function setItemTimestep($itemTimestep)
    {
        $this->itemTimestep = $itemTimestep;

        return $this;
    }

    /**
     * @return binary
     */
    public function getDllIdx()
    {
        return $this->dllIdx;
    }

    /**
     * @param string $dllIdx
     *
     * @return self
     */
    public function setDllIdx($dllIdx)
    {
        $this->dllIdx = $dllIdx;

        return $this;
    }

    /**
     * @return string
     */
    public function getFatherDllIdx()
    {
        return $this->fatherDllIdx;
    }

    /**
     * @param str $fatherDllIdx
     *
     * @return self
     */
    public function setFatherDllIdx($fatherDllIdx)
    {
        $this->fatherDllIdx = $fatherDllIdx;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEqpImpIdStudy()
    {
        return $this->eqpImpIdStudy;
    }

    /**
     * @param integer $eqpImpIdStudy
     *
     * @return self
     */
    public function setEqpImpIdStudy($eqpImpIdStudy)
    {
        $this->eqpImpIdStudy = $eqpImpIdStudy;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isOpenByOwner()
    {
        return $this->openByOwner;
    }

    /**
     * @param boolean $openByOwner
     *
     * @return self
     */
    public function setOpenByOwner($openByOwner)
    {
        $this->openByOwner = $openByOwner;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdEquip()
    {
        return $this->idEquip;
    }

    /**
     * @param integer $idEquip
     *
     * @return self
     */
    public function setIdEquip($idEquip)
    {
        $this->idEquip = $idEquip;

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

    /**
     * @return \AppBundle\Entity\Equipseries
     */
    public function getIdEquipseries()
    {
        return $this->idEquipseries;
    }

    /**
     * @param \AppBundle\Entity\Equipseries $idEquipseries
     *
     * @return self
     */
    public function setIdEquipseries(\AppBundle\Entity\Equipseries $idEquipseries)
    {
        $this->idEquipseries = $idEquipseries;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\CoolingFamily
     */
    public function getIdCoolingFamily()
    {
        return $this->idCoolingFamily;
    }

    /**
     * @param \AppBundle\Entity\CoolingFamily $idCoolingFamily
     *
     * @return self
     */
    public function setIdCoolingFamily(\AppBundle\Entity\CoolingFamily $idCoolingFamily)
    {
        $this->idCoolingFamily = $idCoolingFamily;

        return $this;
    }
}

