<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Logging
 *
 * @ORM\Table(name="logging")
 * @ORM\Entity
 */
class Logging
{
    /**
     * @var integer
     *
     * @ORM\Column(name="LOG_ITEM", type="integer", nullable=true)
     */
    private $logItem;

    /**
     * @var boolean
     *
     * @ORM\Column(name="LOG_ENABLED", type="boolean", nullable=true)
     */
    private $logEnabled;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_LOGGING", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLogging;


}

