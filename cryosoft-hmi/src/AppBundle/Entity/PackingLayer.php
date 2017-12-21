<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PackingLayer
 *
 * @ORM\Table(name="packing_layer", indexes={@ORM\Index(name="FK_PACKING_LAYER_PACKING_ELMT", columns={"ID_PACKING_ELMT"}), @ORM\Index(name="FK_PACKING_LAYER_PACKING", columns={"ID_PACKING"})})
 * @ORM\Entity
 */
class PackingLayer
{
    /**
     * @var float
     *
     * @ORM\Column(name="THICKNESS", type="float", precision=24, scale=0, nullable=true)
     */
    private $thickness;

    /**
     * @var integer
     *
     * @ORM\Column(name="PACKING_SIDE_NUMBER", type="smallint", nullable=true)
     */
    private $packingSideNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="PACKING_LAYER_ORDER", type="smallint", nullable=true)
     */
    private $packingLayerOrder;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PACKING_LAYER", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPackingLayer;

    /**
     * @var \AppBundle\Entity\PackingElmt
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PackingElmt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PACKING_ELMT", referencedColumnName="ID_PACKING_ELMT")
     * })
     */
    private $idPackingElmt;

    /**
     * @var \AppBundle\Entity\Packing
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Packing")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PACKING", referencedColumnName="ID_PACKING")
     * })
     */
    private $idPacking;

    /**
     * @return float
     */
    public function getThickness()
    {
        return $this->thickness;
    }

    /**
     * @param float $thickness
     */
    public function setThickness($thickness)
    {
        $this->thickness = $thickness;
    }

    /**
     * @return int
     */
    public function getPackingSideNumber()
    {
        return $this->packingSideNumber;
    }

    /**
     * @param int $packingSideNumber
     */
    public function setPackingSideNumber($packingSideNumber)
    {
        $this->packingSideNumber = $packingSideNumber;
    }

    /**
     * @return int
     */
    public function getPackingLayerOrder()
    {
        return $this->packingLayerOrder;
    }

    /**
     * @param int $packingLayerOrder
     */
    public function setPackingLayerOrder($packingLayerOrder)
    {
        $this->packingLayerOrder = $packingLayerOrder;
    }

    /**
     * @return int
     */
    public function getIdPackingLayer()
    {
        return $this->idPackingLayer;
    }

    /**
     * @param int $idPackingLayer
     */
    public function setIdPackingLayer($idPackingLayer)
    {
        $this->idPackingLayer = $idPackingLayer;
    }

    /**
     * @return \AppBundle\Entity\PackingElmt
     */
    public function getIdPackingElmt()
    {
        return $this->idPackingElmt;
    }

    /**
     * @param \AppBundle\Entity\PackingElmt $idPackingElmt
     *
     * @return self
     */
    public function setIdPackingElmt(\AppBundle\Entity\PackingElmt $idPackingElmt)
    {
        $this->idPackingElmt = $idPackingElmt;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Packing
     */
    public function getIdPacking()
    {
        return $this->idPacking;
    }

    /**
     * @param \AppBundle\Entity\Packing $idPacking
     *
     * @return self
     */
    public function setIdPacking(\AppBundle\Entity\Packing $idPacking)
    {
        $this->idPacking = $idPacking;

        return $this;
    }
}
