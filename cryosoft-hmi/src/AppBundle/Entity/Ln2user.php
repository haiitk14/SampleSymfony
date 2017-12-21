<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"usernam"},
 *     errorPath="usernam",
 *     message="This usernam is already.")
 */
/**
 * Ln2user
 *
 * @ORM\Table(name="ln2user", indexes={@ORM\Index(name="FK_LN2USER_LANGUAGE", columns={"CODE_LANGUE"}), @ORM\Index(name="FK_LN2USER_MONETARY_CURRENCY", columns={"ID_MONETARY_CURRENCY"})})
 * @ORM\Entity
 */
class Ln2user implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_MONETARY_CURRENCY", type="integer", nullable=false)
     */
    private $idMonetaryCurrency;

    /**
     * @var integer
     *
     * @ORM\Column(name="CODE_LANGUE", type="integer", nullable=false)
     */
    private $codeLangue;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_CALC_PARAMSDEF", type="integer", nullable=true)
     */
    private $idCalcParamsdef;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_TEMP_RECORD_PTS_DEF", type="integer", nullable=true)
     */
    private $idTempRecordPtsDef;

    /**
     * @var string
     *
     * @Assert\NotBlank(
            message="The user login is mandatory."
     )
     *
     * @ORM\Column(name="USERNAM", type="string", length=32, nullable=true)
     */
    private $usernam;

    /**
     * @var string
     *@Assert\NotBlank(
            message="The password is not blank."
     )
     * @ORM\Column(name="USERPASS", type="string", length=255, nullable=true)
     */
    private $userpass;

    /**
     * @var integer
     *
     * @ORM\Column(name="USERPRIO", type="smallint", nullable=true)
     */
    private $userprio;

    /**
     * @var integer
     *
     * @ORM\Column(name="TRACE_LEVEL", type="integer", nullable=true)
     */
    private $traceLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="USER_ENERGY", type="integer", nullable=true)
     */
    private $userEnergy;

    /**
     * @var string
     *
     * @ORM\Column(name="USER_CONSTRUCTOR", type="string", length=32, nullable=true)
     */
    private $userConstructor;

    /**
     * @var integer
     *
     * @ORM\Column(name="USER_FAMILY", type="integer", nullable=true)
     */
    private $userFamily;

    /**
     * @var integer
     *
     * @ORM\Column(name="USER_ORIGINE", type="integer", nullable=true)
     */
    private $userOrigine;

    /**
     * @var integer
     *
     * @ORM\Column(name="USER_PROCESS", type="integer", nullable=true)
     */
    private $userProcess;

    /**
     * @var integer
     *
     * @ORM\Column(name="USER_MODEL", type="integer", nullable=true)
     */
    private $userModel;

    /**
     * @var string
     *
     * @ORM\Column(name="USERMAIL", type="string", length=255, nullable=true)
     */
    private $usermail;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_USER", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUser;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Unit", inversedBy="idUser")
     * @ORM\JoinTable(name="user_unit",
     *   joinColumns={
     *     @ORM\JoinColumn(name="ID_USER", referencedColumnName="ID_USER")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="ID_UNIT", referencedColumnName="ID_UNIT")
     *   }
     * )
     */
    private $idUnit;

     /**
     * @ORM\OneToMany(targetEntity="Studies", mappedBy="idUser")
     */
    private $idStudy;
     /**
     * @ORM\OneToMany(targetEntity="Equipment", mappedBy="idUser")
     */
    private $idEquip;
    /**
     * @ORM\OneToMany(targetEntity="Component", mappedBy="idUser")
     */
    private $idComp;
    /**
     * @ORM\OneToMany(targetEntity="LineElmt", mappedBy="idUser")
     */
    private $idPipelineElmt;
    /**
     * @ORM\OneToMany(targetEntity="PackingEmlt", mappedBy="idUser")
     */
    private $idPackingElmt;
    
    /**
     * Constructor
     */
    
    public function __construct()
    {
        $this->idUnit = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * @return integer
     */
    public function getIdMonetaryCurrency()
    {
        return $this->idMonetaryCurrency;
    }

    /**
     * @param integer $idMonetaryCurrency
     *
     * @return self
     */
    public function setIdMonetaryCurrency($idMonetaryCurrency)
    {
        $this->idMonetaryCurrency = $idMonetaryCurrency;

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

    /**
     * @return integer
     */
    public function getIdCalcParamsdef()
    {
        return $this->idCalcParamsdef;
    }

    /**
     * @param integer $idCalcParamsdef
     *
     * @return self
     */
    public function setIdCalcParamsdef($idCalcParamsdef)
    {
        $this->idCalcParamsdef = $idCalcParamsdef;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdTempRecordPtsDef()
    {
        return $this->idTempRecordPtsDef;
    }

    /**
     * @param integer $idTempRecordPtsDef
     *
     * @return self
     */
    public function setIdTempRecordPtsDef($idTempRecordPtsDef)
    {
        $this->idTempRecordPtsDef = $idTempRecordPtsDef;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsernam()
    {
        return $this->usernam;
    }

    /**
     * @param string $usernam
     *
     * @return self
     */
    public function setUsernam($usernam)
    {
        $this->usernam = $usernam;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserpass()
    {
        return $this->userpass;
    }

    /**
     * @param string $userpass
     *
     * @return self
     */
    public function setUserpass($userpass)
    {
        $this->userpass = $userpass;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUserprio()
    {
        return $this->userprio;
    }

    /**
     * @param integer $userprio
     *
     * @return self
     */
    public function setUserprio($userprio)
    {
        $this->userprio = $userprio;

        return $this;
    }

    /**
     * @return integer
     */
    public function getTraceLevel()
    {
        return $this->traceLevel;
    }

    /**
     * @param integer $traceLevel
     *
     * @return self
     */
    public function setTraceLevel($traceLevel)
    {
        $this->traceLevel = $traceLevel;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUserEnergy()
    {
        return $this->userEnergy;
    }

    /**
     * @param integer $userEnergy
     *
     * @return self
     */
    public function setUserEnergy($userEnergy)
    {
        $this->userEnergy = $userEnergy;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserConstructor()
    {
        return $this->userConstructor;
    }

    /**
     * @param string $userConstructor
     *
     * @return self
     */
    public function setUserConstructor($userConstructor)
    {
        $this->userConstructor = $userConstructor;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUserFamily()
    {
        return $this->userFamily;
    }

    /**
     * @param integer $userFamily
     *
     * @return self
     */
    public function setUserFamily($userFamily)
    {
        $this->userFamily = $userFamily;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUserOrigine()
    {
        return $this->userOrigine;
    }

    /**
     * @param integer $userOrigine
     *
     * @return self
     */
    public function setUserOrigine($userOrigine)
    {
        $this->userOrigine = $userOrigine;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUserProcess()
    {
        return $this->userProcess;
    }

    /**
     * @param integer $userProcess
     *
     * @return self
     */
    public function setUserProcess($userProcess)
    {
        $this->userProcess = $userProcess;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUserModel()
    {
        return $this->userModel;
    }

    /**
     * @param integer $userModel
     *
     * @return self
     */
    public function setUserModel($userModel)
    {
        $this->userModel = $userModel;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsermail()
    {
        return $this->usermail;
    }

    /**
     * @param string $usermail
     *
     * @return self
     */
    public function setUsermail($usermail)
    {
        $this->usermail = $usermail;

        return $this;
    }

    /**
     * @return integer
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param integer $idUser
     *
     * @return self
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdUnit()
    {
        return $this->idUnit;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $idUnit
     *
     * @return self
     */
    public function setIdUnit(\Doctrine\Common\Collections\Collection $idUnit)
    {
        $this->idUnit = $idUnit;

        return $this;
    }
     /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
       return ['USER_ROLE'];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->userpass;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
       return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->usernam;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
       return null;
    }

    /**
     * @return mixed
     */
    public function getIdStudy()
    {
        return $this->idStudy;
    }

    /**
     * @param mixed $idStudy
     *
     * @return self
     */
    public function setIdStudy($idStudy)
    {
        $this->idStudy = $idStudy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdEquip()
    {
        return $this->idEquip;
    }

    /**
     * @param mixed $idEquip
     *
     * @return self
     */
    public function setIdEquip($idEquip)
    {
        $this->idEquip = $idEquip;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdComp()
    {
        return $this->idComp;
    }

    /**
     * @param mixed $idComp
     *
     * @return self
     */
    public function setIdComp($idComp)
    {
        $this->idComp = $idComp;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdPipelineElmt()
    {
        return $this->idPipelineElmt;
    }

    /**
     * @param mixed $idPipelineElmt
     *
     * @return self
     */
    public function setIdPipelineElmt($idPipelineElmt)
    {
        $this->idPipelineElmt = $idPipelineElmt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdPackingElmt()
    {
        return $this->idPackingElmt;
    }

    /**
     * @param mixed $idPackingElmt
     *
     * @return self
     */
    public function setIdPackingElmt($idPackingElmt)
    {
        $this->idPackingElmt = $idPackingElmt;

        return $this;
    }
}

