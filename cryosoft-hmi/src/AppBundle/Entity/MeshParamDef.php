<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MeshParamDef
 *
 * @ORM\Table(name="mesh_param_def")
 * @ORM\Entity
 */
class MeshParamDef
{
    /**
     * @var float
     *
     * @ORM\Column(name="MESH_1_SIZE", type="float", precision=10, scale=0, nullable=false)
     */
    private $mesh1Size;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_2_SIZE", type="float", precision=10, scale=0, nullable=false)
     */
    private $mesh2Size;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_3_SIZE", type="float", precision=10, scale=0, nullable=false)
     */
    private $mesh3Size;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_RATIO", type="float", precision=24, scale=0, nullable=false)
     */
    private $meshRatio;

    /**
     * @var \AppBundle\Entity\Ln2user
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Ln2user")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_USER", referencedColumnName="ID_USER")
     * })
     */
    private $idUser;



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
    public function getMeshRatio()
    {
        return $this->meshRatio;
    }

    /**
     * @param float $meshRatio
     *
     * @return self
     */
    public function setMeshRatio($meshRatio)
    {
        $this->meshRatio = $meshRatio;

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

