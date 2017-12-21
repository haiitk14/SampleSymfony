<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MinMax
 *
 * @ORM\Table(name="min_max")
 * @ORM\Entity
 */
class MinMax
{
    /**
     * @var integer
     *
     * @ORM\Column(name="LIMIT_ITEM", type="integer", nullable=true)
     */
    private $limitItem;

    /**
     * @var float
     *
     * @ORM\Column(name="LIMIT_MAX", type="float", precision=10, scale=0, nullable=true)
     */
    private $limitMax;

    /**
     * @var float
     *
     * @ORM\Column(name="LIMIT_MIN", type="float", precision=10, scale=0, nullable=true)
     */
    private $limitMin;

    /**
     * @var float
     *
     * @ORM\Column(name="DEFAULT_VALUE", type="float", precision=10, scale=0, nullable=true)
     */
    private $defaultValue;

    /**
     * @var string
     *
     * @ORM\Column(name="COMMENT", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_MIN_MAX", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMinMax;



    /**
     * @return integer
     */
    public function getLimitItem()
    {
        return $this->limitItem;
    }

    /**
     * @param integer $limitItem
     *
     * @return self
     */
    public function setLimitItem($limitItem)
    {
        $this->limitItem = $limitItem;

        return $this;
    }

    /**
     * @return float
     */
    public function getLimitMax()
    {
        return $this->limitMax;
    }

    /**
     * @param float $limitMax
     *
     * @return self
     */
    public function setLimitMax($limitMax)
    {
        $this->limitMax = $limitMax;

        return $this;
    }

    /**
     * @return float
     */
    public function getLimitMin()
    {
        return $this->limitMin;
    }

    /**
     * @param float $limitMin
     *
     * @return self
     */
    public function setLimitMin($limitMin)
    {
        $this->limitMin = $limitMin;

        return $this;
    }

    /**
     * @return float
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param float $defaultValue
     *
     * @return self
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return self
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdMinMax()
    {
        return $this->idMinMax;
    }

    /**
     * @param integer $idMinMax
     *
     * @return self
     */
    public function setIdMinMax($idMinMax)
    {
        $this->idMinMax = $idMinMax;

        return $this;
    }
}

