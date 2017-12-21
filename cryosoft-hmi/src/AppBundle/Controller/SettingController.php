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
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Ln2user;
use AppBundle\Entity\MeshParamDef;
use AppBundle\Entity\CalculationParametersDef;
use AppBundle\Entity\TempRecordPtsDef;
use Symfony\Component\HttpFoundation\Session\Session;

class SettingController extends Controller 
{
	/**
     * @Route("/settings/calculation", name="calculationSettings")
     */
	public function calculationAction()
	{
		$userCurrent = $this->getUser();

		if ($userCurrent == null) {
			return $this->redirectToRoute('login');
		}

		$idUser = $userCurrent->getIdUser();

		$horizScanDef = false;
		$isHorizScanDef = "";
		$isHorizScanDef1 = "";

		$vertScanDef = false;
		$isVertScanDef = "";
		$isVertScanDef1 = "";

		$maxIter = 0.1;
		$relaxCoef = 0.1;
		$precision = 0.1;

		$stopTopSurfDef = 0.1;
		$stopIntDef = 0.1;
		$stopBottomSurfDef = 0.1;
		$stopAvgDef = 0.1;

		$isStudyAlphaTopFixedDef = false;
		$studyAlphaTopFixedDef = "";
		$disabledAlpha0 = "disabled";

		$isStudyAlphaBottomFixedDef = false;
		$studyAlphaBottomFixedDef = "";
		$disabledAlpha1 = "disabled";

		$isStudyAlphaLeftFixedDef = false;
		$studyAlphaLeftFixedDef = "";
		$disabledAlpha2 = "disabled";

		$isStudyAlphaRightFixedDef = false;
		$studyAlphaRightFixedDef = "";
		$disabledAlpha3 = "disabled";

		$isStudyAlphaFrontFixedDef = false;
		$studyAlphaFrontFixedDef = "";
		$disabledAlpha4 = "disabled";

		$isStudyAlphaRearFixedDef = false;
		$studyAlphaRearFixedDef = "";
		$disabledAlpha5 = "disabled";

		$studyAlphaTopDef = 0.0;
		$studyAlphaBottomDef = 0.0;
		$studyAlphaLeftDef = 0.0;
		$studyAlphaRightDef = 0.0;
		$studyAlphaFrontDef = 0.0;
		$studyAlphaRearDef = 0.0;

		$storageStepDef = 0.0;
		$precisionLogStepDef = 0.0;
		$timeStepDef = 0.0;


		$calculationParametersDef = $this->getDoctrine()->getRepository(CalculationParametersDef::class)->findOneBy(
			['idUser' => $idUser]);

		if ($calculationParametersDef != null) {
			$horizScanDef = $calculationParametersDef->isHorizScanDef();
			$maxIter = $calculationParametersDef->getMaxItNbDef();
			$relaxCoef = $calculationParametersDef->getRelaxCoeffDef();
			$precision = $calculationParametersDef->getPrecisionRequestDef();
			

			$vertScanDef = $calculationParametersDef->isVertScanDef();
			$stopTopSurfDef = $calculationParametersDef->getStopTopSurfDef();
			$stopIntDef = $calculationParametersDef->getStopIntDef();
			$stopBottomSurfDef = $calculationParametersDef->getStopBottomSurfDef();
			$stopAvgDef = $calculationParametersDef->getStopAvgDef();

			$isStudyAlphaTopFixedDef = $calculationParametersDef->isStudyAlphaTopFixedDef();
			$isStudyAlphaBottomFixedDef = $calculationParametersDef->isStudyAlphaBottomFixedDef();
			$isStudyAlphaLeftFixedDef = $calculationParametersDef->isStudyAlphaLeftFixedDef();
			$isStudyAlphaRightFixedDef = $calculationParametersDef->isStudyAlphaRightFixedDef();
			$isStudyAlphaFrontFixedDef = $calculationParametersDef->isStudyAlphaFrontFixedDef();
			$isStudyAlphaRearFixedDef = $calculationParametersDef->isStudyAlphaRearFixedDef();

			$studyAlphaTopDef = $calculationParametersDef->getStudyAlphaTopDef();
			$studyAlphaBottomDef = $calculationParametersDef->getStudyAlphaBottomDef();
			$studyAlphaLeftDef = $calculationParametersDef->getStudyAlphaLeftDef();
			$studyAlphaRightDef = $calculationParametersDef->getStudyAlphaRightDef();
			$studyAlphaFrontDef = $calculationParametersDef->getStudyAlphaFrontDef();
			$studyAlphaRearDef = $calculationParametersDef->getStudyAlphaRearDef();

			$storageStepDef = $calculationParametersDef->getStorageStepDef();
			$precisionLogStepDef = $calculationParametersDef->getPrecisionLogStepDef();
			$timeStepDef = $calculationParametersDef->getTimeStepDef();

			if ($horizScanDef == true) {
				$isHorizScanDef = "checked";
			} else {
				$isHorizScanDef1 = "checked";
			}

			if ($vertScanDef == true) {
				$isVertScanDef = "checked";
			} else {
				$isVertScanDef1 = "checked";
			}

			if ($isStudyAlphaTopFixedDef == true) {
				$studyAlphaTopFixedDef = "checked";
				$disabledAlpha0 = "";
			}

			if ($isStudyAlphaBottomFixedDef == true) {
				$studyAlphaBottomFixedDef = "checked";
				$disabledAlpha1 = "";
			}

			if ($isStudyAlphaLeftFixedDef == true) {
				$studyAlphaLeftFixedDef = "checked";
				$disabledAlpha2 = "";
			}

			if ($isStudyAlphaRightFixedDef == true) {
				$studyAlphaRightFixedDef = "checked";
				$disabledAlpha3 = "";
			}

			if ($isStudyAlphaFrontFixedDef == true) {
				$studyAlphaFrontFixedDef = "checked";
				$disabledAlpha4 = "";
			}

			if ($isStudyAlphaRearFixedDef == true) {
				$studyAlphaRearFixedDef = "checked";
				$disabledAlpha5 = "";
			}
		}

		return $this->render("settings/calculation.html.twig", array(
			'isHorizScanDef' => $isHorizScanDef,
			'isHorizScanDef1' => $isHorizScanDef1,
			'maxIter' => $maxIter,
			'relaxCoef' => $relaxCoef,
			'precision' => $precision,
			'isVertScanDef' => $isVertScanDef,
			'isVertScanDef1' => $isVertScanDef1,
			'stopTopSurfDef' => $stopTopSurfDef,
			'stopIntDef' => $stopIntDef,
			'stopBottomSurfDef' => $stopBottomSurfDef,
			'stopAvgDef' => $stopAvgDef,
			'studyAlphaTopFixedDef' => $studyAlphaTopFixedDef,
			'studyAlphaBottomFixedDef' => $studyAlphaBottomFixedDef,
			'studyAlphaLeftFixedDef' => $studyAlphaLeftFixedDef,
			'studyAlphaRightFixedDef' => $studyAlphaRightFixedDef,
			'studyAlphaFrontFixedDef' => $studyAlphaFrontFixedDef,
			'studyAlphaRearFixedDef' => $studyAlphaRearFixedDef,
			'alphavalue0' => $studyAlphaTopDef,
			'alphavalue1' => $studyAlphaBottomDef,
			'alphavalue2' => $studyAlphaLeftDef,
			'alphavalue3' => $studyAlphaRightDef,
			'alphavalue4' => $studyAlphaFrontDef,
			'alphavalue5' => $studyAlphaRearDef,
			'storagestep' => $storageStepDef,
			'precisionlog' => $precisionLogStepDef,
			'timestep' => $timeStepDef,
			'disabledAlpha0' => $disabledAlpha0,
			'disabledAlpha1' => $disabledAlpha1,
			'disabledAlpha2' => $disabledAlpha2,
			'disabledAlpha3' => $disabledAlpha3,
			'disabledAlpha4' => $disabledAlpha4,
			'disabledAlpha5' => $disabledAlpha5,
		));
	}

