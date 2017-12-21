<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MeshGeneration
 *
 * @ORM\Table(name="mesh_generation", indexes={@ORM\Index(name="FK_MESH_GENERATION_PRODUCT", columns={"ID_PROD"})})
 * @ORM\Entity
 */
class MeshGeneration
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="MESH_1_FIXED", type="boolean", nullable=true)
     */
    private $mesh1Fixed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="MESH_2_FIXED", type="boolean", nullable=true)
     */
    private $mesh2Fixed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="MESH_3_FIXED", type="boolean", nullable=true)
     */
    private $mesh3Fixed;

    /**
     * @var integer
     *
     * @ORM\Column(name="MESH_1_MODE", type="smallint", nullable=true)
     */
    private $mesh1Mode;

    /**
     * @var integer
     *
     * @ORM\Column(name="MESH_2_MODE", type="smallint", nullable=true)
     */
    private $mesh2Mode;

    /**
     * @var integer
     *
     * @ORM\Column(name="MESH_3_MODE", type="smallint", nullable=true)
     */
    private $mesh3Mode;

    /**
     * @var integer
     *
     * @ORM\Column(name="MESH_1_NB", type="integer", nullable=true)
     */
    private $mesh1Nb;

    /**
     * @var integer
     *
     * @ORM\Column(name="MESH_2_NB", type="integer", nullable=true)
     */
    private $mesh2Nb;

    /**
     * @var integer
     *
     * @ORM\Column(name="MESH_3_NB", type="integer", nullable=true)
     */
    private $mesh3Nb;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_1_SIZE", type="float", precision=10, scale=0, nullable=true)
     */
    private $mesh1Size;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_2_SIZE", type="float", precision=10, scale=0, nullable=true)
     */
    private $mesh2Size;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_3_SIZE", type="float", precision=10, scale=0, nullable=true)
     */
    private $mesh3Size;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_1_INT", type="float", precision=24, scale=0, nullable=true)
     */
    private $mesh1Int;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_2_INT", type="float", precision=24, scale=0, nullable=true)
     */
    private $mesh2Int;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_3_INT", type="float", precision=24, scale=0, nullable=true)
     */
    private $mesh3Int;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_1_RATIO", type="float", precision=24, scale=0, nullable=true)
     */
    private $mesh1Ratio;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_2_RATIO", type="float", precision=24, scale=0, nullable=true)
     */
    private $mesh2Ratio;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_3_RATIO", type="float", precision=24, scale=0, nullable=true)
     */
    private $mesh3Ratio;

    /**
     * @var integer
     *
     * @ORM\Column(name="BEST_1_NB", type="integer", nullable=true)
     */
    private $best1Nb;

    /**
     * @var integer
     *
     * @ORM\Column(name="BEST_2_NB", type="integer", nullable=true)
     */
    private $best2Nb;

    /**
     * @var integer
     *
     * @ORM\Column(name="BEST_3_NB", type="integer", nullable=true)
     */
    private $best3Nb;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_MESH_GENERATION", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMeshGeneration;

    /**
     * @var \AppBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PROD", referencedColumnName="ID_PROD")
     * })
     */
    private $idProd;

    /**
     * @return boolean
     */
    public function isMesh1Fixed()
    {
        return $this->mesh1Fixed;
    }

    /**
     * @param boolean $mesh1Fixed
     *
     * @return self
     */
    public function setMesh1Fixed($mesh1Fixed)
    {
        $this->mesh1Fixed = $mesh1Fixed;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isMesh2Fixed()
    {
        return $this->mesh2Fixed;
    }

    /**
     * @param boolean $mesh2Fixed
     *
     * @return self
     */
    public function setMesh2Fixed($mesh2Fixed)
    {
        $this->mesh2Fixed = $mesh2Fixed;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isMesh3Fixed()
    {
        return $this->mesh3Fixed;
    }

    /**
     * @param boolean $mesh3Fixed
     *
     * @return self
     */
    public function setMesh3Fixed($mesh3Fixed)
    {
        $this->mesh3Fixed = $mesh3Fixed;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMesh1Mode()
    {
        return $this->mesh1Mode;
    }

    /**
     * @param integer $mesh1Mode
     *
     * @return self
     */
    public function setMesh1Mode($mesh1Mode)
    {
        $this->mesh1Mode = $mesh1Mode;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMesh2Mode()
    {
        return $this->mesh2Mode;
    }

    /**
     * @param integer $mesh2Mode
     *
     * @return self
     */
    public function setMesh2Mode($mesh2Mode)
    {
        $this->mesh2Mode = $mesh2Mode;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMesh3Mode()
    {
        return $this->mesh3Mode;
    }

    /**
     * @param integer $mesh3Mode
     *
     * @return self
     */
    public function setMesh3Mode($mesh3Mode)
    {
        $this->mesh3Mode = $mesh3Mode;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMesh1Nb()
    {
        return $this->mesh1Nb;
    }

    /**
     * @param integer $mesh1Nb
     *
     * @return self
     */
    public function setMesh1Nb($mesh1Nb)
    {
        $this->mesh1Nb = $mesh1Nb;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMesh2Nb()
    {
        return $this->mesh2Nb;
    }

    /**
     * @param integer $mesh2Nb
     *
     * @return self
     */
    public function setMesh2Nb($mesh2Nb)
    {
        $this->mesh2Nb = $mesh2Nb;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMesh3Nb()
    {
        return $this->mesh3Nb;
    }

    /**
     * @param integer $mesh3Nb
     *
     * @return self
     */
    public function setMesh3Nb($mesh3Nb)
    {
        $this->mesh3Nb = $mesh3Nb;

        return $this;
    }

    /**
     * @return float
     */
    public function getMesh1Size()
    {
        return $this->mesh1Size;
    }

    /**
     * @param float $mesh1Size
     *
     * @return self
     */
    public function setMesh1Size($mesh1Size)
    {
        $this->mesh1Size = $mesh1Size;

        return $this;
    }

    /**
     * @return float
     */
    public function getMesh2Size()
    {
        return $this->mesh2Size;
    }

    /**
     * @param float $mesh2Size
     *
     * @return self
     */
    public function setMesh2Size($mesh2Size)
    {
        $this->mesh2Size = $mesh2Size;

        return $this;
    }

    /**
     * @return float
     */
    public function getMesh3Size()
    {
        return $this->mesh3Size;
    }

    /**
     * @param float $mesh3Size
     *
     * @return self
     */
    public function setMesh3Size($mesh3Size)
    {
        $this->mesh3Size = $mesh3Size;

        return $this;
    }

    /**
     * @return float
     */
    public function getMesh1Int()
    {
        return $this->mesh1Int;
    }

    /**
     * @param float $mesh1Int
     *
     * @return self
     */
    public function setMesh1Int($mesh1Int)
    {
        $this->mesh1Int = $mesh1Int;

        return $this;
    }

    /**
     * @return float
     */
    public function getMesh2Int()
    {
        return $this->mesh2Int;
    }

    /**
     * @param float $mesh2Int
     *
     * @return self
     */
    public function setMesh2Int($mesh2Int)
    {
        $this->mesh2Int = $mesh2Int;

        return $this;
    }

    /**
     * @return float
     */
    public function getMesh3Int()
    {
        return $this->mesh3Int;
    }

    /**
     * @param float $mesh3Int
     *
     * @return self
     */
    public function setMesh3Int($mesh3Int)
    {
        $this->mesh3Int = $mesh3Int;

        return $this;
    }

    /**
     * @return float
     */
    public function getMesh1Ratio()
    {
        return $this->mesh1Ratio;
    }

    /**
     * @param float $mesh1Ratio
     *
     * @return self
     */
    public function setMesh1Ratio($mesh1Ratio)
    {
        $this->mesh1Ratio = $mesh1Ratio;

        return $this;
    }

    /**
     * @return float
     */
    public function getMesh2Ratio()
    {
        return $this->mesh2Ratio;
    }

    /**
     * @param float $mesh2Ratio
     *
     * @return self
     */
    public function setMesh2Ratio($mesh2Ratio)
    {
        $this->mesh2Ratio = $mesh2Ratio;

        return $this;
    }

    /**
     * @return float
     */
    public function getMesh3Ratio()
    {
        return $this->mesh3Ratio;
    }

    /**
     * @param float $mesh3Ratio
     *
     * @return self
     */
    public function setMesh3Ratio($mesh3Ratio)
    {
        $this->mesh3Ratio = $mesh3Ratio;

        return $this;
    }

    /**
     * @return integer
     */
    public function getBest1Nb()
    {
        return $this->best1Nb;
    }

    /**
     * @param integer $best1Nb
     *
     * @return self
     */
    public function setBest1Nb($best1Nb)
    {
        $this->best1Nb = $best1Nb;

        return $this;
    }

    /**
     * @return integer
     */
    public function getBest2Nb()
    {
        return $this->best2Nb;
    }

    /**
     * @param integer $best2Nb
     *
     * @return self
     */
    public function setBest2Nb($best2Nb)
    {
        $this->best2Nb = $best2Nb;

        return $this;
    }

    /**
     * @return integer
     */
    public function getBest3Nb()
    {
        return $this->best3Nb;
    }

    /**
     * @param integer $best3Nb
     *
     * @return self
     */
    public function setBest3Nb($best3Nb)
    {
        $this->best3Nb = $best3Nb;

        return $this;
    }

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
     * @return \AppBundle\Entity\Product
     */
    public function getIdProd()
    {
        return $this->idProd;
    }

    /**
     * @param \AppBundle\Entity\Product $idProd
     *
     * @return self
     */
    public function setIdProd(\AppBundle\Entity\Product $idProd)
    {
        $this->idProd = $idProd;

        return $this;
    }
}

