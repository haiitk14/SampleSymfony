<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InitialTemperature
 *
 * @ORM\Table(name="initial_temperature", indexes={@ORM\Index(name="IX_ID_PRODUCTION", columns={"ID_PRODUCTION"})})
 * @ORM\Entity
 */
class InitialTemperature
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_INITIAL_TEMP", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idInitialTemp;

    /**
     * @var integer
     *
     * @ORM\Column(name="MESH_1_ORDER", type="smallint", nullable=true)
     */
    private $mesh1Order;

    /**
     * @var integer
     *
     * @ORM\Column(name="MESH_2_ORDER", type="smallint", nullable=true)
     */
    private $mesh2Order;

    /**
     * @var integer
     *
     * @ORM\Column(name="MESH_3_ORDER", type="smallint", nullable=true)
     */
    private $mesh3Order;

    /**
     * @var float
     *
     * @ORM\Column(name="INITIAL_T", type="float", precision=24, scale=0, nullable=true)
     */
    private $initialT;

    /**
     * @var \Production
     *
     * @ORM\ManyToOne(targetEntity="Production")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_PRODUCTION", referencedColumnName="ID_PRODUCTION")
     * })
     */
    private $idProduction;



    /**
     * @return integer
     */
    public function getIdInitialTemp()
    {
        return $this->idInitialTemp;
    }

    /**
     * @param integer $idInitialTemp
     *
     * @return self
     */
    public function setIdInitialTemp($idInitialTemp)
    {
        $this->idInitialTemp = $idInitialTemp;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMesh1Order()
    {
        return $this->mesh1Order;
    }

    /**
     * @param integer $mesh1Order
     *
     * @return self
     */
    public function setMesh1Order($mesh1Order)
    {
        $this->mesh1Order = $mesh1Order;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMesh2Order()
    {
        return $this->mesh2Order;
    }

    /**
     * @param integer $mesh2Order
     *
     * @return self
     */
    public function setMesh2Order($mesh2Order)
    {
        $this->mesh2Order = $mesh2Order;

        return $this;
    }

    /**
     * @return integer
     */
    public function getMesh3Order()
    {
        return $this->mesh3Order;
    }

    /**
     * @param integer $mesh3Order
     *
     * @return self
     */
    public function setMesh3Order($mesh3Order)
    {
        $this->mesh3Order = $mesh3Order;

        return $this;
    }

    /**
     * @return float
     */
    public function getInitialT()
    {
        return $this->initialT;
    }

    /**
     * @param float $initialT
     *
     * @return self
     */
    public function setInitialT($initialT)
    {
        $this->initialT = $initialT;

        return $this;
    }

    /**
     * @return \Production
     */
    public function getIdProduction()
    {
        return $this->idProduction;
    }

    /**
     * @param  AppBundle\Entity\Production $idProduction
     *
     * @return self
     */
    public function setIdProduction( \AppBundle\Entity\Production $idProduction)
    {
        $this->idProduction = $idProduction;

        return $this;
    }
}

