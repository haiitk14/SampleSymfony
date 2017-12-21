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
use AppBundle\Entity\CalculationParameters;
use AppBundle\Entity\Post;

class BrainCalculateController extends Controller 
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
     * @Route("/r-popup", name="r-popup")
     */
	public function rPopupAction(Request $request)
	{
		$idStudyEquipments = $request->get("idstudyequipments");
		$session = $this->get('session');
		$session->set("idstudyequipments", $idStudyEquipments);
	}

	/**
     * @Route("/braincalculate", name="nameBrainCalculate")
     */
	public function braincalculateAction()
	{

		$userCurrent = $this->getUser();

		if ($userCurrent == null) {
			return $this->redirectToRoute('login');
		}

		$idUser = $userCurrent->getIdUser();

		$session = $this->get('session');
		$idStudy = $session->get('idStudy');
		$idStudy = 1;
		$idStudyEquipments = $session->get("idstudyequipments");
		$idStudyEquipments = 2;

		$url = "calculates/common/error.html.twig";

		$mode = 3;
		if ($mode == 3) {
			$url = "calculates/estimation/brain.html.twig";
		}

		$sdisableFields = $this->cal->disableFields($idStudy);
		$sdisableCalculate = $this->cal->disableCalculate($idStudy);
		$sdisForSpecialEqpt = $this->brainCal->disCalculForSpecialEqpt();

		$sequip = $this->brainCal->getStudyEquipment($idStudy, $idStudyEquipments);

		$lTS = array();
		$lfDwellingTime = 0.0;

		if ($sequip != null) {
			$lTS = $this->equip->getTs($idStudyEquipments);

			for($i = 0; $i < count($lTS); $i++) {
				$lfDwellingTime += $lTS[$i];
			}
		}

		$ldStorageStepRatio = 1;
        $lfTimeStep = $this->convert->convertToDouble($this->brainCal->getTimeStep($idStudyEquipments));
        if ($lfTimeStep > 0) {
            $lfStorageStep = $this->convert->convertToDouble($this->brainCal->getStorageStep($idStudyEquipments));
            $ldStorageStepRatio = $lfStorageStep / $lfTimeStep;
        }

        $hradioOn = $this->brainCal->getHradioOn($idStudyEquipments);
        $hradioOff = $this->brainCal->getHradioOff($idStudyEquipments);
        $vradioOn = $this->brainCal->getVradioOn($idStudyEquipments);
        $vradioOff = $this->brainCal->getVradioOff($idStudyEquipments) ;

        $maxIter = $this->brainCal->getMaxIter($idStudyEquipments);
        $relaxCoef = $this->brainCal->getRelaxCoef($idStudyEquipments);
        $precision = $this->brainCal->getPrecision($idStudyEquipments);

        $topSurf = $this->brainCal->getTempPtSurf($idStudyEquipments);
        $topInt = $this->brainCal->getTempPtIn($idStudyEquipments);
        $bottomSurf = $this->brainCal->getTempPtBot($idStudyEquipments);
        $topAvg = $this->brainCal->getTempPtAvg($idStudyEquipments);

        $logStep = $this->brainCal->getPrecisionLogStep($idStudyEquipments);
        $storageStep = $this->brainCal->getStorageStep($idStudyEquipments);
        $timeStep = $this->brainCal->getTimeStep($idStudyEquipments);


		$select1 = $this->cal->getOption($idStudy, "X", "TOP");
        $select2 = $this->cal->getOption($idStudy, "Y", "TOP");
        $select3 = $this->cal->getOption($idStudy, "Z", "TOP");
        $select4 = $this->cal->getOption($idStudy, "X", "INT");
        $select5 = $this->cal->getOption($idStudy, "Y", "INT");
        $select6 = $this->cal->getOption($idStudy, "Z", "INT");
        $select7 = $this->cal->getOption($idStudy, "X", "BOT");
        $select8 = $this->cal->getOption($idStudy, "Y", "BOT");
        $select9 = $this->cal->getOption($idStudy, "Z", "BOT");

		$arr = [
			'sdisableFields' => $sdisableFields,
			'sdisableCalculate' => $sdisableCalculate,
			'sdisableCalculate' => $sdisableCalculate,
			'_hradioOn' => $hradioOn,
			'_hradioOff' => $hradioOff,
			'_vradioOn' => $vradioOn,
			'_vradioOff' => $vradioOff,
			'_relaxCoef' => $relaxCoef,
			'_precision' => $precision,
			'_topSurf' => $topSurf,
			'_topInt' => $topInt,
			'_bottomSurf' => $bottomSurf,
			'_topAvg' => $topAvg,
			'_maxIter' => $maxIter,
			'_logStep' => $logStep,
			'_storageStep' => $storageStep,
			'_timeStep' => $timeStep,
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

		return $this->render($url, $arr);
	}
}