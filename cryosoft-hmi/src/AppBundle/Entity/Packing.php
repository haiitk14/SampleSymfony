<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Packing
 *
 * @ORM\Table(name="packing", indexes={@ORM\Index(name="FK_PACKING_SHAPE", columns={"ID_SHAPE"}), @ORM\Index(name="FK_PACKING_STUDIES", columns={"ID_STUDY"})})
 * @ORM\Entity
 */
class Packing
{
    /**
     * @var string
     *
     * @ORM\Column(name="NOMEMBMAT", type="string", length=32, nullable=true)
     */
    private $nomembmat;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PACKING", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPacking;

    /**
     * @var \AppBundle\Entity\Studies
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Studies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_STUDY", referencedColumnName="ID_STUDY")
     * })
     */
    private $idStudy;

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
     * Get the value of Nomembmat
     *
     * @return string
     */
    public function getNomembmat()
    {
        return $this->nomembmat;
    }

    /**
     * Set the value of Nomembmat
     *
     * @param string nomembmat
     *
     * @return self
     */
    public function setNomembmat($nomembmat)
    {
        $this->nomembmat = $nomembmat;

        return $this;
    }

    /**
     * Get the value of Id Packing
     *
     * @return integer
     */
    public function getIdPacking()
    {
        return $this->idPacking;
    }

    /**
     * Set the value of Id Packing
     *
     * @param integer idPacking
     *
     * @return self
     */
    public function setIdPacking($idPacking)
    {
        $this->idPacking = $idPacking;

        return $this;
    }

    /**
     * Get the value of Id Study
     *
     * @return \AppBundle\Entity\Studies
     */
    public function getIdStudy()
    {
        return $this->idStudy;
    }

    /**
     * Set the value of Id Study
     *
     * @param \AppBundle\Entity\Studies idStudy
     *
     * @return self
     */
    public function setIdStudy(\AppBundle\Entity\Studies $idStudy)
    {
        $this->idStudy = $idStudy;

        return $this;
    }

    /**
     * Get the value of Id Shape
     *
     * @return \AppBundle\Entity\Shape
     */
    public function getIdShape()
    {
        return $this->idShape;
    }

    /**
     * Set the value of Id Shape
     *
     * @param \AppBundle\Entity\Shape idShape
     *
     * @return self
     */
    public function setIdShape(\AppBundle\Entity\Shape $idShape)
    {
        $this->idShape = $idShape;

        return $this;
    }

}
