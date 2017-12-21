<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductElmt
 *
 * @ORM\Table(name="product_elmt", indexes={@ORM\Index(name="FK_PRODUCT_ELMT_SHAPE", columns={"ID_SHAPE"}), @ORM\Index(name="FK_PRODUCT_ELMT_COMPONENT", columns={"ID_COMP"}), @ORM\Index(name="FK_PRODUCT_ELMT_PRODUCT", columns={"ID_PROD"})})
 * @ORM\Entity
 */
class ProductElmt
{
    /**
     * @var string
     *
     * @ORM\Column(name="PROD_ELMT_NAME", type="string", length=255, nullable=true)
     */
    private $prodElmtName;
    /**
     * @var float
     *
     * @ORM\Column(name="SHAPE_PARAM1", type="float", precision=10, scale=0, nullable=true)
     */
  
    private $shapeParam1;

    /**
     * @var float
     *
     * @ORM\Column(name="SHAPE_PARAM2", type="float", precision=24, scale=0, nullable=true)
     */
    private $shapeParam2;

    /**
     * @var float
     *
     * @ORM\Column(name="SHAPE_PARAM3", type="float", precision=24, scale=0, nullable=true)
     */
    private $shapeParam3;

    /**
     * @var float
     *
     * @ORM\Column(name="PROD_DEHYD", type="float", precision=24, scale=0, nullable=true)
     */
    private $prodDehyd;

    /**
     * @var float
     *
     * @ORM\Column(name="PROD_DEHYD_COST", type="float", precision=24, scale=0, nullable=true)
     */
    private $prodDehydCost;

    /**
     * @var float
     *
     * @ORM\Column(name="SHAPE_POS1", type="float", precision=24, scale=0, nullable=true)
     */
    private $shapePos1;

    /**
     * @var float
     *
     * @ORM\Column(name="SHAPE_POS2", type="float", precision=24, scale=0, nullable=true)
     */
    private $shapePos2;

    /**
     * @var float
     *
     * @ORM\Column(name="SHAPE_POS3", type="float", precision=24, scale=0, nullable=true)
     */
    private $shapePos3;

    /**
     * @var boolean
     *
     * @ORM\Column(name="PROD_ELMT_ISO", type="boolean", nullable=true)
     */
    private $prodElmtIso;

    /**
     * @var float
     *
     * @ORM\Column(name="ORIGINAL_THICK", type="float", precision=10, scale=0, nullable=true)
     */
    private $originalThick;

    /**
     * @var boolean
     *
     * @ORM\Column(name="NODE_DECIM", type="boolean", nullable=true)
     */
    private $nodeDecim;

    /**
     * @var integer
     *
     * @ORM\Column(name="INSERT_LINE_ORDER", type="integer", nullable=true)
     */
    private $insertLineOrder;

    /**
     * @var float
     *
     * @ORM\Column(name="PROD_ELMT_WEIGHT", type="float", precision=10, scale=0, nullable=true)
     */
    private $prodElmtWeight;

    /**
     * @var float
     *
     * @ORM\Column(name="PROD_ELMT_REALWEIGHT", type="float", precision=10, scale=0, nullable=true)
     */
    private $prodElmtRealweight;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PRODUCT_ELMT", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProductElmt;

