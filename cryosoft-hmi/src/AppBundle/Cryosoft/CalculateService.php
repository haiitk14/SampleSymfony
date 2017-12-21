<?php
/****************************************************************************
**
** Copyright (C) 2017 Oriental Tran.
** Contact: dongtp@dfm-engineering.com
** Company: DFM-Engineering Vietnam
**
** This file is part of the cryosoft project.
**
**All rights reserved.
****************************************************************************/
namespace  AppBundle\Cryosoft;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\StudyEquipments;
use AppBundle\Entity\Studies;
use AppBundle\Entity\Ln2user;
use AppBundle\Entity\Post;
use AppBundle\Cryosoft\EquipmentsService;
use AppBundle\Entity\MinMax;
use AppBundle\Cryosoft\UnitsConverterService;
use AppBundle\Entity\CalculationParameters;
use AppBundle\Entity\CalculationParametersDef;
use AppBundle\Entity\MeshPosition;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductElmt;
use AppBundle\Entity\TempRecordPts;


class CalculateService
{	
	
	private $doctrine;
	private $user;
	private $cEquip;
	private $convert;
	private $calParametersDef;

	public function __construct(\Doctrine\ORM\EntityManager $doctrine, TokenStorageInterface $tokenStorage, EquipmentsService $cEquip, UnitsConverterService $convert) 
	{
		$this->doctrine = $doctrine;
		$this->user = $tokenStorage->getToken()->getUser();
		$this->cEquip = $cEquip;
		$this->convert = $convert;
		$this->calParametersDef = $this->getCalculationParametersDef($this->user);
	}

	private function getDoctrine() 
	{
		return $this->doctrine;
	}

	public function disableFields($idStudy)
	{
        $disabledField = "";

        $study = $this->getDoctrine()->getRepository(Studies::class)->findOneBy(['idStudy' => $idStudy]);

        if ($study != null) {
            $studyOwnerUserID = $study->getIdUser()->getIdUser();
            $userProfileID = $this->user->getUserprio();
            $userID = $this->user->getIdUser();

            if (($userProfileID > POST::PROFIL_EXPERT) || ($studyOwnerUserID != $userID)) {
    			$disabledField = "disabled";
    		}
        }

		return $disabledField;
    }

    public function disableCalculate($idStudy) 
    {
        $disabledField = "";

        $study = $this->getDoctrine()->getRepository(Studies::class)->findOneBy(['idStudy' => $idStudy]);

        if ($study != null) {
            $studyOwnerUserID = $study->getIdUser()->getIdUser();
            $userProfileID = $this->user->getUserprio();
            $userID = $this->user->getIdUser();

            if ($studyOwnerUserID != $userID) {
    			$disabledField = "disabled";
    		}
        }

		return $disabledField;
    }

    public function getTimeStep($idStudy) 
    {
    	$timeStep = -1.0;
        $bOneTimeStep = true;

        $studyEquipments  = $this->getCalculableStudyEquipments($idStudy);

        foreach ($studyEquipments as $sEquipment) {
			$calParamester = $this->getDoctrine()->getRepository(CalculationParameters::class)->findOneBy(
				['idCalcParams' => $sEquipment->getIdCalcParams()]);

			if ($timeStep != $calParamester->getTimeStep()) {
				if ($timeStep == -1.0) {
	                $timeStep = $calParamester->getTimeStep();
	            } else {
	                $bOneTimeStep = false;
	            }
			}			
        }


    	if ($bOneTimeStep) {
            return $this->convert->unitConvert(Post::TYPE_UNIT_TIME, $timeStep, 3);
        }

        return "N.A.";
    }

