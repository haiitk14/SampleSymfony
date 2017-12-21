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
namespace AppBundle\Controller\Calculates;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Cryosoft\CheckControlService;
use AppBundle\Cryosoft\StudyService;
use AppBundle\Cryosoft\CalculateService;
use AppBundle\Cryosoft\BrainCalculateService;
use AppBundle\Cryosoft\UnitsConverterService;
use AppBundle\Cryosoft\EquipmentsService;
use AppBundle\Entity\Post;

class CalculateController extends Controller 
{

	private $check;
	private $study;
	private $cal;
	private $brainCal;
	private $equip;
	private $convert;

	public function __construct(CheckControlService $check, CalculateService $cal, StudyService $study, BrainCalculateService $brainCal, EquipmentsService $equip, UnitsConverterService $convert)
	{
		$this->check = $check;
		$this->study = $study;
		$this->cal = $cal;
		$this->brainCal = $brainCal;
		$this->equip = $equip;
		$this->convert = $convert;
	}

	/**
     * @Route("/calculate", name="nameCalculate")
     */
	public function calculateAction()
	{	

		$userCurrent = $this->getUser();

		if ($userCurrent == null) {
			return $this->redirectToRoute('login');
		}

		$idUser = $userCurrent->getIdUser();

		$session = $this->get('session');
		$idStudy = $session->get('idStudy');
		$idStudy = 84;
		$idStudyEquipments = $session->get('idStudyEquipments');
		$calMode = $this->study->getCalculationMode($idStudy, $idUser);

		$mode = 3;
		$url = "calculates/common/error.html.twig";
		if ($mode == 1){
			$url = "calculates/estimation/estimation.html.twig";
		} else if ($mode == 2) {
			$studyEquipemts = $this->cal->getCalculableStudyEquipments($idStudy);

			if ($studyEquipemts != null) {
				$idStudyEquipments = $studyEquipemts[0]->getIdStudyEquipments();
			}

			$url = "calculates/optimum/optimum.html.twig";
		} else if ($mode == 3) {
			//$url = "calculates/common/waiting.html.twig";
			$url = "calculates/optimum/optimum.html.twig";
		} else {
			$url = "calculates/common/error.html.twig";
		}

		$sdisableFields = $this->cal->disableFields($idStudy);
		$sdisableCalculate = $this->cal->disableCalculate($idStudy);

		$sdisableOptim = $sdisableNbOptim = $sdisableStorage = "";
		$sclassNbOptim = $sclassStorage = "";
		$sdisableTimeStep = $sdisablePrecision = "";
		$scheckOptim = $scheckStorage = "";

		if ($sdisableFields == "") {
			$sdisableOptim = $sdisableFields;

			if ($calMode == Post::STUDY_OPTIMUM_MODE) {
				$sdisableNbOptim = $sdisableStorage = "disabled";
				$sclassNbOptim = $sclassStorage = "sous-titredisabled";
				$scheckOptim = "checked";
				$scheckStorage = "";

				if ($this->cal->getTimeStep($idStudy) == Post::VALUE_N_A) {
					$sdisableTimeStep = "disabled";
				} else {
					$sdisableTimeStep = "";
				}

				if ($this->cal->getPrecision($idStudy) == Post::VALUE_N_A) {
					$sdisablePrecision = "disabled";
				} else {
					$sdisablePrecision = "";
				}
			} else if ($calMode == Post::STUDY_SELECTED_MODE) {
				$sdisableNbOptim = $sdisableStorage = "";
				$sclassNbOptim = $sclassStorage = "sous-titre";
				$scheckOptim = "checked";
				$scheckStorage = "";
				$sdisableTimeStep = $sdisablePrecision = "";
			} else {
				$sdisableNbOptim = $sdisableStorage = "disabled";
				$sclassNbOptim = $sclassStorage = "sous-titredisabled";
				$scheckOptim = $scheckStorage = "";
				$sdisableTimeStep = $sdisablePrecision = "";
			}

			if (!$this->cal->isThereAnEquipWithOptimEnable($idStudy)) {
				$sdisableOptim = $sdisableNbOptim = "disabled";
				$scheckOptim = "";
			}
		} else {
			$sdisableOptim = $sdisableNbOptim = $sdisableStorage = "disabled";
			$sdisableTimeStep = $sdisablePrecision = "disabled";
			$sclassNbOptim = $sclassStorage = "sous-titredisabled";
			$scheckOptim = $scheckStorage = "";
		}

		$sequip = $this->brainCal->getStudyEquipment($idStudy, $idStudyEquipments);

		$lTR = array();
		$lTS = array();
		$lfToc 	= 0;
		$nbMaxEqpPrm = 0;
		$lfDwellingTime = 0.0;

		if (($calMode == Post::STUDY_SELECTED_MODE) && ($sequip != null)) {
			$lfToc = $this->equip->getLoadingRate($sequip->getIdLayoutResults());
			$lTR = $this->equip->getTr($idStudyEquipments);
			$lTS = $this->equip->getTs($idStudyEquipments);
			$nbMaxEqpPrm = (count($lTR) > count($lTS)) ? $lTR : $lTS;

			for($i = 0; $i < count($lTS); $i++) {
				$lfDwellingTime += $lTS[$i];
			}
		}

		$ldStorageStepRatio = 1;

		if ($calMode == Post::STUDY_SELECTED_MODE) {
			$lfTimeStep = $this->brainCal->getTimeStep($idStudyEquipments);

			if($lfTimeStep > 0) {
				$lfStorageStep = $this->brainCal->getStorageStep($idStudyEquipments);
				$ldStorageStepRatio = ($lfStorageStep / $lfTimeStep);
			}
		}

		$sTitle = false;
		if ($calMode == Post::STUDY_OPTIMUM_MODE) {
			$sTitle = true;
		} 

		$epsilonTemp = $this->cal->getOptimErrorT();
		$epsilonEnth = $this->cal->getOptimErrorH();
		$nbOptimIter = $this->cal->getNbOptim();

		$timeStep = $this->cal->getTimeStep($idStudy);
		$precision = $this->cal->getPrecision($idStudy);
		$storagestep = $this->cal->getStorageStep($idUser);

		$hRadioOn = $this->cal->getHradioOn();
		$hRadioOff = $this->cal->getHradioOff();
		$maxIter = $this->cal->getMaxIter();
		$relaxCoef = $this->cal->getRelaxCoef();

		$vRadioOn = $this->cal->getVradioOn();
		$vRadioOff = $this->cal->getVradioOff();
		$tempPtSurf = $this->cal->getTempPtSurf();
		$tempPtIn = $this->cal->getTempPtIn();
		$tempPtBot = $this->cal->getTempPtBot();
		$tempPtAvg = $this->cal->getTempPtAvg();

		$select1 = $this->cal->getOption($idStudy, "X", "TOP");
        $select2 = $this->cal->getOption($idStudy, "Y", "TOP");
        $select3 = $this->cal->getOption($idStudy, "Z", "TOP");
        $select4 = $this->cal->getOption($idStudy, "X", "INT");
        $select5 = $this->cal->getOption($idStudy, "Y", "INT");
        $select6 = $this->cal->getOption($idStudy, "Z", "INT");
        $select7 = $this->cal->getOption($idStudy, "X", "BOT");
        $select8 = $this->cal->getOption($idStudy, "Y", "BOT");
        $select9 = $this->cal->getOption($idStudy, "Z", "BOT");

		$array = [
			'sdisableFields' => $sdisableFields,
			'sdisableCalculate' => $sdisableCalculate,
			'scheckOptim' => $scheckOptim,
			'sdisableOptim' => $sdisableOptim,
			'sdisableNbOptim' => $sdisableNbOptim,
			'_epsilonTemp' => $epsilonTemp,
			'_epsilonEnth' => $epsilonEnth,
			'_nbOptimIter' => $nbOptimIter,
			'sdisableTimeStep' => $sdisableTimeStep,
			'sdisablePrecision' => $sdisablePrecision,
			'sdisableStorage' => $sdisableStorage,
			'_timeStep' => $timeStep,
			'_precision' => $precision,
			'scheckStorage' => $scheckStorage,
			'_storagestep' =>  $storagestep,
			'_hRadioOn' => $hRadioOn,
			'_hRadioOff' => $hRadioOff,
			'_maxIter' => $maxIter,
			'_relaxCoef' => $relaxCoef,
			'_vRadioOn' => $vRadioOn,
			'_vRadioOff' => $vRadioOff,
			'_tempPtSurf' => $tempPtSurf,
			'_tempPtIn' => $tempPtIn,
			'_tempPtBot' => $tempPtBot,
			'_tempPtAvg' => $tempPtAvg,
			'_select1' => $select1,
			'_select2' => $select2,
			'_select3' => $select3,
			'_select4' => $select4,
			'_select5' => $select5,
			'_select6' => $select6,
			'_select7' => $select7,
			'_select8' => $select8,
			'_select9' => $select9,
		];

		return $this->render($url, $array);
	}

	/**
     * @Route("/checkcalculate", name="checkCalculate")
     */
	public function checkAction() 
	{
		$userCurrent = $this->getUser();

		if ($userCurrent == null) {
			return $this->redirectToRoute('login');
		}

		$idUser = $userCurrent->getIdUser();

		$session = $this->get('session');
		$idStudy = $session->get('idStudy');
		$idProd = $session->get('idProd');
		$loadEquipment = $session->get('loadEquipment');

		$isCheckControl = $this->check->isCheckControl($idUser, $idStudy, $loadEquipment, $idProd);

		return die(json_encode($isCheckControl));
	}

	/**
     * @Route("/startCalculate", name="startCalculate")
     */
	public function startCalculateAction() 
	{
		$url = "calculates/common/error.html.twig";

		$userCurrent = $this->getUser();

		if ($userCurrent == null) {
			return $this->redirectToRoute('login');
		}

		$idUser = $userCurrent->getIdUser();

		$session = $this->get('session');
		$idStudy = $session->get('idStudy');

		$calMode = $this->study->getCalculationMode($idStudy, $idUser);

		if ($calMode == 1) {

		} else if ($calMode == 2) {

		} else if ($calMode == 3) {

		} else {

		}

		return $this->render($url);
	}
}  