    /**
     * @var \AppBundle\Entity\Shape
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Shape")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_SHAPE", referencedColumnName="ID_SHAPE")
     * })
     */
    private $idShape;

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
     * @var \AppBundle\Entity\Component
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Component")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_COMP", referencedColumnName="ID_COMP")
     * })
     */
    private $idComp;



    /**
     * @return string
     */
    public function getProdElmtName()
    {
        return $this->prodElmtName;
    }

    /**
     * @param string $prodElmtName
     *
     * @return self
     */
    public function setProdElmtName($prodElmtName)
    {
        $this->prodElmtName = $prodElmtName;

        return $this;
    }

    /**
     * Get the value of Shape Param
     *
     * @return float
     */
    public function getShapeParam1()
    {
        return $this->shapeParam1;
    }

    /**
     * @param float $shapeParam1
     *
     * @return self
     */
    public function setShapeParam1($shapeParam1)
    {
        $this->shapeParam1 = $shapeParam1;

        return $this;
    }

    /**
     * Get the value of Shape Param
     *
     * @return float
     */
    public function getShapeParam2()
    {
        return $this->shapeParam2;
    }

    /**
     * @param float $shapeParam2
     *
     * @return self
     */
    public function setShapeParam2($shapeParam2)
    {
        $this->shapeParam2 = $shapeParam2;

        return $this;
    }

    /**
     * Get the value of Shape Param
     *
     * @return float
     */
    public function getShapeParam3()
    {
        return $this->shapeParam3;
    }

    /**
     * @param float $shapeParam3
     *
     * @return self
     */
    public function setShapeParam3($shapeParam3)
    {
        $this->shapeParam3 = $shapeParam3;

        return $this;
    }

    /**
     * Get the value of Prod Dehyd
     *
     * @return float
     */
    public function getProdDehyd()
    {
        return $this->prodDehyd;
    }

    /**
     * @param float $prodDehyd
     *
     * @return self
     */
    public function setProdDehyd($prodDehyd)
    {
        $this->prodDehyd = $prodDehyd;

        return $this;
    }

    /**
     * Get the value of Prod Dehyd Cost
     *
     * @return float
     */
    public function getProdDehydCost()
    {
        return $this->prodDehydCost;
    }

    /**
     * @param float $prodDehydCost
     *
     * @return self
     */
    public function setProdDehydCost($prodDehydCost)
    {
        $this->prodDehydCost = $prodDehydCost;

        return $this;
    }

    /**
     * Get the value of Shape Pos
     *
     * @return float
     */
    public function getShapePos1()
    {
        return $this->shapePos1;
    }

    /**
     * @param float $shapePos1
     *
     * @return self
     */
    public function setShapePos1($shapePos1)
    {
        $this->shapePos1 = $shapePos1;

        return $this;
    }

    /**
     * Get the value of Shape Pos
     *
     * @return float
     */
    public function getShapePos2()
    {
        return $this->shapePos2;
    }

    /**
     * @param float $shapePos2
     *
     * @return self
     */
    public function setShapePos2($shapePos2)
    {
        $this->shapePos2 = $shapePos2;

        return $this;
    }

    /**
     * Get the value of Shape Pos
     *
     * @return float
     */
    public function getShapePos3()
    {
        return $this->shapePos3;
    }

    /**
     * @param float $shapePos3
     *
     * @return self
     */
    public function setShapePos3($shapePos3)
    {
        $this->shapePos3 = $shapePos3;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getProdElmtIso()
    {
        return $this->prodElmtIso;
    }

    /**
     * @param boolean $prodElmtIso
     *
     * @return self
     */
    public function setProdElmtIso($prodElmtIso)
    {
        $this->prodElmtIso = $prodElmtIso;

        return $this;
    }

    /**
     * Get the value of Original Thick
     *
     * @return float
     */
    public function getOriginalThick()
    {
        return $this->originalThick;
    }

    /**
     * @param float $originalThick
     *
     * @return self
     */
    public function setOriginalThick($originalThick)
    {
        $this->originalThick = $originalThick;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getNodeDecim()
    {
        return $this->nodeDecim;
    }

    /**
     * @param boolean $nodeDecim
     *
     * @return self
     */
    public function setNodeDecim($nodeDecim)
    {
        $this->nodeDecim = $nodeDecim;

        return $this;
    }

    /**
     * @return integer
     */
    public function getInsertLineOrder()
    {
        return $this->insertLineOrder;
    }

    /**
     * @param integer $insertLineOrder
     *
     * @return self
     */
    public function setInsertLineOrder($insertLineOrder)
    {
        $this->insertLineOrder = $insertLineOrder;

        return $this;
    }

    /**
     * Get the value of Prod Elmt Weight
     *
     * @return float
     */
    public function getProdElmtWeight()
    {
        return $this->prodElmtWeight;
    }

    /**
     * @param float $prodElmtWeight
     *
     * @return self
     */
    public function setProdElmtWeight($prodElmtWeight)
    {
        $this->prodElmtWeight = $prodElmtWeight;

        return $this;
    }

    /**
     * Get the value of Prod Elmt Realweight
     *
     * @return float
     */
    public function getProdElmtRealweight()
    {
        return $this->prodElmtRealweight;
    }

    /**
     * @param float $prodElmtRealweight
     *
     * @return self
     */
    public function setProdElmtRealweight($prodElmtRealweight)
    {
        $this->prodElmtRealweight = $prodElmtRealweight;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdProductElmt()
    {
        return $this->idProductElmt;
    }

    /**
     * @param integer $idProductElmt
     *
     * @return self
     */
    public function setIdProductElmt($idProductElmt)
    {
        $this->idProductElmt = $idProductElmt;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Shape
     */
    public function getIdShape()
    {
        return $this->idShape;
    }

    /**
     * @param \AppBundle\Entity\Shape $idShape
     *
     * @return self
     */
    public function setIdShape(\AppBundle\Entity\Shape $idShape)
    {
        $this->idShape = $idShape;

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

    /**
     * @return \AppBundle\Entity\Component
     */
    public function getIdComp()
    {
        return $this->idComp;
    }

    /**
     * @param \AppBundle\Entity\Component $idComp
     *
     * @return self
     */
    public function setIdComp(\AppBundle\Entity\Component $idComp)
    {
        $this->idComp = $idComp;

        return $this;
    }
}