    public function getPrecision($idStudy)
    {
    	$precision = -1.0;
        $bOnePrecision = true;

    	$studyEquipments  = $this->getCalculableStudyEquipments($idStudy);

		foreach ($studyEquipments as $sEquipment) {
			$calParamester = $this->getDoctrine()->getRepository(CalculationParameters::class)->findOneBy(
				['idCalcParams' => $sEquipment->getIdCalcParams()]);

			if ($precision != $calParamester->getPrecisionRequest()) {
				if ($precision == -1.0) {
                    $precision = $calParamester->getPrecisionRequest();
                } else {
                    $bOnePrecision = false;
                }
			}
		}

    	if ($bOnePrecision) {
            return $this->convert->unitConvert(Post::TYPE_UNIT_TIME, $precision, 3);
        }

        return "N.A.";
    }

    public function getStorageStep($idUser)
    {
    	$lfStep = 0.0;
    	if ($this->calParametersDef != null) {
    		$lfStep = $this->calParametersDef->getStorageStepDef() * $this->calParametersDef->getTimeStepDef();
    	}

    	return $this->convert->unitConvert(Post::TYPE_UNIT_TIME, $lfStep, 1);
    }

    public function getHradioOn() 
    {
        $etat = "";
        if ($this->calParametersDef->isHorizScanDef()) {
            $etat = "checked";
        }
        return $etat;
    }

    public function getHradioOff() 
    {
        $etat = "";
        if (!$this->calParametersDef->isHorizScanDef()) {
            $etat = "checked";
        }
        return $etat;
    }

    public function getMaxIter() 
    {
        return $this->convert->convertCalculator($this->calParametersDef->getMaxItNbDef(), 1.0, 0.0, 0);
    }

    public function getRelaxCoef() 
    {
        return $this->convert->convertCalculator($this->calParametersDef->getRelaxCoeffDef(), 1.0, 0.0);
    }

