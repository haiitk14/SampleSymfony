<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProdcharColorsDef
 *
 * @ORM\Table(name="prodchar_colors_def", indexes={@ORM\Index(name="FK_PRODCHAR_COLORS_DEF_COLOR_PALETTE", columns={"ID_COLOR"}), @ORM\Index(name="FK_PRODCHAR_COLORS_DEF_LN2USER", columns={"ID_USER"})})
 * @ORM\Entity
 */
class ProdcharColorsDef
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
     * @ORM\Column(name="ID_PRODCHAR_COLORS_DEF", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProdcharColorsDef;

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
    public function getIdProdcharColorsDef()
    {
        return $this->idProdcharColorsDef;
    }

    /**
     * @param integer $idProdcharColorsDef
     *
     * @return self
     */
    public function setIdProdcharColorsDef($idProdcharColorsDef)
    {
        $this->idProdcharColorsDef = $idProdcharColorsDef;

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

