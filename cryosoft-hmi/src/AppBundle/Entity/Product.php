<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="FK_PRODUCT_STUDIES", columns={"ID_STUDY"})})
 * @ORM\Entity
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_MESH_GENERATION", type="integer", nullable=true)
     */
    private $idMeshGeneration;

    /**
     * @var string
     *
     * @ORM\Column(name="PRODNAME", type="string", length=32, nullable=true)
     */
    private $prodname;

    /**
     * @var boolean
     *
     * @ORM\Column(name="PROD_ISO", type="boolean", nullable=true)
     */
    private $prodIso;

    /**
     * @var float
     *
     * @ORM\Column(name="PROD_WEIGHT", type="float", precision=10, scale=0, nullable=true)
     */
    private $prodWeight;

    /**
     * @var float
     *
     * @ORM\Column(name="PROD_REALWEIGHT", type="float", precision=10, scale=0, nullable=true)
     */
    private $prodRealweight;

    /**
     * @var float
     *
     * @ORM\Column(name="PROD_VOLUME", type="float", precision=10, scale=0, nullable=true)
     */
    private $prodVolume;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PROD", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProd;

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
    public function getIdMeshGeneration()
    {
        return $this->idMeshGeneration;
    }

    /**
     * @param integer $idMeshGeneration
     *
     * @return self
     */
    public function setIdMeshGeneration($idMeshGeneration)
    {
        $this->idMeshGeneration = $idMeshGeneration;

        return $this;
    }

    /**
     * @return string
     */
    public function getProdname()
    {
        return $this->prodname;
    }

    /**
     * @param string $prodname
     *
     * @return self
     */
    public function setProdname($prodname)
    {
        $this->prodname = $prodname;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isProdIso()
    {
        return $this->prodIso;
    }

    /**
     * @param boolean $prodIso
     *
     * @return self
     */
    public function setProdIso($prodIso)
    {
        $this->prodIso = $prodIso;

        return $this;
    }

    /**
     * @return float
     */
    public function getProdWeight()
    {
        return $this->prodWeight;
    }

    /**
     * @param float $prodWeight
     *
     * @return self
     */
    public function setProdWeight($prodWeight)
    {
        $this->prodWeight = $prodWeight;

        return $this;
    }

    /**
     * @return float
     */
    public function getProdRealweight()
    {
        return $this->prodRealweight;
    }

    /**
     * @param float $prodRealweight
     *
     * @return self
     */
    public function setProdRealweight($prodRealweight)
    {
        $this->prodRealweight = $prodRealweight;

        return $this;
    }

    /**
     * @return float
     */
    public function getProdVolume()
    {
        return $this->prodVolume;
    }

    /**
     * @param float $prodVolume
     *
     * @return self
     */
    public function setProdVolume($prodVolume)
    {
        $this->prodVolume = $prodVolume;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdProd()
    {
        return $this->idProd;
    }

    /**
     * @param integer $idProd
     *
     * @return self
     */
    public function setIdProd($idProd)
    {
        $this->idProd = $idProd;

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

