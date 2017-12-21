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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\InitialTemperature;
use AppBundle\Entity\StudyEquipments;
use AppBundle\Entity\MeshGeneration;
use AppBundle\Entity\MeshPosition;
use AppBundle\Entity\ProductElmt;
use AppBundle\Entity\Production;
use AppBundle\Entity\Packing;
use AppBundle\Entity\Product;
use AppBundle\Entity\Studies;
use AppBundle\Entity\Ln2user;
use AppBundle\Entity\PipeGen;
use AppBundle\Entity\Shape;
use AppBundle\Cryosoft\CheckControlService;



class CheckControlController extends Controller 
{
	/**
     * @Route("/checkcontrol", name="checkcontrol")
     */
	public function checkcontrolAction(CheckControlService $check) 
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

		$check->isCheckControl($idUser, $idStudy, $loadEquipment, $idProd);

		$calcModel = false;
		if ($check->isStdCalcModeChecked($idUser, $idStudy)) {
			$calcModel = true;
		} 

		$stdCustomer = false;
		if ($check->isStdCustomerChecked($idStudy)) {
			$stdCustomer = true;
		} 

		$stdProduct = false;
		if ($check->isStdProductChecked($idStudy)) {
			$stdProduct = true;
		} 

		$stdMesh_Init = false;
		if ($check->isStdMesh_InitTempChecked($idStudy, $idProd)) {
			$stdMesh_Init = true;
		} 

		$stdEquipment = false;
		if ($check->isStdEquipmentChecked($idStudy, $loadEquipment)) {
			$stdEquipment = true;
		} 

		$stdPacking = false;
		if ($check->isStdPackingChecked($idStudy)) {
			$stdPacking = true;
		} 

		$isLineEnabled = false;
		if ($this->isLineEnabled()) {
			$isLineEnabled = true;
		} 

		$stdLine = false;
		if ($check->isStdLineChecked($idStudy)) {
			$stdLine = true;
		} 

		return $this->render('checkcontrol/checkcontrol.html.twig', array(
			'calcModel' => $calcModel,
			'stdCustomer' => $stdCustomer,
			'stdProduct' => $stdProduct,
			'stdMesh_Init' => $stdMesh_Init,
			'stdEquipment' => $stdEquipment,
			'stdPacking' => $stdPacking,
			'isLineEnabled' => $isLineEnabled,
			'stdLine' => $stdLine,
		));
	}

	public function isLineEnabled() 
	{
		$session = $this->get('session');
		$idStudy = $session->get('idStudy');

		$study = $this->getDoctrine()->getRepository(Studies::class)->findOneBy(['idStudy' => $idStudy]);

		if ($study != null) {
			$pipeline = $study->getOptionCryopipeline();
			if ($pipeline == 0) {
				return false;
			}
		}
		return true;
	}
}