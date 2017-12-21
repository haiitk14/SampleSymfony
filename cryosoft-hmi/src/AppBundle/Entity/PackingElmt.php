<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PackingElmt
 *
 * @ORM\Table(name="packing_elmt", indexes={@ORM\Index(name="FK_PACKING_ELMT_LN2USER", columns={"ID_USER"})})
 * @ORM\Entity
 */
class PackingElmt
{
    /**
     * @var float
     *
     * @ORM\Column(name="PACKING_VERSION", type="float", precision=24, scale=0, nullable=true)
     */
    private $packingVersion;

    /**
     * @var integer
     *
     * @ORM\Column(name="PACKING_RELEASE", type="smallint", nullable=true)
     */
    private $packingRelease;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="PACKING_DATE", type="datetime", nullable=true)
     */
    private $packingDate;

    /**
     * @var string
     *
     * @ORM\Column(name="PACKING_COMMENT", type="string", length=2000, nullable=true)
     */
    private $packingComment;

    /**
     * @var float
     *
     * @ORM\Column(name="PACKINGCOND", type="float", precision=24, scale=0, nullable=true)
     */
    private $packingcond;

    /**
     * @var integer
     *
     * @ORM\Column(name="PACK_IMP_ID_STUDY", type="integer", nullable=true)
     */
    private $packImpIdStudy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="OPEN_BY_OWNER", type="boolean", nullable=true)
     */
    private $openByOwner = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PACKING_ELMT", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPackingElmt;

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
     * Get the value of Packing Version
     *
     * @return float
     */
    public function getPackingVersion()
    {
        return $this->packingVersion;
    }

    /**
     * Set the value of Packing Version
     *
     * @param float packingVersion
     *
     * @return self
     */
    public function setPackingVersion($packingVersion)
    {
        $this->packingVersion = $packingVersion;

        return $this;
    }

    /**
     * Get the value of Packing Release
     *
     * @return integer
     */
    public function getPackingRelease()
    {
        return $this->packingRelease;
    }

    /**
     * Set the value of Packing Release
     *
     * @param integer packingRelease
     *
     * @return self
     */
    public function setPackingRelease($packingRelease)
    {
        $this->packingRelease = $packingRelease;

        return $this;
    }

    /**
     * Get the value of Packing Date
     *
     * @return \DateTime
     */
    public function getPackingDate()
    {
        return $this->packingDate;
    }

    /**
     * Set the value of Packing Date
     *
     * @param \DateTime packingDate
     *
     * @return self
     */
    public function setPackingDate(\DateTime $packingDate)
    {
        $this->packingDate = $packingDate;

        return $this;
    }

    /**
     * Get the value of Packing Comment
     *
     * @return string
     */
    public function getPackingComment()
    {
        return $this->packingComment;
    }

    /**
     * Set the value of Packing Comment
     *
     * @param string packingComment
     *
     * @return self
     */
    public function setPackingComment($packingComment)
    {
        $this->packingComment = $packingComment;

        return $this;
    }

    /**
     * Get the value of Packingcond
     *
     * @return float
     */
    public function getPackingcond()
    {
        return $this->packingcond;
    }

    /**
     * Set the value of Packingcond
     *
     * @param float packingcond
     *
     * @return self
     */
    public function setPackingcond($packingcond)
    {
        $this->packingcond = $packingcond;

        return $this;
    }

    /**
     * Get the value of Pack Imp Id Study
     *
     * @return integer
     */
    public function getPackImpIdStudy()
    {
        return $this->packImpIdStudy;
    }

    /**
     * Set the value of Pack Imp Id Study
     *
     * @param integer packImpIdStudy
     *
     * @return self
     */
    public function setPackImpIdStudy($packImpIdStudy)
    {
        $this->packImpIdStudy = $packImpIdStudy;

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
    public function getIdPackingElmt()
    {
        return $this->idPackingElmt;
    }

    /**
     * Set the value of Id Packing Elmt
     *
     * @param integer idPackingElmt
     *
     * @return self
     */
    public function setIdPackingElmt($idPackingElmt)
    {
        $this->idPackingElmt = $idPackingElmt;

        return $this;
    }

    /**
     * Get the value of Id User
     *
     * @return \AppBundle\Entity\Ln2user
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set the value of Id User
     *
     * @param \AppBundle\Entity\Ln2user idUser
     *
     * @return self
     */
    public function setIdUser(\AppBundle\Entity\Ln2user $idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

}
