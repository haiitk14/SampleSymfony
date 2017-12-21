<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MeshPosition
 *
 * @ORM\Table(name="mesh_position", indexes={@ORM\Index(name="IX_ID_PROD_ELT", columns={"ID_PRODUCT_ELMT"})})
 * @ORM\Entity
 */
class MeshPosition
{
    /**
     * @var integer
     *
     * @ORM\Column(name="MESH_AXIS", type="smallint", nullable=true)
     */
    private $meshAxis;

    /**
     * @var integer
     *
     * @ORM\Column(name="MESH_ORDER", type="smallint", nullable=true)
     */
    private $meshOrder;

    /**
     * @var float
     *
     * @ORM\Column(name="MESH_AXIS_POS", type="float", precision=24, scale=0, nullable=true)
     */
    private $meshAxisPos;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_MESH_POSITION", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMeshPosition;

    /**
     * @var \AppBundle\Entity\ProductElmt
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductElmt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCT_ELMT", referencedColumnName="ID_PRODUCT_ELMT")
     * })
     */
    private $idProductElmt;



    /**
     * @return integer
     */
    public function getMeshAxis()
    {
        return $this->meshAxis;
    }

    /**
     * @param integer $meshAxis
     *
     * @return self
     */
    public function setMeshAxis($meshAxis)
    {
        $this->meshAxis = $meshAxis;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMeshOrder()
    {
        return $this->meshOrder;
    }

    /**
     * @param integer $meshOrder
     *
     * @return self
     */
    public function setMeshOrder($meshOrder)
    {
        $this->meshOrder = $meshOrder;

        return $this;
    }

    /**
     * @return float
     */
    public function getMeshAxisPos()
    {
        return $this->meshAxisPos;
    }

    /**
     * @param float $meshAxisPos
     *
     * @return self
     */
    public function setMeshAxisPos($meshAxisPos)
    {
        $this->meshAxisPos = $meshAxisPos;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdMeshPosition()
    {
        return $this->idMeshPosition;
    }

    /**
     * @param integer $idMeshPosition
     *
     * @return self
     */
    public function setIdMeshPosition($idMeshPosition)
    {
        $this->idMeshPosition = $idMeshPosition;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\ProductElmt
     */
    public function getIdProductElmt()
    {
        return $this->idProductElmt;
    }

    /**
     * @param \AppBundle\Entity\ProductElmt $idProductElmt
     *
     * @return self
     */
    public function setIdProductElmt(\AppBundle\Entity\ProductElmt $idProductElmt)
    {
        $this->idProductElmt = $idProductElmt;

        return $this;
    }

}
