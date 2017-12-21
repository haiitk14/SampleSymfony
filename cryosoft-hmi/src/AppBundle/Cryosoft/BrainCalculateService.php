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
use AppBundle\Entity\CalculationParameters;
use AppBundle\Cryosoft\UnitsConverterService;
use AppBundle\Entity\Post;
use AppBundle\Entity\StudyEquipments;
use AppBundle\Entity\MinMax;


class BrainCalculateService
{

	private $doctrine;
	private $user;
	private $studyEquipment;
	private $convert;

	public function __construct(\Doctrine\ORM\EntityManager $doctrine, TokenStorageInterface $tokenStorage, UnitsConverterService $convert) 
	{
		$this->doctrine = $doctrine;
		$this->user = $tokenStorage->getToken()->getUser();
		$this->convert = $convert;
	}

	private function getDoctrine() 
	{
		return $this->doctrine;
	}

	public function getStudyEquipment($idStudy, $idStudyEquipments) 
	{
		$this->studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(
			['idStudy' => $idStudy, 'idStudyEquipments' => $idStudyEquipments]);

		return $this->studyEquipment;
	}

	public function setStudyEquipment($studyEquipment) 
	{
		$this->studyEquipment = $studyEquipment;

		if ($studyEquipment != null) {
			//some code here 
		}
	}

    public function getHradioOn($idStudyEquipments) 
    {
        $etat = "";
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        if (!empty($calcParameter)) {
            if ($calcParameter->isHorizScan()) {
                $etat = "checked";
            }   
        }
        
        return $etat;
    }

    public function getHradioOff($idStudyEquipments) 
    {
        $etat = "";
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        if (!empty($calcParameter)) {
            if (!$calcParameter->isHorizScan()) {
                $etat = "checked";
            }
        }
        return $etat;
    }

    public function getVradioOn($idStudyEquipments) 
    {
        $etat = "";
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        if (!empty($calcParameter)) {
            if ($calcParameter->isVertScan()) {
                $etat = "checked";
            }
        }
        return $etat;
    }

    public function getVradioOff($idStudyEquipments) 
    {
        $etat = "";
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        if (!empty($calcParameter)) {
            if (!$calcParameter->isVertScan()) {
                $etat = "checked";
            }
        }
        return $etat;
    }

    public function getMaxIter($idStudyEquipments) 
    {
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        $maxItNb = 0;
        if (!empty($calcParameter)) {
            $maxItNb = $calcParameter->getMaxItNb();
        }
        return $maxItNb;
    }

    public function getRelaxCoef($idStudyEquipments) 
    {
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        $relaxCoeff = 0;
        if (!empty($calcParameter)) {
            $relaxCoeff =  $calcParameter->getRelaxCoeff();
        }

        return $relaxCoeff;
    }

    public function getPrecision($idStudyEquipments) 
    {
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        $precision = 0;

        if (!empty($calcParameter)) {
            $precision = $calcParameter->getPrecisionRequest();
        }

        return $this->convert->unitConvert(Post::TYPE_UNIT_TIME, $precision, 3);
    }

