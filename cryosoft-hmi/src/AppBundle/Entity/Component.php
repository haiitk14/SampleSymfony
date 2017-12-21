<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Component
 *
 * @ORM\Table(name="component", indexes={@ORM\Index(name="FK_COMPONENT_LN2USER", columns={"ID_USER"})})
 * @ORM\Entity
 */
class Component
{
    /**
     * @var float
     *
     * @ORM\Column(name="COMP_VERSION", type="float", precision=24, scale=0, nullable=true)
     */
    private $compVersion;

    /**
     * @var integer
     *
     * @ORM\Column(name="COMP_RELEASE", type="smallint", nullable=true)
     */
    private $compRelease;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="COMP_DATE", type="datetime", nullable=true)
     */
    private $compDate;

    /**
     * @var string
     *
     * @ORM\Column(name="COMP_COMMENT", type="string", length=2000, nullable=true)
     */
    private $compComment;

    /**
     * @var integer
     *
     * @ORM\Column(name="COND_DENS_MODE", type="smallint", nullable=true)
     */
    private $condDensMode;

    /**
     * @var float
     *
     * @ORM\Column(name="SPECIFIC_HEAT", type="float", precision=24, scale=0, nullable=true)
     */
    private $specificHeat;

    /**
     * @var float
     *
     * @ORM\Column(name="DENSITY", type="float", precision=24, scale=0, nullable=true)
     */
    private $density;

    /**
     * @var float
     *
     * @ORM\Column(name="PROTID", type="float", precision=24, scale=0, nullable=true)
     */
    private $protid;

    /**
     * @var float
     *
     * @ORM\Column(name="LIPID", type="float", precision=24, scale=0, nullable=true)
     */
    private $lipid;

    /**
     * @var float
     *
     * @ORM\Column(name="GLUCID", type="float", precision=24, scale=0, nullable=true)
     */
    private $glucid;

    /**
     * @var float
     *
     * @ORM\Column(name="WATER", type="float", precision=24, scale=0, nullable=true)
     */
    private $water;

    /**
     * @var float
     *
     * @ORM\Column(name="NON_FROZEN_WATER", type="float", precision=24, scale=0, nullable=true)
     */
    private $nonFrozenWater;

    /**
     * @var float
     *
     * @ORM\Column(name="SALT", type="float", precision=24, scale=0, nullable=true)
     */
    private $salt;

    /**
     * @var float
     *
     * @ORM\Column(name="AIR", type="float", precision=24, scale=0, nullable=true)
     */
    private $air;

    /**
     * @var integer
     *
     * @ORM\Column(name="CLASS_TYPE", type="smallint", nullable=true)
     */
    private $classType;

    /**
     * @var integer
     *
     * @ORM\Column(name="SUB_FAMILY", type="integer", nullable=true)
     */
    private $subFamily;

    /**
     * @var integer
     *
     * @ORM\Column(name="FAT_TYPE", type="smallint", nullable=true)
     */
    private $fatType;

    /**
     * @var integer
     *
     * @ORM\Column(name="COMP_NATURE", type="smallint", nullable=true)
     */
    private $compNature;

    /**
     * @var float
     *
     * @ORM\Column(name="FREEZE_TEMP", type="float", precision=24, scale=0, nullable=true)
     */
    private $freezeTemp;

    /**
     * @var string
     *
     * @ORM\Column(name="BLS_CODE", type="string", length=10, nullable=true)
     */
    private $blsCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="COMP_GEN_STATUS", type="integer", nullable=true)
     */
    private $compGenStatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="COMP_IMP_ID_STUDY", type="integer", nullable=true)
     */
    private $compImpIdStudy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="OPEN_BY_OWNER", type="boolean", nullable=true)
     */
    private $openByOwner = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_COMP", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idComp;

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
     * @return float
     */
    public function getCompVersion()
    {
        return $this->compVersion;
    }

    /**
     * @param float $compVersion
     *
     * @return self
     */
    public function setCompVersion($compVersion)
    {
        $this->compVersion = $compVersion;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCompRelease()
    {
        return $this->compRelease;
    }

    /**
     * @param integer $compRelease
     *
     * @return self
     */
    public function setCompRelease($compRelease)
    {
        $this->compRelease = $compRelease;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCompDate()
    {
        return $this->compDate;
    }

    /**
     * @param \DateTime $compDate
     *
     * @return self
     */
    public function setCompDate(\DateTime $compDate)
    {
        $this->compDate = $compDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompComment()
    {
        return $this->compComment;
    }

    /**
     * @param string $compComment
     *
     * @return self
     */
    public function setCompComment($compComment)
    {
        $this->compComment = $compComment;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCondDensMode()
    {
        return $this->condDensMode;
    }

    /**
     * @param integer $condDensMode
     *
     * @return self
     */
    public function setCondDensMode($condDensMode)
    {
        $this->condDensMode = $condDensMode;

        return $this;
    }

    /**
     * @return float
     */
    public function getSpecificHeat()
    {
        return $this->specificHeat;
    }

    /**
     * @param float $specificHeat
     *
     * @return self
     */
    public function setSpecificHeat($specificHeat)
    {
        $this->specificHeat = $specificHeat;

        return $this;
    }

    /**
     * @return float
     */
    public function getDensity()
    {
        return $this->density;
    }

    /**
     * @param float $density
     *
     * @return self
     */
    public function setDensity($density)
    {
        $this->density = $density;

        return $this;
    }

    /**
     * @return float
     */
    public function getProtid()
    {
        return $this->protid;
    }

    /**
     * @param float $protid
     *
     * @return self
     */
    public function setProtid($protid)
    {
        $this->protid = $protid;

        return $this;
    }

    /**
     * @return float
     */
    public function getLipid()
    {
        return $this->lipid;
    }

    /**
     * @param float $lipid
     *
     * @return self
     */
    public function setLipid($lipid)
    {
        $this->lipid = $lipid;

        return $this;
    }

    /**
     * @return float
     */
    public function getGlucid()
    {
        return $this->glucid;
    }

    /**
     * @param float $glucid
     *
     * @return self
     */
    public function setGlucid($glucid)
    {
        $this->glucid = $glucid;

        return $this;
    }

    /**
     * @return float
     */
    public function getWater()
    {
        return $this->water;
    }

    /**
     * @param float $water
     *
     * @return self
     */
    public function setWater($water)
    {
        $this->water = $water;

        return $this;
    }

    /**
     * @return float
     */
    public function getNonFrozenWater()
    {
        return $this->nonFrozenWater;
    }

    /**
     * @param float $nonFrozenWater
     *
     * @return self
     */
    public function setNonFrozenWater($nonFrozenWater)
    {
        $this->nonFrozenWater = $nonFrozenWater;

        return $this;
    }

    /**
     * @return float
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param float $salt
     *
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return float
     */
    public function getAir()
    {
        return $this->air;
    }

    /**
     * @param float $air
     *
     * @return self
     */
    public function setAir($air)
    {
        $this->air = $air;

        return $this;
    }

    /**
     * @return integer
     */
    public function getClassType()
    {
        return $this->classType;
    }

    /**
     * @param integer $classType
     *
     * @return self
     */
    public function setClassType($classType)
    {
        $this->classType = $classType;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSubFamily()
    {
        return $this->subFamily;
    }

    /**
     * @param integer $subFamily
     *
     * @return self
     */
    public function setSubFamily($subFamily)
    {
        $this->subFamily = $subFamily;

        return $this;
    }

    /**
     * @return integer
     */
    public function getFatType()
    {
        return $this->fatType;
    }

    /**
     * @param integer $fatType
     *
     * @return self
     */
    public function setFatType($fatType)
    {
        $this->fatType = $fatType;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCompNature()
    {
        return $this->compNature;
    }

    /**
     * @param integer $compNature
     *
     * @return self
     */
    public function setCompNature($compNature)
    {
        $this->compNature = $compNature;

        return $this;
    }

    /**
     * @return float
     */
    public function getFreezeTemp()
    {
        return $this->freezeTemp;
    }

    /**
     * @param float $freezeTemp
     *
     * @return self
     */
    public function setFreezeTemp($freezeTemp)
    {
        $this->freezeTemp = $freezeTemp;

        return $this;
    }

    /**
     * @return string
     */
    public function getBlsCode()
    {
        return $this->blsCode;
    }

    /**
     * @param string $blsCode
     *
     * @return self
     */
    public function setBlsCode($blsCode)
    {
        $this->blsCode = $blsCode;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCompGenStatus()
    {
        return $this->compGenStatus;
    }

    /**
     * @param integer $compGenStatus
     *
     * @return self
     */
    public function setCompGenStatus($compGenStatus)
    {
        $this->compGenStatus = $compGenStatus;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCompImpIdStudy()
    {
        return $this->compImpIdStudy;
    }

    /**
     * @param integer $compImpIdStudy
     *
     * @return self
     */
    public function setCompImpIdStudy($compImpIdStudy)
    {
        $this->compImpIdStudy = $compImpIdStudy;

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
    public function getIdComp()
    {
        return $this->idComp;
    }

    /**
     * @param integer $idComp
     *
     * @return self
     */
    public function setIdComp($idComp)
    {
        $this->idComp = $idComp;

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
    public function __toString()
    {
        return $this->idComp.'';
    }
}

