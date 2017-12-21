<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shape
 *
 * @ORM\Table(name="shape")
 * @ORM\Entity
 */
class Shape
{
    /**
     * @var integer
     *
     * @ORM\Column(name="SHAPECODE", type="integer", nullable=true)
     */
    private $shapecode;

    /**
     * @var string
     *
     * @ORM\Column(name="SHAPEPICT", type="string", length=255, nullable=true)
     */
    private $shapepict;

    /**
     * @var boolean
     *
     * @ORM\Column(name="SYM_1", type="boolean", nullable=true)
     */
    private $sym1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="SYM_2", type="boolean", nullable=true)
     */
    private $sym2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="SYM_3", type="boolean", nullable=true)
     */
    private $sym3;

    /**
     * @var boolean
     *
     * @ORM\Column(name="AXISYM_1", type="boolean", nullable=true)
     */
    private $axisym1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="AXISYM_2", type="boolean", nullable=true)
     */
    private $axisym2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="AXISYM_3", type="boolean", nullable=true)
     */
    private $axisym3;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_SHAPE", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idShape;



    /**
     * @return integer
     */
    public function getShapecode()
    {
        return $this->shapecode;
    }

    /**
     * @param integer $shapecode
     *
     * @return self
     */
    public function setShapecode($shapecode)
    {
        $this->shapecode = $shapecode;

        return $this;
    }

    /**
     * @return string
     */
    public function getShapepict()
    {
        return $this->shapepict;
    }

    /**
     * @param string $shapepict
     *
     * @return self
     */
    public function setShapepict($shapepict)
    {
        $this->shapepict = $shapepict;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSym1()
    {
        return $this->sym1;
    }

    /**
     * @param boolean $sym1
     *
     * @return self
     */
    public function setSym1($sym1)
    {
        $this->sym1 = $sym1;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSym2()
    {
        return $this->sym2;
    }

    /**
     * @param boolean $sym2
     *
     * @return self
     */
    public function setSym2($sym2)
    {
        $this->sym2 = $sym2;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSym3()
    {
        return $this->sym3;
    }

    /**
     * @param boolean $sym3
     *
     * @return self
     */
    public function setSym3($sym3)
    {
        $this->sym3 = $sym3;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAxisym1()
    {
        return $this->axisym1;
    }

    /**
     * @param boolean $axisym1
     *
     * @return self
     */
    public function setAxisym1($axisym1)
    {
        $this->axisym1 = $axisym1;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAxisym2()
    {
        return $this->axisym2;
    }

    /**
     * @param boolean $axisym2
     *
     * @return self
     */
    public function setAxisym2($axisym2)
    {
        $this->axisym2 = $axisym2;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAxisym3()
    {
        return $this->axisym3;
    }

    /**
     * @param boolean $axisym3
     *
     * @return self
     */
    public function setAxisym3($axisym3)
    {
        $this->axisym3 = $axisym3;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdShape()
    {
        return $this->idShape;
    }

    /**
     * @param integer $idShape
     *
     * @return self
     */
    public function setIdShape($idShape)
    {
        $this->idShape = $idShape;

        return $this;
    }
    public function __toString() {
        return $this->idShape.'';
    }

}

