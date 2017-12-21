<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sysdiagrams
 *
 * @ORM\Table(name="sysdiagrams", uniqueConstraints={@ORM\UniqueConstraint(name="UK_principal_name", columns={"principal_id", "name"})})
 * @ORM\Entity
 */
class Sysdiagrams
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=160, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="principal_id", type="integer", nullable=false)
     */
    private $principalId;

    /**
     * @var integer
     *
     * @ORM\Column(name="version", type="integer", nullable=true)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="definition", type="blob", nullable=true)
     */
    private $definition;

    /**
     * @var integer
     *
     * @ORM\Column(name="diagram_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $diagramId;


}

