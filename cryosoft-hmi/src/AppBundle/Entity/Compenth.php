<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Compenth
 *
 * @ORM\Table(name="compenth", indexes={@ORM\Index(name="FK_COMPENTH_COMPENTH", columns={"ID_COMP"})})
 * @ORM\Entity
 */
class Compenth
{
    /**
     * @var float
     *
     * @ORM\Column(name="COMPTEMP", type="float", precision=24, scale=0, nullable=true)
     */
    private $comptemp;

    /**
     * @var float
     *
     * @ORM\Column(name="COMPENTH", type="float", precision=24, scale=0, nullable=true)
     */
    private $compenth;

    /**
     * @var float
     *
     * @ORM\Column(name="COMPCOND", type="float", precision=24, scale=0, nullable=true)
     */
    private $compcond;

    /**
     * @var float
     *
     * @ORM\Column(name="COMPDENS", type="float", precision=24, scale=0, nullable=true)
     */
    private $compdens;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_COMPENTH", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCompenth;

    /**
     * @var \AppBundle\Entity\Component
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Component")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_COMP", referencedColumnName="ID_COMP")
     * })
     */
    private $idComp;


}

