<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrecalcLdgRatePrm
 *
 * @ORM\Table(name="precalc_ldg_rate_prm", indexes={@ORM\Index(name="FK_PRECALC_LDG_RATE_PRM_STUDIES", columns={"ID_STUDY"})})
 * @ORM\Entity
 */
class PrecalcLdgRatePrm
{
    /**
     * @var float
     *
     * @ORM\Column(name="L_INTERVAL", type="float", precision=10, scale=0, nullable=true)
     */
    private $lInterval;

    /**
     * @var float
     *
     * @ORM\Column(name="W_INTERVAL", type="float", precision=10, scale=0, nullable=true)
     */
    private $wInterval;

    /**
     * @var float
     *
     * @ORM\Column(name="PRECALC_LDG_TR", type="float", precision=10, scale=0, nullable=true)
     */
    private $precalcLdgTr;

    /**
     * @var float
     *
     * @ORM\Column(name="APPROX_LDG_RATE", type="float", precision=10, scale=0, nullable=true)
     */
    private $approxLdgRate;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PRECALC_LDG_RATE_PRM", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPrecalcLdgRatePrm;

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
    public function getLInterval()
    {
        return $this->lInterval;
    }

    /**
     * @param float $lInterval
     *
     * @return self
     */
    public function setLInterval($lInterval)
    {
        $this->lInterval = $lInterval;

        return $this;
    }

    /**
     * @return float
     */
    public function getWInterval()
    {
        return $this->wInterval;
    }

    /**
     * @param float $wInterval
     *
     * @return self
     */
    public function setWInterval($wInterval)
    {
        $this->wInterval = $wInterval;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrecalcLdgTr()
    {
        return $this->precalcLdgTr;
    }

    /**
     * @param float $precalcLdgTr
     *
     * @return self
     */
    public function setPrecalcLdgTr($precalcLdgTr)
    {
        $this->precalcLdgTr = $precalcLdgTr;

        return $this;
    }

    /**
     * @return float
     */
    public function getApproxLdgRate()
    {
        return $this->approxLdgRate;
    }

    /**
     * @param float $approxLdgRate
     *
     * @return self
     */
    public function setApproxLdgRate($approxLdgRate)
    {
        $this->approxLdgRate = $approxLdgRate;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdPrecalcLdgRatePrm()
    {
        return $this->idPrecalcLdgRatePrm;
    }

    /**
     * @param integer $idPrecalcLdgRatePrm
     *
     * @return self
     */
    public function setIdPrecalcLdgRatePrm($idPrecalcLdgRatePrm)
    {
        $this->idPrecalcLdgRatePrm = $idPrecalcLdgRatePrm;

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

