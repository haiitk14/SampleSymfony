<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Translation
 *
 * @ORM\Table(name="translation", indexes={@ORM\Index(name="IDX_B469456FAE6F8658", columns={"CODE_LANGUE"})})
 * @ORM\Entity
 */
class Translation
{
    /**
     * @var string
     *
     * @ORM\Column(name="LABEL", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_TRANSLATION", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idTranslation;

    /**
     * @var integer
     *
     * @ORM\Column(name="TRANS_TYPE", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $transType;

    /**
     * @var \AppBundle\Entity\Language
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CODE_LANGUE", referencedColumnName="CODE_LANGUE")
     * })
     */
    private $codeLangue;



    /**
     * Get the value of Label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdTranslation()
    {
        return $this->idTranslation;
    }

    /**
     * @param integer $idTranslation
     *
     * @return self
     */
    public function setIdTranslation($idTranslation)
    {
        $this->idTranslation = $idTranslation;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTransType()
    {
        return $this->transType;
    }

    /**
     * @param integer $transType
     *
     * @return self
     */
    public function setTransType($transType)
    {
        $this->transType = $transType;

        return $this;
    }

    /**
     * @return \AppBundle\Entity\Language
     */
    public function getCodeLangue()
    {
        return $this->codeLangue;
    }

    /**
     * @param \AppBundle\Entity\Language $codeLangue
     *
     * @return self
     */
    public function setCodeLangue(\AppBundle\Entity\Language $codeLangue)
    {
        $this->codeLangue = $codeLangue;

        return $this;
    }
}
