<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoolingFamily
 *
 * @ORM\Table(name="cooling_family")
 * @ORM\Entity
 */
class CoolingFamily
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_COOLING_FAMILY", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCoolingFamily;

    

    /**
     * @return integer
     */
    public function getIdCoolingFamily()
    {
        return $this->idCoolingFamily;
    }

    /**
     * @param integer $idCoolingFamily
     *
     * @return self
     */
    public function setIdCoolingFamily($idCoolingFamily)
    {
        $this->idCoolingFamily = $idCoolingFamily;

        return $this;
    }
}

