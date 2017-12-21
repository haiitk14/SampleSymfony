<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LineDefinition
 *
 * @ORM\Table(name="line_definition", indexes={@ORM\Index(name="FK_LINE_DEFINITION_PIPE_GEN", columns={"ID_PIPE_GEN"}), @ORM\Index(name="FK_LINE_DEFINITION_LINE_ELMT", columns={"ID_PIPELINE_ELMT"})})
 * @ORM\Entity
 */
class LineDefinition
{
    /**
     * @var integer
     *
     * @ORM\Column(name="TYPE_ELMT", type="smallint", nullable=true)
     */
    private $typeElmt;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_LINE_DEFINITION", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLineDefinition;

    /**
     * @var \AppBundle\Entity\PipeGen
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PipeGen")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PIPE_GEN", referencedColumnName="ID_PIPE_GEN")
     * })
     */
    private $idPipeGen;

    /**
     * @var \AppBundle\Entity\LineElmt
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\LineElmt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PIPELINE_ELMT", referencedColumnName="ID_PIPELINE_ELMT")
     * })
     */
    private $idPipelineElmt;

    

    /**
     * @return integer
     */
    public function getTypeElmt()
    {
        return $this->typeElmt;
    }

    /**
     * @param integer $typeElmt
     *
     * @return self
     */
    public function setTypeElmt($typeElmt)
    {
        $this->typeElmt = $typeElmt;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdLineDefinition()
    {
        return $this->idLineDefinition;
    }

    /**
     * @param integer $idLineDefinition
     *
     * @return self
     */
    public function setIdLineDefinition($idLineDefinition)
    {
        $this->idLineDefinition = $idLineDefinition;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\PipeGen
     */
    public function getIdPipeGen()
    {
        return $this->idPipeGen;
    }

    /**
     * @param \AppBundle\Entity\PipeGen $idPipeGen
     *
     * @return self
     */
    public function setIdPipeGen(\AppBundle\Entity\PipeGen $idPipeGen)
    {
        $this->idPipeGen = $idPipeGen;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\LineElmt
     */
    public function getIdPipelineElmt()
    {
        return $this->idPipelineElmt;
    }

    /**
     * @param \AppBundle\Entity\LineElmt $idPipelineElmt
     *
     * @return self
     */
    public function setIdPipelineElmt(\AppBundle\Entity\LineElmt $idPipelineElmt)
    {
        $this->idPipelineElmt = $idPipelineElmt;

        return $this;
    }
}