    public function getTempPtSurf() 
    {
        return $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $this->calParametersDef->getStopTopSurfDef(), 2);
    }

    public function getTempPtIn() 
    {
        return $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $this->calParametersDef->getStopIntDef(), 2);
    }

    public function getTempPtBot() 
    {
        return $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $this->calParametersDef->getStopBottomSurfDef(), 2);
    }

    public function getTempPtAvg() 
    {
        return $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $this->calParametersDef->getStopAvgDef(), 2);
    }

    public function getVradioOn() 
    {
        $etat = "";
        if ($this->calParametersDef->isVertScanDef()) {
            $etat = "checked";
        }
        return $etat;
    }

    public function getVradioOff()
    {
        $etat = "";
        if (!$this->calParametersDef->isVertScanDef()) {
            $etat = "checked";
        }
        return $etat;
    }

    public function getCalculationParametersDef($idUser)
    {
    	$calParametersDef = $this->getDoctrine()->getRepository(CalculationParametersDef::class)->findOneBy(['idUser' => $idUser]);

    	return $calParametersDef;
    }

    public function getOption($idStudy, $key, $axe)
    {
        switch ($key) {
            case "X":
                $meshAxis = 1;
                break;

            case "Y":
                $meshAxis = 2;
                break;

            case "Z":
                $meshAxis = 3;
                break;
        }
    
        $lint = $this->getDoctrine()->getRepository(MeshPosition::class)->createQueryBuilder('m')
            ->select('m.meshAxisPos')
            ->join(ProductElmt::class, 'pE', 'WITH', 'pE.idProductElmt = m.idProductElmt')
            ->join(Product::class, 'p', 'WITH', 'p.idProd = pE.idProd')
            ->where('p.idStudy = :idStudy')
            ->andWhere('m.meshAxis = :meshAxis')
            ->setParameter('idStudy', $idStudy)
            ->setParameter('meshAxis', $meshAxis)
            ->distinct(true)
            ->orderBy("m.meshAxisPos")
            ->getQuery()->getResult();
        
        $arrLint = array();

        if (!empty($lint)) {
            foreach ($lint as $row) {
                $item["selected"] = ($this->getCoordinate($idStudy, $key, $axe) == $row["meshAxisPos"]) ? true : false;
                $item["value"] = $this->convert->unitConvert(Post::TYPE_UNIT_MESH_CUT, $row["meshAxisPos"]);
                $item["lable"] = $row["meshAxisPos"];
                array_push($arrLint, $item);
            }
        }
        
        return $arrLint;
    }

    public function getCoordinate($idStudy, $key, $axe)
    {
        $val = 0.0;
        $tempRecordsPts = $this->getDoctrine()->getRepository(TempRecordPts::class)->findOneBy(['idStudy' => $idStudy]);
        
        if ($key == "X" && $axe == "INT") {
            $val = ($tempRecordsPts != null) ? $tempRecordsPts->getAxis1PtIntPt() : 0.0;
        } else if ($key == "Y" && $axe == "INT") {
            $val = ($tempRecordsPts != null) ? $tempRecordsPts->getAxis2PtIntPt() : 0.0;
        } else if ($key == "Z" && $axe == "INT") {
            $val = ($tempRecordsPts != null) ? $tempRecordsPts->getAxis3PtIntPt() : 0.0;
        } else if ($key == "X" && $axe == "BOT") {
            $val = ($tempRecordsPts != null) ? $tempRecordsPts->getAxis1PtBotSurf() : 0.0;
        } else if ($key == "Y" && $axe == "BOT") {
            $val = ($tempRecordsPts != null) ? $tempRecordsPts->getAxis2PtBotSurf() : 0.0;
        } else if ($key == "Z" && $axe == "BOT") {
            $val = ($tempRecordsPts != null) ? $tempRecordsPts->getAxis3PtBotSurf() : 0.0;
        } else if ($key == "X" && $axe == "TOP") {
            $val = ($tempRecordsPts != null) ? $tempRecordsPts->getAxis1PtTopSurf() : 0.0;
        } else if ($key == "Y" && $axe == "TOP") {
            $val = ($tempRecordsPts != null) ? $tempRecordsPts->getAxis2PtTopSurf() : 0.0;
        } else if ($key == "Z" && $axe == "TOP") {
            $val = ($tempRecordsPts != null) ? $tempRecordsPts->getAxis3PtTopSurf() : 0.0;
        }

        return $this->convert->unitConvert(Post::TYPE_UNIT_MESH_CUT, $val);
    }

    public function isThereAnEquipWithOptimEnable($idStudy) 
    {
    	$bret = false;
    	$studyEquipments  = $this->getCalculableStudyEquipments($idStudy);

    	if (!empty($studyEquipments)) {
    		foreach ($studyEquipments as $sEquipment) {
    			$equipWithSpecificSize = ($sEquipment->getStdeqpWidth() != -1.0) && ($sEquipment->getStdEqpLength() != -1.0);
    			$idEquip = $sEquipment->getIdEquip()->getIdEquip();

    			$bspecialEquip = ($this->cEquip->getCapability(262144, $idEquip)) && ($this->cEquip->getCapability(2097152, $idEquip)) && (!$equipWithSpecificSize);

    			if (($this->cEquip->getCapability(64, $idEquip)) && (!$bspecialEquip)) {
                    $bret = true;
                    break;
                }
    		}
    	}

    	return $bret;
    }

    public function getCalculableStudyEquipments($idStudy) 
    {
    	$studyEquipments = $this->getDoctrine()->getRepository(StudyEquipments::class)->findBy(
    		['idStudy' => $idStudy, 'runCalculate' => 1, 'brainType' => 0]);

   		return $studyEquipments;
    }

    public function getOptimErrorT()
    {
    	$mmErrorT = 0.0;
    	$minMax = $this->getMinMax(1132);
        $mmErrorT =  $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $minMax->getDefaultValue());
    	return $mmErrorT;
    }

    public function getOptimErrorH()
    {
    	$mmErrorH = 0.0;
    	$minMax = $this->getMinMax(1131);
        $mmErrorH =  $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $minMax->getDefaultValue());
    	return $mmErrorH;
    }

    public function getNbOptim()
    {
    	$mmNbOptim = 0.0;
    	$minMax = $this->getMinMax(1130);
        $mmNbOptim =  $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $minMax->getDefaultValue());
    	return $mmNbOptim;
    }

    public function getMinMax($limitItem)
    {
        return $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(['limitItem' => $limitItem]);
    }
}

