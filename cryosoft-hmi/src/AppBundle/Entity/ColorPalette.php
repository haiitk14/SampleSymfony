<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ColorPalette
 *
 * @ORM\Table(name="color_palette")
 * @ORM\Entity
 */
class ColorPalette
{
    /**
     * @var integer
     *
     * @ORM\Column(name="COLOR_ORDER", type="integer", nullable=true)
     */
    private $colorOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="COLOR_NAME", type="string", length=50, nullable=false)
     */
    private $colorName;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE_HEXA", type="string", length=50, nullable=false)
     */
    private $codeHexa;

    /**
     * @var string
     *
     * @ORM\Column(name="COLOR_TEXT", type="string", length=50, nullable=false)
     */
    private $colorText;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_COLOR", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idColor;



    /**
     * @return integer
     */
    public function getColorOrder()
    {
        return $this->colorOrder;
    }

    /**
     * @param integer $colorOrder
     *
     * @return self
     */
    public function setColorOrder($colorOrder)
    {
        $this->colorOrder = $colorOrder;

        return $this;
    }

    /**
     * @return string
     */
    public function getColorName()
    {
        return $this->colorName;
    }

    /**
     * @param string $colorName
     *
     * @return self
     */
    public function setColorName($colorName)
    {
        $this->colorName = $colorName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCodeHexa()
    {
        return $this->codeHexa;
    }

    /**
     * @param string $codeHexa
     *
     * @return self
     */
    public function setCodeHexa($codeHexa)
    {
        $this->codeHexa = $codeHexa;

        return $this;
    }

    /**
     * @return string
     */
    public function getColorText()
    {
        return $this->colorText;
    }

    /**
     * @param string $colorText
     *
     * @return self
     */
    public function setColorText($colorText)
    {
        $this->colorText = $colorText;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdColor()
    {
        return $this->idColor;
    }

    /**
     * @param integer $idColor
     *
     * @return self
     */
    public function setIdColor($idColor)
    {
        $this->idColor = $idColor;

        return $this;
    }
}

