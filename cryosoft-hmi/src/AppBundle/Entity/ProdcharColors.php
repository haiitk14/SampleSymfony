<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProdcharColors
 *
 * @ORM\Table(name="prodchar_colors", indexes={@ORM\Index(name="FK_PRODCHAR_COLORS_COLOR_PALETTE", columns={"ID_COLOR"}), @ORM\Index(name="FK_PRODCHAR_COLORS_PRODUCT", columns={"ID_PROD"})})
 * @ORM\Entity
 */
class ProdcharColors
{
    /**
     * @var integer
     *
     * @ORM\Column(name="LAYER_ORDER", type="integer", nullable=false)
     */
    private $layerOrder;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PRODCHAR_COLORS", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProdcharColors;

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
     * @var \AppBundle\Entity\ColorPalette
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ColorPalette")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_COLOR", referencedColumnName="ID_COLOR")
     * })
     */
    private $idColor;



    /**
     * @return integer
     */
    public function getLayerOrder()
    {
        return $this->layerOrder;
    }

    /**
     * @param integer $layerOrder
     *
     * @return self
     */
    public function setLayerOrder($layerOrder)
    {
        $this->layerOrder = $layerOrder;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdProdcharColors()
    {
        return $this->idProdcharColors;
    }

    /**
     * @param integer $idProdcharColors
     *
     * @return self
     */
    public function setIdProdcharColors($idProdcharColors)
    {
        $this->idProdcharColors = $idProdcharColors;

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
     * @return \AppBundle\Entity\ColorPalette
     */
    public function getIdColor()
    {
        return $this->idColor;
    }

    /**
     * @param \AppBundle\Entity\ColorPalette $idColor
     *
     * @return self
     */
    public function setIdColor(\AppBundle\Entity\ColorPalette $idColor)
    {
        $this->idColor = $idColor;

        return $this;
    }
}

