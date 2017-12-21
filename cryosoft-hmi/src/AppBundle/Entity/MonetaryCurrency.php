<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MonetaryCurrency
 *
 * @ORM\Table(name="monetary_currency")
 * @ORM\Entity
 */
class MonetaryCurrency
{
    /**
     * @var string
     *
     * @ORM\Column(name="MONEY_TEXT", type="string", length=255, nullable=true)
     */
    private $moneyText;

    /**
     * @var string
     *
     * @ORM\Column(name="MONEY_SYMB", type="string", length=255, nullable=true)
     */
    private $moneySymb;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_MONETARY_CURRENCY", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMonetaryCurrency;



    /**
     * @return string
     */
    public function getMoneyText()
    {
        return $this->moneyText;
    }

    /**
     * @param string $moneyText
     *
     * @return self
     */
    public function setMoneyText($moneyText)
    {
        $this->moneyText = $moneyText;

        return $this;
    }

    /**
     * @return string
     */
    public function getMoneySymb()
    {
        return $this->moneySymb;
    }

    /**
     * @param string $moneySymb
     *
     * @return self
     */
    public function setMoneySymb($moneySymb)
    {
        $this->moneySymb = $moneySymb;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdMonetaryCurrency()
    {
        return $this->idMonetaryCurrency;
    }

    /**
     * @param integer $idMonetaryCurrency
     *
     * @return self
     */
    public function setIdMonetaryCurrency($idMonetaryCurrency)
    {
        $this->idMonetaryCurrency = $idMonetaryCurrency;

        return $this;
    }
    public function __toString()
    {
        try {
            return (string) $this->idMonetaryCurrency;;
        } catch (Exception $exception) {
            return '';
        }
        
    }
}

