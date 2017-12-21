<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity
 */
class Language
{
    /**
     * @var string
     *
     * @ORM\Column(name="LANG_NAME", type="string", length=2, nullable=false)
     */
    private $langName;

    /**
     * @var integer
     *
     * @ORM\Column(name="CODE_LANGUE", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codeLangue;



    /**
     * @return string
     */
    public function getLangName()
    {
        return $this->langName;
    }

    /**
     * @param string $langName
     *
     * @return self
     */
    public function setLangName($langName)
    {
        $this->langName = $langName;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCodeLangue()
    {
        return $this->codeLangue;
    }

    /**
     * @param integer $codeLangue
     *
     * @return self
     */
    public function setCodeLangue($codeLangue)
    {
        $this->codeLangue = $codeLangue;

        return $this;
    }

    public function __toString()
    {
        try {
            return (string) $this->codeLangue;;
        } catch (Exception $exception) {
            return '';
        }
        
    }
}

