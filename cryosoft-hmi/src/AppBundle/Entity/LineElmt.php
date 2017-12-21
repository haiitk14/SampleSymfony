<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LineElmt
 *
 * @ORM\Table(name="line_elmt", indexes={@ORM\Index(name="FK_LINE_ELMT_COOLING_FAMILY", columns={"ID_COOLING_FAMILY"}), @ORM\Index(name="FK_LINE_ELMT_LN2USER", columns={"ID_USER"})})
 * @ORM\Entity
 */
class LineElmt
{
    /**
     * @var float
     *
     * @ORM\Column(name="LINE_VERSION", type="float", precision=24, scale=0, nullable=true)
     */
    private $lineVersion;

    /**
     * @var integer
     *
     * @ORM\Column(name="LINE_RELEASE", type="smallint", nullable=true)
     */
    private $lineRelease;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="LINE_DATE", type="datetime", nullable=true)
     */
    private $lineDate;

    /**
     * @var string
     *
     * @ORM\Column(name="LINE_COMMENT", type="string", length=2000, nullable=true)
     */
    private $lineComment;

    /**
     * @var string
     *
     * @ORM\Column(name="MANUFACTURER", type="string", length=32, nullable=true)
     */
    private $manufacturer;

    /**
     * @var integer
     *
     * @ORM\Column(name="ELT_TYPE", type="smallint", nullable=true)
     */
    private $eltType;

    /**
     * @var integer
     *
     * @ORM\Column(name="INSULATION_TYPE", type="smallint", nullable=true)
     */
    private $insulationType;

    /**
     * @var float
     *
     * @ORM\Column(name="ELMT_PRICE", type="float", precision=10, scale=0, nullable=true)
     */
    private $elmtPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="ELT_SIZE", type="float", precision=10, scale=0, nullable=true)
     */
    private $eltSize;

    /**
     * @var float
     *
     * @ORM\Column(name="ELT_LOSSES_1", type="float", precision=10, scale=0, nullable=true)
     */
    private $eltLosses1;

    /**
     * @var float
     *
     * @ORM\Column(name="ELT_LOSSES_2", type="float", precision=10, scale=0, nullable=true)
     */
    private $eltLosses2;

    /**
     * @var integer
     *
     * @ORM\Column(name="ELT_IMP_ID_STUDY", type="integer", nullable=true)
     */
    private $eltImpIdStudy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="OPEN_BY_OWNER", type="boolean", nullable=true)
     */
    private $openByOwner = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PIPELINE_ELMT", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPipelineElmt;

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
     * @var \AppBundle\Entity\CoolingFamily
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CoolingFamily")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_COOLING_FAMILY", referencedColumnName="ID_COOLING_FAMILY")
     * )
     */
    private $idCoolingFamily;

    /**
     * @return float
     */
    public function getLineVersion()
    {
        return $this->lineVersion;
    }

    /**
     * @param float $lineVersion
     *
     * @return self
     */
    public function setLineVersion($lineVersion)
    {
        $this->lineVersion = $lineVersion;

        return $this;
    }

    /**
     * @return integer
     */
    public function getLineRelease()
    {
        return $this->lineRelease;
    }

    /**
     * @param integer $lineRelease
     *
     * @return self
     */
    public function setLineRelease($lineRelease)
    {
        $this->lineRelease = $lineRelease;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLineDate()
    {
        return $this->lineDate;
    }

    /**
     * @param \DateTime $lineDate
     *
     * @return self
     */
    public function setLineDate(\DateTime $lineDate)
    {
        $this->lineDate = $lineDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getLineComment()
    {
        return $this->lineComment;
    }

    /**
     * @param string $lineComment
     *
     * @return self
     */
    public function setLineComment($lineComment)
    {
        $this->lineComment = $lineComment;

        return $this;
    }

    /**
     * @return string
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param string $manufacturer
     *
     * @return self
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEltType()
    {
        return $this->eltType;
    }

    /**
     * @param integer $eltType
     *
     * @return self
     */
    public function setEltType($eltType)
    {
        $this->eltType = $eltType;

        return $this;
    }

    /**
     * @return integer
     */
    public function getInsulationType()
    {
        return $this->insulationType;
    }

    /**
     * @param integer $insulationType
     *
     * @return self
     */
    public function setInsulationType($insulationType)
    {
        $this->insulationType = $insulationType;

        return $this;
    }

    /**
     * @return float
     */
    public function getElmtPrice()
    {
        return $this->elmtPrice;
    }

    /**
     * @param float $elmtPrice
     *
     * @return self
     */
    public function setElmtPrice($elmtPrice)
    {
        $this->elmtPrice = $elmtPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getEltSize()
    {
        return $this->eltSize;
    }

    /**
     * @param float $eltSize
     *
     * @return self
     */
    public function setEltSize($eltSize)
    {
        $this->eltSize = $eltSize;

        return $this;
    }

    /**
     * @return float
     */
    public function getEltLosses1()
    {
        return $this->eltLosses1;
    }

    /**
     * @param float $eltLosses1
     *
     * @return self
     */
    public function setEltLosses1($eltLosses1)
    {
        $this->eltLosses1 = $eltLosses1;

        return $this;
    }

    /**
     * @return float
     */
    public function getEltLosses2()
    {
        return $this->eltLosses2;
    }

    /**
     * @param float $eltLosses2
     *
     * @return self
     */
    public function setEltLosses2($eltLosses2)
    {
        $this->eltLosses2 = $eltLosses2;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEltImpIdStudy()
    {
        return $this->eltImpIdStudy;
    }

    /**
     * @param integer $eltImpIdStudy
     *
     * @return self
     */
    public function setEltImpIdStudy($eltImpIdStudy)
    {
        $this->eltImpIdStudy = $eltImpIdStudy;

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
    public function getIdPipelineElmt()
    {
        return $this->idPipelineElmt;
    }

    /**
     * @param integer $idPipelineElmt
     *
     * @return self
     */
    public function setIdPipelineElmt($idPipelineElmt)
    {
        $this->idPipelineElmt = $idPipelineElmt;

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
    

   