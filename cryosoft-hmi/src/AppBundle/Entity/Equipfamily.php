<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipfamily
 *
 * @ORM\Table(name="equipfamily")
 * @ORM\Entity
 */
class Equipfamily
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="BATCH_PROCESS", type="boolean", nullable=true)
     */
    private $batchProcess;

    /**
     * @var integer
     *
     * @ORM\Column(name="TYPE_CELL", type="smallint", nullable=true)
     */
    private $typeCell;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_FAMILY", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFamily;



    /**
     * @return boolean
     */
    public function isBatchProcess()
    {
        return $this->batchProcess;
    }

    /**
     * @param boolean $batchProcess
     *
     * @return self
     */
    public function setBatchProcess($batchProcess)
    {
        $this->batchProcess = $batchProcess;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTypeCell()
    {
        return $this->typeCell;
    }

    /**
     * @param integer $typeCell
     *
     * @return self
     */
    public function setTypeCell($typeCell)
    {
        $this->typeCell = $typeCell;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdFamily()
    {
        return $this->idFamily;
    }

    /**
     * @param integer $idFamily
     *
     * @return self
     */
    public function setIdFamily($idFamily)
    {
        $this->idFamily = $idFamily;

        return $this;
    }
}

