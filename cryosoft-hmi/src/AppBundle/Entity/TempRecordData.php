<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TempRecordData
 *
 * @ORM\Table(name="temp_record_data", indexes={@ORM\Index(name="IX_ID_REC_POS", columns={"ID_REC_POS"})})
 * @ORM\Entity
 */
class TempRecordData
{
    /**
     * @var integer
     *
     * @ORM\Column(name="REC_AXIS_X_POS", type="integer", nullable=true)
     */
    private $recAxisXPos;

    /**
     * @var integer
     *
     * @ORM\Column(name="REC_AXIS_Y_POS", type="integer", nullable=true)
     */
    private $recAxisYPos;

    /**
     * @var integer
     *
     * @ORM\Column(name="REC_AXIS_Z_POS", type="integer", nullable=true)
     */
    private $recAxisZPos;

    /**
     * @var float
     *
     * @ORM\Column(name="TEMP", type="float", precision=24, scale=0, nullable=true)
     */
    private $temp;

    /**
     * @var float
     *
     * @ORM\Column(name="ENTH", type="float", precision=24, scale=0, nullable=true)
     */
    private $enth;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TRD_BUFFER", type="boolean", nullable=true)
     */
    private $trdBuffer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TRD_STATE", type="boolean", nullable=true)
     */
    private $trdState;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_TEMP_RECORD_DATA", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTempRecordData;

    /**
     * @var \AppBundle\Entity\RecordPosition
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RecordPosition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_REC_POS", referencedColumnName="ID_REC_POS")
     * })
     */
    private $idRecPos;


}