	/**
     * @Route("/settings/mesh", name="meshSettings")
     */
	public function meshAction() 
	{

		$userCurrent = $this->getUser();

		if ($userCurrent == null) {
			return $this->redirectToRoute('login');
		}

		$idUser = $userCurrent->getIdUser();

		$dimension1 = 0.1;
		$dimension2 = 0.1;
		$dimension3 = 0.1;

		$meshParamDef = $this->getDoctrine()->getRepository(MeshParamDef::class)->findOneBy(['idUser' => $idUser]);

		if ($meshParamDef != null) {
			$dimension1 = $meshParamDef->getMesh1Size();
			$dimension2 = $meshParamDef->getMesh2Size();
			$dimension3 = $meshParamDef->getMesh3Size();
		}

		return $this->render("settings/mesh.html.twig", array(
			'dimension1' => $dimension1,
			'dimension2' => $dimension2,
			'dimension3' => $dimension3,
		));
	}

	/**
     * @Route("/settings/result", name="resultSettings")
     */
	public function resultAction()
	{
		$userCurrent = $this->getUser();

		if ($userCurrent == null) {
			return $this->redirectToRoute('login');
		}

		$idUser = $userCurrent->getIdUser();

		$axis1TopSurf = 0.0;
		$axis2TopSurf = 0.0;
		$axis3TopSurf = 0.0;
		$axis1IntPt = 0.0;
		$axis2IntPt = 0.0;
		$axis3IntPt = 0.0;
		$axis1BotSurf = 0.0;
		$axis2BotSurf = 0.0;
		$axis3BotSurf = 0.0;
		$axis1PL23 = 0.0;
		$axis2PL13 = 0.0;
		$axis3PL12 = 0.0;
		$axis2Axe1 = 0.0;
		$axis3Axe1 = 0.0;
		$axis1Axe2 = 0.0;
		$axis3Axe2 = 0.0;
		$axis1Axe3 = 0.0;
		$axis2Axe3 = 0.0;

		$tempRecordPtsDef = $this->getDoctrine()->getRepository(TempRecordPtsDef::class)->findOneBy(['idUser' => $idUser]);

		if ($tempRecordPtsDef != null) {
			$axis1TopSurf = $tempRecordPtsDef->getAxis1PtTopSurfDef();
			$axis2TopSurf = $tempRecordPtsDef->getAxis2PtTopSurfDef();
			$axis3TopSurf = $tempRecordPtsDef->getAxis3PtTopSurfDef();

			$axis1IntPt = $tempRecordPtsDef->getAxis1PtIntPtDef();
			$axis2IntPt = $tempRecordPtsDef->getAxis2PtIntPtDef();
			$axis3IntPt = $tempRecordPtsDef->getAxis3PtIntPtDef();

			$axis1BotSurf = $tempRecordPtsDef->getAxis1PtBotSurfDef();
			$axis2BotSurf = $tempRecordPtsDef->getAxis1PtBotSurfDef();
			$axis3BotSurf = $tempRecordPtsDef->getAxis1PtBotSurfDef();

			$axis1PL23 = $tempRecordPtsDef->getAxis1Pl23Def();
			$axis2PL13 = $tempRecordPtsDef->getAxis2Pl13Def();
			$axis3PL12 = $tempRecordPtsDef->getAxis3Pl12Def();

			$axis2Axe1 = $tempRecordPtsDef->getAxis2Ax1Def();
			$axis3Axe1 = $tempRecordPtsDef->getAxis3Ax1Def();

			$axis1Axe2 = $tempRecordPtsDef->getAxis1Ax2Def();
			$axis3Axe2 = $tempRecordPtsDef->getAxis3Ax2Def();
			$axis1Axe3 = $tempRecordPtsDef->getAxis1Ax3Def();
			$axis2Axe3 = $tempRecordPtsDef->getAxis2Ax3Def();
		}

		return $this->render("settings/result.html.twig", array(
			'axis1TopSurf' => $axis1TopSurf,
			'axis2TopSurf' => $axis2TopSurf,
			'axis3TopSurf' => $axis3TopSurf,
			'axis1IntPt' => $axis1IntPt,
			'axis2IntPt' => $axis2IntPt,
			'axis3IntPt' => $axis3IntPt,
			'axis1BotSurf' => $axis1BotSurf,
			'axis2BotSurf' => $axis2BotSurf,
			'axis3BotSurf' => $axis3BotSurf,
			'axis1PL23' => $axis1PL23,
			'axis2PL13' => $axis2PL13,
			'axis3PL12' => $axis3PL12,
			'axis2Axe1' => $axis2Axe1,
			'axis3Axe1' => $axis3Axe1,
			'axis1Axe2' => $axis1Axe2,
			'axis3Axe2' => $axis3Axe2,
			'axis1Axe3' => $axis1Axe3,
			'axis2Axe3' => $axis2Axe3,
		));
	}