    public function getTempPtSurf($idStudyEquipments) 
    {
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        $topSurf = 0;

        if (!empty($calcParameter)) {
            $topSurf = $calcParameter->getStopTopSurf();
        }

        return $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $topSurf, 2);
    }

    public function getTempPtIn($idStudyEquipments) 
    {
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        $topInt = 0;

        if (!empty($calcParameter)) {
            $topInt = $calcParameter->getStopInt();
        }

        return $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $topInt, 2);
    }

    public function getTempPtBot($idStudyEquipments) 
    {
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        $bottomSurf = 0;

        if (!empty($calcParameter)) {
            $bottomSurf = $calcParameter->getStopBottomSurf();
        }

        return $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $bottomSurf, 2);
    }

    public function getTempPtAvg($idStudyEquipments) 
    {
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        $topAvg = 0;

        if (!empty($calcParameter)) {
            $topAvg = $calcParameter->getStopAvg();
        }

        return $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $topAvg, 2);
    }

    public function getPrecisionLogStep($idStudyEquipments) 
    {
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        $logStep = 0;

        if (!empty($calcParameter)) {
            $logStep = $calcParameter->getPrecisionLogStep();
        }

        return $logStep;
    }

    public function getStorageStep($idStudyEquipments) 
    {
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        $lfStep = 0.0;

        if (!empty($calcParameter)) {
            $lfStep = $calcParameter->getStorageStep() * $calcParameter->getTimeStep();
        }

        return $this->convert->unitConvert(Post::TYPE_UNIT_TIME, $lfStep, 1);
    }

    public function getTimeStep($idStudyEquipments) 
    {
        $calcParameter = $this->getCalcParams($idStudyEquipments);
        $lfStep = 0.0;

        if (!empty($calcParameter)) {
            $lfStep = $calcParameter->getTimeStep();
        }

        return $this->convert->unitConvert(Post::TYPE_UNIT_TIME, $lfStep, 1);
    }

	public function getCalcParams($idStudyEquipments)
	{
		$calcParameter = $this->getDoctrine()->getRepository(CalculationParameters::class)->findOneBy(['idStudyEquipments' => $idStudyEquipments]);

		return $calcParameter;
	}

	public function getMinMax($limitItem)
    {
        return $this->getDoctrine()->getRepository(MinMax::class)->findOneBy(["limitItem" => $limitItem]);
    }

    public function getOptimErrorT($calculationMode, $idStudyEquipments)
    {
        $sOptimErrorT = "";
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);
        $brandType = $studyEquipment->getBrainType();
        $idCalcParams = $studyEquipment->getIdCalcParams();
        $calcParameters = $this->getDoctrine()->getRepository(CalculationParameters::class)->findOneBy(["idStudyEquipments" => $studyEquipment->getIdStudyEquipments()]);

        switch ($calculationMode) {
            case 1:
            case 2:
            case 10:
            case 14:
                $minMax = $this->getMinMax(1132);
                $sOptimErrorT =  $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $minMax->getDefaultValue());
                break;

            case 11:
            case 15:
                if ($brandType == 2) {
                    $sOptimErrorT =  $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $calcParameters->getErrorT());
                } else {
                    $minMax = $this->getMinMax(1134);
                    $sOptimErrorT =  $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $minMax->getDefaultValue());
                }
                break;

            case 12:
            case 16:
                if ($brandType == 4 || $brandType == 3) {
                    $sOptimErrorT =  $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $calcParameters->getErrorT());
                } else {
                    $minMax = $this->getMinMax(1136);
                    $sOptimErrorT =  $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $minMax->getDefaultValue());
                }
                break;

            case 13:
            case 17:
                if ($brandType != 0 || $brandType != 1) {
                    $sOptimErrorT =  $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $calcParameters->getErrorT());
                } else {
                    $minMax = $this->getMinMax(1138);
                    $sOptimErrorT =  $this->convert->unitConvert(Post::TYPE_UNIT_TEMPERATURE, $minMax->getDefaultValue());
                }
                break;
        }

        return $sOptimErrorT;
    }

    public function getOptimErrorH($calculationMode, $idStudyEquipments)
    {
        $sOptimErrorH = "";
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);
        $brandType = $studyEquipment->getBrainType();
        $idCalcParams = $studyEquipment->getIdCalcParams();
        $calcParameters = $this->getDoctrine()->getRepository(CalculationParameters::class)->findOneBy(["idStudyEquipments" => $studyEquipment->getIdStudyEquipments()]);
        $uPercent = $this->convert->uPercent();

        switch ($calculationMode) {
            case 1:
            case 2:
            case 10:
            case 14:
                $minMax = $this->getMinMax(1131);
                $sOptimErrorH =  $this->convert->convertCalculator($minMax->getDefaultValue(), $uPercent["coeffA"], $uPercent["coeffB"]);
                break;

            case 11:
            case 15:
                if ($brandType == 2) {
                    $sOptimErrorH =  $this->convert->convertCalculator($calcParameters->getErrorH(), $uPercent["coeffA"], $uPercent["coeffB"]);
                } else {
                    $minMax = $this->getMinMax(1133);
                    $sOptimErrorH =  $this->convert->convertCalculator($minMax->getDefaultValue(), $uPercent["coeffA"], $uPercent["coeffB"]);
                }
                break;

            case 12:
            case 16:
                if ($brandType == 4 || $brandType == 3) {
                    $sOptimErrorH =  $this->convert->convertCalculator($calcParameters->getErrorH(), $uPercent["coeffA"], $uPercent["coeffB"]);
                } else {
                    $minMax = $this->getMinMax(1135);
                    $sOptimErrorH =  $this->convert->convertCalculator($minMax->getDefaultValue(), $uPercent["coeffA"], $uPercent["coeffB"]);
                }
                break;

            case 13:
            case 17:
                if ($brandType != 0 || $brandType != 1) {
                    $sOptimErrorH =  $this->convert->convertCalculator($calcParameters->getErrorH(), $uPercent["coeffA"], $uPercent["coeffB"]);
                } else {
                    $minMax = $this->getMinMax(1137);
                    $sOptimErrorH =  $this->convert->convertCalculator($minMax->getDefaultValue(), $uPercent["coeffA"], $uPercent["coeffB"]);
                }
                break;
        }

        return $sOptimErrorH;
    }

    public function getNbOptim($calculationMode, $idStudyEquipments)
    {
        $sNbOptim = "";
        $studyEquipment = $this->getDoctrine()->getRepository(StudyEquipments::class)->findOneBy(["idStudyEquipments" => $idStudyEquipments]);
        $brandType = $studyEquipment->getBrainType();
        $idCalcParams = $studyEquipment->getIdCalcParams();
        $calcParameters = $this->getDoctrine()->getRepository(CalculationParameters::class)->findOneBy(["idStudyEquipments" => $studyEquipment->getIdStudyEquipments()]);
        $uNone = $this->convert->uNone();
        $minMax = $this->getMinMax(1130);

        switch ($calculationMode) {
            case 1:
            case 2:
            case 10:
            case 14:
                $sNbOptim =  $this->convert->convertCalculator($minMax->getDefaultValue(), $uNone["coeffA"], $uNone["coeffB"]);
                break;

            case 11:
            case 15:
                if ($brandType == 2 && $calcParameters->getNbOptim() > 0) {
                    $sNbOptim =  $this->convert->convertCalculator($calcParameters->getNbOptim(), $uNone["coeffA"], $uNone["coeffB"]);
                } else {
                    $sNbOptim =  $this->convert->convertCalculator($minMax->getDefaultValue(), $uNone["coeffA"], $uNone["coeffB"]);
                }
                break;

            case 12:
            case 16:
                if (($brandType == 4 || $brandType == 3) && $calcParameters->getNbOptim() > 0) {
                    $sNbOptim =  $this->convert->convertCalculator($calcParameters->getNbOptim(), $uNone["coeffA"], $uNone["coeffB"]);
                } else {
                    $sNbOptim =  $this->convert->convertCalculator($minMax->getDefaultValue(), $uNone["coeffA"], $uNone["coeffB"]);
                }
                break;

            case 13:
            case 17:
                $sNbOptim =  $this->convert->convertCalculator($minMax->getDefaultValue(), $uNone["coeffA"], $uNone["coeffB"]);
                break;
        }

        return $sNbOptim;
    }

    public function disCalculForSpecialEqpt() 
    {
        $disabled = "";
        return $disabled;
    }
}

