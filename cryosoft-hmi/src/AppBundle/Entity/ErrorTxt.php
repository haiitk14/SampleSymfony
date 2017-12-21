<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ErrorTxt
 *
 * @ORM\Table(name="error_txt", indexes={@ORM\Index(name="IDX_6FD71029AE6F8658", columns={"CODE_LANGUE"})})
 * @ORM\Entity
 */
class ErrorTxt
{
    /**
     * @var string
     *
     * @ORM\Column(name="ERR_TXT", type="text", nullable=true)
     */
    private $errTxt;

    /**
     * @var integer
     *
     * @ORM\Column(name="ERR_CODE", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $errCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="ERR_COMP", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $errComp;

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
     * @return string
     */
    public function getErrTxt()
    {
        return $this->errTxt;
    }

    /**
     * @param string $errTxt
     *
     * @return self
     */
    public function setErrTxt($errTxt)
    {
        $this->errTxt = $errTxt;

        return $this;
    }

    /**
     * @return integer
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    /**
     * @param integer $errCode
     *
     * @return self
     */
    public function setErrCode($errCode)
    {
        $this->errCode = $errCode;

        return $this;
    }

    /**
     * @return integer
     */
    public function getErrComp()
    {
        return $this->errComp;
    }

    /**
     * @param integer $errComp
     *
     * @return self
     */
    public function setErrComp($errComp)
    {
        $this->errComp = $errComp;

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