	/**
     * @Route("/settings/save", name="saveSettings")
     */
	public function saveAction(Request $request)
	{
		$session = $request->getSession();

		if ($request->getMethod() == "POST") {
			$data = $request->request->all();

			$userCurrent = $this->getUser();
			$idUser = $userCurrent->getIdUser();

			$em = $this->getDoctrine()->getManager();

			if (isset($data['_dimension1']) && isset($data['_dimension2']) &&  isset($data['_dimension3'])) {
				$meshParamDef = $em->getRepository(MeshParamDef::class)->findOneBy(['idUser' => $idUser]);

				if ($meshParamDef != null) {
					$meshParamDef->setMesh1Size($data['_dimension1']);
					$meshParamDef->setMesh2Size($data['_dimension2']);
					$meshParamDef->setMesh3Size($data['_dimension3']);

					$em->flush();

					$session->getFlashBag()->add(
					    'success',
					    'Update mesh success.'
					);

					return $this->redirectToRoute('meshSettings', array(
						'dimension1' => $data['_dimension1'],
						'dimension2' => $data['_dimension2'],
						'dimension3' => $data['_dimension3'],
					));
				}
			}

			if (isset($data['_hradiobutton']) && isset($data['_maxIter']) && isset($data['_relaxCoef']) && isset($data['_precision']) && isset($data['_vradiobutton']) && isset($data['_r2Suface']) && isset($data['_r2Internal']) && isset($data['_r2Bottom']) && isset($data['_r2Average']) && isset($data['_storageStep']) && isset($data['_precisionStep']) && isset($data['_timeStep'])) {

				$calculationParametersDef = $em->getRepository(CalculationParametersDef::class)->findOneBy(['idUser' => $idUser]);

				if ($calculationParametersDef != null) {
					$calculationParametersDef->setHorizScanDef($data['_hradiobutton']);
					$calculationParametersDef->setMaxItNbDef($data['_maxIter']);
					$calculationParametersDef->setRelaxCoeffDef($data['_relaxCoef']);
					$calculationParametersDef->setPrecisionRequestDef($data['_precision']);
					$calculationParametersDef->setVertScanDef($data['_vradiobutton']);
					$calculationParametersDef->setStopTopSurfDef($data['_r2Suface']);
					$calculationParametersDef->setStopIntDef($data['_r2Internal']);
					$calculationParametersDef->setStopBottomSurfDef($data['_r2Bottom']);
					$calculationParametersDef->setStopAvgDef($data['_r2Average']);

					$studyAlphaTopFixedDef = 0;
					$isStudyAlphaTopFixedDef = "";
					$disabledAlpha0 = "disabled";
					$alphavalue0 = 0;

					$studyAlphaBottomFixedDef = 0;
					$isStudyAlphaBottomFixedDef = "";
					$disabledAlpha1 = "disabled";
					$alphavalue1 = 0;

					$studyAlphaLeftFixedDef = 0;
					$isStudyAlphaLeftFixedDef = "";
					$disabledAlpha2 = "disabled";
					$alphavalue2 = 0;

					$studyAlphaRightFixedDef = 0;
					$isStudyAlphaRightFixedDef = "";
					$disabledAlpha3 = "disabled";
					$alphavalue3 = 0;

					$studyAlphaFrontFixedDef = 0;
					$isStudyAlphaFrontFixedDef = "";
					$disabledAlpha4 = "disabled";
					$alphavalue4 = 0;

					$studyAlphaRearFixedDef = 0;
					$isStudyAlphaRearFixedDef = "";
					$disabledAlpha5 = "disabled";
					$alphavalue5 = 0;

					if (isset($data['_isalpha0'])) {
						$studyAlphaTopFixedDef = 1;
						$isStudyAlphaTopFixedDef = "checked";
						$disabledAlpha0 = "";
					}

					if (isset($data['_isalpha1'])) {
						$studyAlphaBottomFixedDef = 1;
						$isStudyAlphaBottomFixedDef = "checked";
						$disabledAlpha1 = "";
					}

					if (isset($data['_isalpha2'])) {
						$studyAlphaLeftFixedDef = 1;
						$isStudyAlphaLeftFixedDef = "checked";
						$disabledAlpha2 = "";
					}

					if (isset($data['_isalpha3'])) {
						$studyAlphaRightFixedDef = 1;
						$isStudyAlphaRightFixedDef = "checked";
						$disabledAlpha3 = "";
					}

					if (isset($data['_isalpha4'])) {
						$studyAlphaFrontFixedDef = 1;
						$isStudyAlphaFrontFixedDef = "checked";
						$disabledAlpha4 = "";
					}

					if (isset($data['_isalpha5'])) {
						$studyAlphaRearFixedDef = 1;
						$isStudyAlphaRearFixedDef = "checked";
						$disabledAlpha5 = "";
					}

					$calculationParametersDef->setStudyAlphaTopFixedDef($studyAlphaTopFixedDef);
					$calculationParametersDef->setStudyAlphaBottomFixedDef($studyAlphaBottomFixedDef);
					$calculationParametersDef->setStudyAlphaLeftFixedDef($studyAlphaLeftFixedDef);
					$calculationParametersDef->setStudyAlphaRightFixedDef($studyAlphaRightFixedDef);
					$calculationParametersDef->setStudyAlphaFrontFixedDef($studyAlphaFrontFixedDef);
					$calculationParametersDef->setStudyAlphaRearFixedDef($studyAlphaRearFixedDef);

					if (isset($data['_alphavalue0'])) {
						$calculationParametersDef->setStudyAlphaTopDef($data['_alphavalue0']);
						$alphavalue0 = $data['_alphavalue0'];
					}

					if (isset($data['_alphavalue1'])) {
						$calculationParametersDef->setStudyAlphaBottomDef($data['_alphavalue1']);
						$alphavalue1 = $data['_alphavalue1'];
					}

					if (isset($data['_alphavalue2'])) {
						$calculationParametersDef->setStudyAlphaLeftDef($data['_alphavalue2']);
						$alphavalue2 = $data['_alphavalue2'];
					}

					if (isset($data['_alphavalue3'])) {
						$calculationParametersDef->setStudyAlphaRightDef($data['_alphavalue3']);
						$alphavalue3 = $data['_alphavalue3'];
					}

					if (isset($data['_alphavalue4'])) {
						$calculationParametersDef->setStudyAlphaFrontDef($data['_alphavalue4']);
						$alphavalue4 = $data['_alphavalue4'];
					}

					if (isset($data['_alphavalue5'])) {
						$calculationParametersDef->setStudyAlphaRearDef($data['_alphavalue5']);
						$alphavalue5 = $data['_alphavalue5'];
					}

					$calculationParametersDef->setStorageStepDef($data['_storageStep']);
					$calculationParametersDef->setPrecisionLogStepDef($data['_precisionStep']);
					$calculationParametersDef->setTimeStepDef($data['_timeStep']);

					$em->flush();

					$isHorizScanDef = "";
					$isHorizScanDef1 = "";
					$isVertScanDef = "";
					$isVertScanDef1 = "";

					if ($data['_hradiobutton'] == 1) {
						$isHorizScanDef = "checked";
					} else {
						$isHorizScanDef1 = "checked";
					}

					if ($data['_vradiobutton'] == 1) {
						$isVertScanDef = "checked";
					} else {
						$isVertScanDef1 = "checked";
					}

					$session->getFlashBag()->add(
					    'success',
					    'Update calculation success'
					);


					return $this->redirectToRoute('calculationSettings', array(
						'isHorizScanDef' => $isHorizScanDef,
						'isHorizScanDef1' => $isHorizScanDef1,
						'maxIter' => $data['_maxIter'],
						'relaxCoef' => $data['_relaxCoef'],
						'precision' => $data['_precision'],
						'isVertScanDef' => $isVertScanDef,
						'isVertScanDef1' => $isVertScanDef1,
						'stopTopSurfDef' => $data['_r2Suface'],
						'stopIntDef' => $data['_r2Internal'],
						'stopBottomSurfDef' => $data['_r2Bottom'],
						'stopAvgDef' => $data['_r2Average'],
						'studyAlphaTopFixedDef' => $isStudyAlphaTopFixedDef,
						'studyAlphaBottomFixedDef' => $isStudyAlphaBottomFixedDef,
						'studyAlphaLeftFixedDef' => $isStudyAlphaLeftFixedDef,
						'studyAlphaRightFixedDef' => $isStudyAlphaRightFixedDef,
						'studyAlphaFrontFixedDef' => $isStudyAlphaFrontFixedDef,
						'studyAlphaRearFixedDef' => $isStudyAlphaRearFixedDef,
						'alphavalue0' => $alphavalue0,
						'alphavalue1' => $alphavalue1,
						'alphavalue2' => $alphavalue2,
						'alphavalue3' => $alphavalue3,
						'alphavalue4' => $alphavalue4,
						'alphavalue5' => $alphavalue5,
						'storagestep' => $data['_storageStep'],
						'precisionlog' => $data['_precisionStep'],
						'timestep' => $data['_timeStep'],
						'disabledAlpha0' => $disabledAlpha0,
						'disabledAlpha1' => $disabledAlpha1,
						'disabledAlpha2' => $disabledAlpha2,
						'disabledAlpha3' => $disabledAlpha3,
						'disabledAlpha4' => $disabledAlpha4,
						'disabledAlpha5' => $disabledAlpha5,
					));
				}
			}

			if (isset($data['_axis1Top']) && isset($data['_axis2Top']) && isset($data['_axis3Top']) && isset($data['_axis1Int']) && isset($data['_axis2Int']) && isset($data['_axis3Int']) && isset($data['_axis1Bot']) && isset($data['_axis2Bot']) && isset($data['_axis3Bot']) && isset($data['_plan1Value']) && isset($data['_plan2Value']) && isset($data['_plan3Value']) && isset($data['_axis2Axe1']) && isset($data['_axis3Axe1']) && isset($data['_axis1Axe2']) && isset($data['_axis3Axe2']) && isset($data['_axis1Axe3']) && isset($data['_axis2Axe3'])){

				$tempRecordPtsDef = $em->getRepository(TempRecordPtsDef::class)->findOneBy(['idUser' => $idUser]);

				if ($tempRecordPtsDef != null) {
					$tempRecordPtsDef->setAxis1PtTopSurfDef($data['_axis1Top']);
					$tempRecordPtsDef->setAxis2PtTopSurfDef($data['_axis2Top']);
					$tempRecordPtsDef->setAxis3PtTopSurfDef($data['_axis3Top']);

					$tempRecordPtsDef->setAxis1PtIntPtDef($data['_axis1Int']);
					$tempRecordPtsDef->setAxis2PtIntPtDef($data['_axis2Int']);
					$tempRecordPtsDef->setAxis3PtIntPtDef($data['_axis3Int']);

					$tempRecordPtsDef->setAxis1PtBotSurfDef($data['_axis1Bot']);
					$tempRecordPtsDef->setAxis2PtBotSurfDef($data['_axis2Bot']);
					$tempRecordPtsDef->setAxis3PtBotSurfDef($data['_axis3Bot']);

					$tempRecordPtsDef->setAxis1Pl23Def($data['_plan1Value']);
					$tempRecordPtsDef->setAxis2Pl13Def($data['_plan2Value']);
					$tempRecordPtsDef->setAxis3Pl12Def($data['_plan3Value']);

					$tempRecordPtsDef->setAxis2Ax1Def($data['_axis2Axe1']);
					$tempRecordPtsDef->setAxis3Ax1Def($data['_axis3Axe1']);

					$tempRecordPtsDef->setAxis1Ax2Def($data['_axis1Axe2']);
					$tempRecordPtsDef->setAxis3Ax2Def($data['_axis3Axe2']);

					$tempRecordPtsDef->setAxis1Ax3Def($data['_axis1Axe3']);
					$tempRecordPtsDef->setAxis2Ax3Def($data['_axis2Axe3']);

					$em->flush();

					$session->getFlashBag()->add(
					    'success',
					    'Update result success.'
					);

					return $this->redirectToRoute('resultSettings', array(
						'axis1TopSurf' => $data['_axis1Top'],
						'axis2TopSurf' => $data['_axis2Top'],
						'axis3TopSurf' => $data['_axis3Top'],
						'axis1IntPt' => $data['_axis1Int'],
						'axis2IntPt' => $data['_axis2Int'],
						'axis3IntPt' => $data['_axis1Int'],
						'axis1BotSurf' => $data['_axis1Bot'],
						'axis2BotSurf' => $data['_axis2Bot'],
						'axis3BotSurf' => $data['_axis3Bot'],
						'axis1PL23' => $data['_plan1Value'],
						'axis2PL13' => $data['_plan2Value'],
						'axis3PL12' => $data['_plan3Value'],
						'axis2Axe1' => $data['_axis2Axe1'],
						'axis3Axe1' => $data['_axis3Axe1'],
						'axis1Axe2' => $data['_axis1Axe2'],
						'axis3Axe2' => $data['_axis3Axe2'],
						'axis1Axe3' => $data['_axis1Axe3'],
						'axis2Axe3' => $data['_axis2Axe3'],

					));
				}
			}
		}

		return $this->redirectToRoute('login');
	}
}

